<?php

namespace App\Http\Controllers;

use App\Models\Ruang;
use Illuminate\Http\Request;

class RuangController extends Controller
{
    public function index(Request $request)
    {
        $query = Ruang::query();

        if ($request->filled('q')) {
            $q = $request->string('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('id_ruang', 'like', "%{$q}%")
                    ->orWhere('nama_ruang', 'like', "%{$q}%")
                    ->orWhere('gedung', 'like', "%{$q}%");
            });
        }

        $data = [
            'ruang' => $query->orderBy('id_ruang')->paginate(10)->withQueryString(),
            'editing' => null,
        ];

        if ($request->filled('edit')) {
            $data['editing'] = Ruang::where('id_ruang', $request->edit)->firstOrFail();
        }

        return view('pages.ruang.index', $data);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_ruang' => 'required|string|max:20|unique:ruang,id_ruang',
            'nama_ruang' => 'required|string|max:100',
            'gedung' => 'required|string|max:50',
            'lantai' => 'required|string|max:10',
            'kapasitas' => 'required|integer|min:1|max:300',
            'fasilitas' => 'nullable|string',
            'status' => 'required|string|max:20',
        ]);

        Ruang::create($validated);

        return redirect()->route('ruang.index')->with('success', 'Ruang berhasil ditambahkan.');
    }

    public function update(Request $request, Ruang $ruang)
    {
        $validated = $request->validate([
            'nama_ruang' => 'required|string|max:100',
            'gedung' => 'required|string|max:50',
            'lantai' => 'required|string|max:10',
            'kapasitas' => 'required|integer|min:1|max:300',
            'fasilitas' => 'nullable|string',
            'status' => 'required|string|max:20',
        ]);

        $ruang->update($validated);

        return redirect()->route('ruang.index')->with('success', 'Ruang berhasil diperbarui.');
    }

    public function destroy(Ruang $ruang)
    {
        $ruang->delete();

        return redirect()->route('ruang.index')->with('success', 'Ruang berhasil dihapus.');
    }
}
