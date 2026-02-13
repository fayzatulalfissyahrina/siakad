<?php

namespace App\Http\Controllers;

use App\Models\Golongan;
use App\Models\JadwalAkademik;
use App\Models\MataKuliah;
use App\Models\Pengampu;
use App\Models\Ruang;
use App\Support\AcademicHelper;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        $role = auth()->user()->role ?? 'guest';

        $query = JadwalAkademik::query()->with(['mataKuliah', 'ruang', 'golongan']);

        $data = [
            'role' => $role,
            'editing' => null,
        ];

        if ($role === 'dosen') {
            $kodeMk = \App\Models\Pengampu::where('nip', auth()->user()->nip)
                ->pluck('kode_mk');
            $query->whereIn('kode_mk', $kodeMk);
            $jadwal = $query->orderBy('hari')->paginate(10)->withQueryString();
            $jadwal->getCollection()->transform(function ($item) {
                $item->semester_kelas = AcademicHelper::semesterKelas(
                    $item->golongan->angkatan ?? null,
                    $item->tahun_akademik,
                    $item->semester_akademik
                );
                return $item;
            });
            $data['jadwal'] = $jadwal;
        } elseif ($role === 'mahasiswa') {
            $mhs = \App\Models\Mahasiswa::where('nim', auth()->user()->nim)->first();
            if ($mhs) {
                $query->where('id_gol', $mhs->id_gol);
            }
            $jadwal = $query->orderBy('hari')->paginate(10)->withQueryString();
            $jadwal->getCollection()->transform(function ($item) {
                $item->semester_kelas = AcademicHelper::semesterKelas(
                    $item->golongan->angkatan ?? null,
                    $item->tahun_akademik,
                    $item->semester_akademik
                );
                return $item;
            });
            $data['jadwal'] = $jadwal;
        } else {
            if ($request->filled('q')) {
                $q = $request->string('q');
                $query->whereHas('mataKuliah', function ($sub) use ($q) {
                    $sub->where('kode_mk', 'like', "%{$q}%")
                        ->orWhere('nama_mk', 'like', "%{$q}%");
                });
            }

            $jadwal = $query->orderBy('hari')->paginate(10)->withQueryString();
            $jadwal->getCollection()->transform(function ($item) {
                $item->semester_kelas = AcademicHelper::semesterKelas(
                    $item->golongan->angkatan ?? null,
                    $item->tahun_akademik,
                    $item->semester_akademik
                );
                return $item;
            });
            $data['jadwal'] = $jadwal;
            $data['mataKuliahList'] = MataKuliah::orderBy('kode_mk')->get();
            $data['ruangList'] = Ruang::orderBy('id_ruang')->get();
            $data['golonganList'] = Golongan::orderBy('nama_gol')->get();

            if ($request->filled('edit')) {
                $data['editing'] = JadwalAkademik::with(['mataKuliah', 'ruang', 'golongan'])
                    ->findOrFail($request->edit);
            }
        }

        return view('pages.jadwal.index', $data);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'hari' => 'required|string|max:20',
            'kode_mk' => 'required|exists:mata_kuliah,kode_mk',
            'id_ruang' => 'required|exists:ruang,id_ruang',
            'id_gol' => 'required|exists:golongan,id_gol',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'tahun_akademik' => 'required|string|max:20',
            'semester_akademik' => 'required|string|max:20',
        ]);

        $pengampuAda = Pengampu::where('kode_mk', $validated['kode_mk'])
            ->where('tahun_akademik', $validated['tahun_akademik'])
            ->where('semester_akademik', $validated['semester_akademik'])
            ->exists();

        if (!$pengampuAda) {
            return back()
                ->withErrors(['kode_mk' => 'Jadwal tidak bisa disimpan karena dosen pengampu untuk mata kuliah ini belum diatur.'])
                ->withInput();
        }

        JadwalAkademik::create($validated);

        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function update(Request $request, JadwalAkademik $jadwal)
    {
        $validated = $request->validate([
            'hari' => 'required|string|max:20',
            'kode_mk' => 'required|exists:mata_kuliah,kode_mk',
            'id_ruang' => 'required|exists:ruang,id_ruang',
            'id_gol' => 'required|exists:golongan,id_gol',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'tahun_akademik' => 'required|string|max:20',
            'semester_akademik' => 'required|string|max:20',
        ]);

        $pengampuAda = Pengampu::where('kode_mk', $validated['kode_mk'])
            ->where('tahun_akademik', $validated['tahun_akademik'])
            ->where('semester_akademik', $validated['semester_akademik'])
            ->exists();

        if (!$pengampuAda) {
            return back()
                ->withErrors(['kode_mk' => 'Jadwal tidak bisa disimpan karena dosen pengampu untuk mata kuliah ini belum diatur.'])
                ->withInput();
        }

        $jadwal->update($validated);

        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroy(JadwalAkademik $jadwal)
    {
        $jadwal->delete();

        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil dihapus.');
    }
}
