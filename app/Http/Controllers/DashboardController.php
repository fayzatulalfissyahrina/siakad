<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
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
        $today = Carbon::today();

        $data = [
            'mahasiswaCount' => Mahasiswa::count(),
            'dosenCount' => Dosen::count(),
            'mataKuliahCount' => MataKuliah::count(),
            'presensiHariIniCount' => PresensiAkademik::whereDate('tanggal', $today)->count(),
            'recentPresensi' => PresensiAkademik::with(['mahasiswa', 'mataKuliah'])
                ->orderByDesc('tanggal')
                ->orderByDesc('id')
                ->limit(5)
                ->get(),
        ];

        $user = Auth::user();
        if ($user && $user->role === 'dosen') {
            $kodeMk = Pengampu::where('nip', $user->nip)->pluck('kode_mk');
            $jadwal = JadwalAkademik::with(['mataKuliah', 'golongan', 'ruang'])
                ->whereIn('kode_mk', $kodeMk)
                ->orderBy('hari')
                ->get();

            $data['dosenJadwal'] = $jadwal->map(function ($item) {
                $item->semester_kelas = AcademicHelper::semesterKelas(
                    $item->golongan->angkatan ?? null,
                    $item->tahun_akademik,
                    $item->semester_akademik
                );
                return $item;
            });
        }

        return view('pages.dashboard.index', $data);
    }
}
