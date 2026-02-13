<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Golongan;
use Illuminate\Http\Request;

class GolonganController extends Controller
{
    public function index(Request $request)
    {
        $query = Golongan::query()->with('dosenWali');

        if ($request->filled('q')) {
            $q = $request->string('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('nama_gol', 'like', "%{$q}%")
                    ->orWhere('program_studi', 'like', "%{$q}%")
                    ->orWhere('angkatan', 'like', "%{$q}%");
            });
        }

        $data = [
            'golongan' => $query->orderBy('nama_gol')->paginate(10)->withQueryString(),
            'dosenList' => Dosen::orderBy('nama')->get(),
            'editing' => null,
        ];

        if ($request->filled('edit')) {
            $data['editing'] = Golongan::findOrFail($request->edit);
        }

        return view('pages.golongan.index', $data);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_gol' => 'required|string|max:50',
            'program_studi' => 'required|string|max:100',
            'angkatan' => 'required|string|max:10',
            'dosen_wali' => 'nullable|exists:dosen,nip',
            'kapasitas' => 'required|integer|min:1|max:200',
        ]);

        Golongan::create($validated);

        return redirect()->route('golongan.index')->with('success', 'Golongan berhasil ditambahkan.');
    }

    public function update(Request $request, Golongan $golongan)
    {
        $validated = $request->validate([
            'nama_gol' => 'required|string|max:50',
            'program_studi' => 'required|string|max:100',
            'angkatan' => 'required|string|max:10',
            'dosen_wali' => 'nullable|exists:dosen,nip',
            'kapasitas' => 'required|integer|min:1|max:200',
        ]);

        $golongan->update($validated);

        return redirect()->route('golongan.index')->with('success', 'Golongan berhasil diperbarui.');
    }

    public function destroy(Golongan $golongan)
    {
        $golongan->delete();

        return redirect()->route('golongan.index')->with('success', 'Golongan berhasil dihapus.');
    }
}
