<?php

namespace App\Http\Controllers;

use App\Models\Krs;
use App\Models\Pengampu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NilaiController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $role = $user->role ?? 'guest';

        $pengampuQuery = Pengampu::with(['mataKuliah', 'dosen'])->orderBy('kode_mk');
        if ($role === 'dosen') {
            $pengampuQuery->where('nip', $user->nip);
        }

        $pengampuList = $pengampuQuery->get();

        $selectedKodeMk = (string) $request->query('kode_mk', '');
        $selectedTahun = (string) $request->query('tahun_akademik', '');
        $selectedSemester = (string) $request->query('semester_akademik', '');

        if ($request->filled('paket')) {
            [$mk, $thn, $smt] = array_pad(explode('|', (string) $request->query('paket')), 3, '');
            $selectedKodeMk = $mk;
            $selectedTahun = $thn;
            $selectedSemester = $smt;
        }

        if ($pengampuList->isNotEmpty() && $selectedKodeMk === '') {
            $selectedKodeMk = (string) $pengampuList->first()->kode_mk;
            $selectedTahun = (string) $pengampuList->first()->tahun_akademik;
            $selectedSemester = (string) $pengampuList->first()->semester_akademik;
        }

        if ($selectedKodeMk !== '') {
            $isAllowed = $pengampuList->contains(function ($item) use ($selectedKodeMk, $selectedTahun, $selectedSemester) {
                return $item->kode_mk === $selectedKodeMk
                    && $item->tahun_akademik === $selectedTahun
                    && $item->semester_akademik === $selectedSemester;
            });

            if (!$isAllowed) {
                $selectedKodeMk = '';
                $selectedTahun = '';
                $selectedSemester = '';
            }
        }

        $krs = collect();
        if ($selectedKodeMk !== '') {
            $krsQuery = Krs::with(['mahasiswa', 'mataKuliah'])
                ->where('kode_mk', $selectedKodeMk)
                ->where('tahun_akademik', $selectedTahun)
                ->where('semester_akademik', $selectedSemester);

            if ($request->filled('q')) {
                $q = $request->string('q');
                $krsQuery->whereHas('mahasiswa', function ($sub) use ($q) {
                    $sub->where('nim', 'like', "%{$q}%")
                        ->orWhere('nama', 'like', "%{$q}%");
                });
            }

            $krs = $krsQuery->orderBy('nim')->get();
        }

        return view('pages.nilai.index', [
            'role' => $role,
            'pengampuList' => $pengampuList,
            'selectedKodeMk' => $selectedKodeMk,
            'selectedTahun' => $selectedTahun,
            'selectedSemester' => $selectedSemester,
            'krsList' => $krs,
            'storeRoute' => $role === 'admin' ? 'admin.nilai.store' : 'dosen.nilai.store',
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $role = $user->role ?? 'guest';

        $validated = $request->validate([
            'nim' => 'required|exists:mahasiswa,nim',
            'kode_mk' => 'required|exists:mata_kuliah,kode_mk',
            'tahun_akademik' => 'required|string|max:20',
            'semester_akademik' => 'required|string|max:20',
            'nilai_angka' => 'required|numeric|min:0|max:100',
        ]);

        $pengampuQuery = Pengampu::where('kode_mk', $validated['kode_mk'])
            ->where('tahun_akademik', $validated['tahun_akademik'])
            ->where('semester_akademik', $validated['semester_akademik']);

        if ($role === 'dosen') {
            $pengampuQuery->where('nip', $user->nip);
        }

        if (!$pengampuQuery->exists()) {
            return back()->with('error', 'Anda tidak memiliki akses input nilai untuk mata kuliah ini.');
        }

        $krs = Krs::where('nim', $validated['nim'])
            ->where('kode_mk', $validated['kode_mk'])
            ->where('tahun_akademik', $validated['tahun_akademik'])
            ->where('semester_akademik', $validated['semester_akademik'])
            ->first();

        if (!$krs) {
            return back()->with('error', 'Data KRS mahasiswa untuk mata kuliah ini tidak ditemukan.');
        }

        $angka = (float) $validated['nilai_angka'];
        [$nilaiAkhir, $statusLulus] = $this->mapNilai($angka);

        $krs->update([
            'nilai_angka' => $angka,
            'nilai_akhir' => $nilaiAkhir,
            'status_lulus' => $statusLulus,
        ]);

        return back()->with('success', 'Nilai berhasil disimpan.');
    }

    private function mapNilai(float $nilaiAngka): array
    {
        if ($nilaiAngka >= 85) {
            return ['A', 'Lulus'];
        }
        if ($nilaiAngka >= 75) {
            return ['B', 'Lulus'];
        }
        if ($nilaiAngka >= 65) {
            return ['C', 'Lulus'];
        }
        if ($nilaiAngka >= 50) {
            return ['D', 'Tidak Lulus'];
        }

        return ['E', 'Tidak Lulus'];
    }
}
