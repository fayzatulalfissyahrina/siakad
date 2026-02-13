<?php

namespace App\Http\Controllers;

use App\Models\Golongan;
use App\Models\Mahasiswa;
use App\Models\PresensiAkademik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $role = Auth::user()->role ?? 'guest';
        
        $golonganList = Golongan::orderBy('nama_gol')->get();
        
        $selectedGol = $request->query('golongan');
        $selectedSemester = $request->query('semester');
        
        $query = PresensiAkademik::query()->with(['mahasiswa', 'mataKuliah']);
        
        if ($role === 'dosen') {
            $nip = Auth::user()->nip;
            $query->whereHas('mataKuliah.pengampu', function ($q) use ($nip) {
                $q->where('nip', $nip);
            });
        }
        
        if ($selectedGol) {
            $query->whereHas('mahasiswa', function ($q) use ($selectedGol) {
                $q->where('id_gol', $selectedGol);
            });
        }
        
        if ($selectedSemester) {
            $query->whereHas('mahasiswa', function ($q) use ($selectedSemester) {
                $q->where('semester', $selectedSemester);
            });
        }
        
        if ($request->filled('q')) {
            $q = $request->string('q');
            $query->whereHas('mahasiswa', function ($sub) use ($q) {
                $sub->where('nim', 'like', "%{$q}%")
                    ->orWhere('nama', 'like', "%{$q}%");
            });
        }
        
        if ($request->filled('tanggal_awal')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_awal);
        }
        
        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_akhir);
        }
        
        $presensi = $query->orderByDesc('tanggal')->orderByDesc('id')->paginate(20)->withQueryString();
        
        $stats = [
            'total' => $presensi->total(),
            'hadir' => PresensiAkademik::when($selectedGol, fn($q) => $q->whereHas('mahasiswa', fn($sq) => $sq->where('id_gol', $selectedGol)))
                ->when($selectedSemester, fn($q) => $q->whereHas('mahasiswa', fn($sq) => $sq->where('semester', $selectedSemester)))
                ->where('status_kehadiran', 'hadir')->count(),
            'izin' => PresensiAkademik::when($selectedGol, fn($q) => $q->whereHas('mahasiswa', fn($sq) => $sq->where('id_gol', $selectedGol)))
                ->when($selectedSemester, fn($q) => $q->whereHas('mahasiswa', fn($sq) => $sq->where('semester', $selectedSemester)))
                ->where('status_kehadiran', 'izin')->count(),
            'sakit' => PresensiAkademik::when($selectedGol, fn($q) => $q->whereHas('mahasiswa', fn($sq) => $sq->where('id_gol', $selectedGol)))
                ->when($selectedSemester, fn($q) => $q->whereHas('mahasiswa', fn($sq) => $sq->where('semester', $selectedSemester)))
                ->where('status_kehadiran', 'sakit')->count(),
            'alpha' => PresensiAkademik::when($selectedGol, fn($q) => $q->whereHas('mahasiswa', fn($sq) => $sq->where('id_gol', $selectedGol)))
                ->when($selectedSemester, fn($q) => $q->whereHas('mahasiswa', fn($sq) => $sq->where('semester', $selectedSemester)))
                ->where('status_kehadiran', 'alpha')->count(),
        ];
        
        return view('pages.laporan.index', [
            'role' => $role,
            'presensi' => $presensi,
            'golonganList' => $golonganList,
            'selectedGol' => $selectedGol,
            'selectedSemester' => $selectedSemester,
            'stats' => $stats,
        ]);
    }

    public function export(string $type): \Illuminate\Http\Response
    {
        if (!in_array($type, ['csv', 'xlsx', 'pdf'], true)) {
            abort(404);
        }

        $content = "Laporan akademik ($type) akan dihasilkan di sini.";
        return response($content, 200, [
            'Content-Type' => 'text/plain',
        ]);
    }
}
