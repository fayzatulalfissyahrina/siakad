<?php

namespace App\Http\Controllers;

use App\Models\MataKuliah;
use Illuminate\Http\Request;

class MataKuliahController extends Controller
{
    public function index(Request $request)
    {
        $query = MataKuliah::query();

        $data = [
            'mataKuliah' => $query->orderBy('kode_mk')->paginate(10)->withQueryString(),
        ];

        return view('pages.mata-kuliah.index', $data);
    }

    public function create()
    {
        return view('pages.mata-kuliah.create');
    }

    public function edit(MataKuliah $mata_kuliah)
    {
        return view('pages.mata-kuliah.edit', ['mataKuliah' => $mata_kuliah]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_mk' => 'required|string|max:20|unique:mata_kuliah,kode_mk',
            'nama_mk' => 'required|string|max:255',
            'sks' => 'required|integer|min:1|max:6',
            'semester' => 'required|integer|min:1|max:14',
            'jenis' => 'required|string|max:50',
            'deskripsi' => 'nullable|string',
        ]);

        MataKuliah::create([
            'kode_mk' => $validated['kode_mk'],
            'nama_mk' => $validated['nama_mk'],
            'sks' => $validated['sks'],
            'semester' => $validated['semester'],
            'jenis' => $validated['jenis'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'silabus' => null,
        ]);

        return redirect()->route('mata-kuliah.index')->with('success', 'Mata kuliah berhasil ditambahkan.');
    }

    public function update(Request $request, MataKuliah $mata_kuliah)
    {
        $validated = $request->validate([
            'nama_mk' => 'required|string|max:255',
            'sks' => 'required|integer|min:1|max:6',
            'semester' => 'required|integer|min:1|max:14',
            'jenis' => 'required|string|max:50',
            'deskripsi' => 'nullable|string',
        ]);

        $mata_kuliah->update([
            'nama_mk' => $validated['nama_mk'],
            'sks' => $validated['sks'],
            'semester' => $validated['semester'],
            'jenis' => $validated['jenis'],
            'deskripsi' => $validated['deskripsi'] ?? null,
        ]);

        return redirect()->route('mata-kuliah.index')->with('success', 'Mata kuliah berhasil diperbarui.');
    }

    public function destroy(MataKuliah $mata_kuliah)
    {
        $mata_kuliah->delete();

        return redirect()->route('mata-kuliah.index')->with('success', 'Mata kuliah berhasil dihapus.');
    }
}
