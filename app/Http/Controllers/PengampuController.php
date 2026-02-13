<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Golongan;
use App\Models\MataKuliah;
use App\Models\Pengampu;
use Illuminate\Http\Request;

class PengampuController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengampu::query()->with(['dosen', 'mataKuliah']);

        if ($request->filled('q')) {
            $q = $request->string('q');
            $query->whereHas('dosen', function ($sub) use ($q) {
                $sub->where('nip', 'like', "%{$q}%")
                    ->orWhere('nama', 'like', "%{$q}%");
            })->orWhereHas('mataKuliah', function ($sub) use ($q) {
                $sub->where('kode_mk', 'like', "%{$q}%")
                    ->orWhere('nama_mk', 'like', "%{$q}%");
            });
        }

        $data = [
            'pengampu' => $query->orderByDesc('id')->paginate(10)->withQueryString(),
            'dosenList' => Dosen::orderBy('nama')->get(),
            'mataKuliahList' => MataKuliah::orderBy('kode_mk')->get(),
            'golonganList' => Golongan::orderBy('nama_gol')->get(),
        ];

        return view('pages.pengampu.index', $data);
    }

    public function create()
    {
        return view('pages.pengampu.create', [
            'dosenList' => Dosen::orderBy('nama')->get(),
            'mataKuliahList' => collect(),
            'golonganList' => Golongan::orderBy('nama_gol')->get(),
        ]);
    }

    public function edit(Pengampu $pengampu)
    {
        return view('pages.pengampu.edit', [
            'pengampu' => $pengampu,
            'dosenList' => Dosen::orderBy('nama')->get(),
            'mataKuliahList' => MataKuliah::orderBy('kode_mk')->get(),
            'golonganList' => Golongan::orderBy('nama_gol')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_mk' => 'required|exists:mata_kuliah,kode_mk',
            'nip' => 'required|exists:dosen,nip',
            'tahun_akademik' => 'required|string|max:20',
            'semester_akademik' => 'required|string|max:20',
            'status' => 'required|string|max:20',
        ]);

        Pengampu::create($validated);

        return redirect()->route('pengampu.index')->with('success', 'Pengampu berhasil ditambahkan.');
    }

    public function update(Request $request, Pengampu $pengampu)
    {
        $validated = $request->validate([
            'kode_mk' => 'required|exists:mata_kuliah,kode_mk',
            'nip' => 'required|exists:dosen,nip',
            'tahun_akademik' => 'required|string|max:20',
            'semester_akademik' => 'required|string|max:20',
            'status' => 'required|string|max:20',
        ]);

        $pengampu->update($validated);

        return redirect()->route('pengampu.index')->with('success', 'Pengampu berhasil diperbarui.');
    }

    public function destroy(Pengampu $pengampu)
    {
        $pengampu->delete();

        return redirect()->route('pengampu.index')->with('success', 'Pengampu berhasil dihapus.');
    }

    public function getMataKuliahBySemester(Request $request)
    {
        $semester = $request->query('semester');
        
        if (!$semester) {
            return response()->json([]);
        }

        $mataKuliah = MataKuliah::where('semester', $semester)
            ->orderBy('kode_mk')
            ->get(['kode_mk', 'nama_mk', 'sks', 'semester']);

        return response()->json($mataKuliah);
    }
}
