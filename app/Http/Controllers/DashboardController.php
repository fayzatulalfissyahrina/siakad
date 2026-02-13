<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Golongan;
use App\Models\JadwalAkademik;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\PresensiAkademik;
use App\Models\Pengampu;
use App\Support\AcademicHelper;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = $user->role ?? 'guest';
        
        $data = [];
        
        if ($role === 'admin') {
            $data = $this->adminDashboard();
        } elseif ($role === 'dosen') {
            $data = $this->dosenDashboard($user);
        } elseif ($role === 'mahasiswa') {
            $data = $this->mahasiswaDashboard($user);
        }
        
        return view('pages.dashboard.index', $data);
    }
    
    private function adminDashboard(): array
    {
        $today = Carbon::today();
        
        return [
            'role' => 'admin',
            'mahasiswaCount' => Mahasiswa::count(),
            'dosenCount' => Dosen::count(),
            'mataKuliahCount' => MataKuliah::count(),
            'golonganCount' => Golongan::count(),
            'presensiHariIniCount' => PresensiAkademik::whereDate('tanggal', $today)->count(),
            'recentPresensi' => PresensiAkademik::with(['mahasiswa', 'mataKuliah'])
                ->orderByDesc('tanggal')
                ->orderByDesc('id')
                ->limit(5)
                ->get(),
        ];
    }
    
    private function dosenDashboard($user): array
    {
        $nip = $user->nip;
        $today = Carbon::today();
        
        $pengampu = Pengampu::with('mataKuliah')
            ->where('nip', $nip)
            ->get();
        
        $kodeMk = $pengampu->pluck('kode_mk');
        
        $jadwal = JadwalAkademik::with(['mataKuliah', 'golongan', 'ruang'])
            ->whereIn('kode_mk', $kodeMk)
            ->orderBy('hari')
            ->get()
            ->map(function ($item) {
                $item->semester_kelas = AcademicHelper::semesterKelas(
                    $item->golongan->angkatan ?? null,
                    $item->tahun_akademik,
                    $item->semester_akademik
                );
                return $item;
            });
        
        $presensiCount = PresensiAkademik::whereHas('mataKuliah', function ($q) use ($kodeMk) {
            $q->whereIn('kode_mk', $kodeMk);
        })->whereDate('tanggal', $today)->count();
        
        return [
            'role' => 'dosen',
            'dosen' => Dosen::where('nip', $nip)->first(),
            'dosenJadwal' => $jadwal,
            'pengampuCount' => $pengampu->count(),
            'presensiHariIni' => $presensiCount,
            'recentPresensi' => PresensiAkademik::with(['mahasiswa', 'mataKuliah'])
                ->whereHas('mataKuliah', function ($q) use ($kodeMk) {
                    $q->whereIn('kode_mk', $kodeMk);
                })
                ->orderByDesc('tanggal')
                ->limit(5)
                ->get(),
        ];
    }
    
    private function mahasiswaDashboard($user): array
    {
        $nim = $user->nim;
        $today = Carbon::today();
        
        $mhs = Mahasiswa::with('golongan')->where('nim', $nim)->first();
        
        $jadwal = collect();
        $golongan = null;
        
        if ($mhs && $mhs->golongan) {
            $golongan = $mhs->golongan;
            
            $semesterKelas = AcademicHelper::semesterKelas(
                $golongan->angkatan,
                date('Y') . '/' . (date('Y') + 1),
                now()->month >= 7 ? 'Ganjil' : 'Genap'
            );
            
            if ($semesterKelas) {
                $jadwal = JadwalAkademik::with(['mataKuliah', 'ruang'])
                    ->where('id_gol', $mhs->id_gol)
                    ->where('tahun_akademik', date('Y') . '/' . (date('Y') + 1))
                    ->where('semester_akademik', now()->month >= 7 ? 'Ganjil' : 'Genap')
                    ->orderBy('hari')
                    ->get();
            }
        }
        
        $presensiCount = PresensiAkademik::where('nim', $nim)->count();
        $presensiHadir = PresensiAkademik::where('nim', $nim)->where('status_kehadiran', 'hadir')->count();
        
        return [
            'role' => 'mahasiswa',
            'mahasiswa' => $mhs,
            'prodi' => $golongan->program_studi ?? '-',
            'jurusan' => $golongan->nama_gol ?? '-',
            'semester' => $mhs->semester ?? 1,
            'golongan' => $golongan,
            'jadwalKuliah' => $jadwal,
            'totalPresensi' => $presensiCount,
            'presensiHadir' => $presensiHadir,
            'recentPresensi' => PresensiAkademik::with('mataKuliah')
                ->where('nim', $nim)
                ->orderByDesc('tanggal')
                ->limit(5)
                ->get(),
        ];
    }
}
