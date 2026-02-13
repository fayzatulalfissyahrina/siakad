<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
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
            'editing' => null,
        ];

        if ($request->filled('edit')) {
            $data['editing'] = Pengampu::with(['dosen', 'mataKuliah'])->findOrFail($request->edit);
        }

        return view('pages.pengampu.index', $data);
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
}
