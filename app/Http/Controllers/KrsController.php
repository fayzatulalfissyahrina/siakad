<?php

namespace App\Http\Controllers;

use App\Models\Krs;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use Illuminate\Http\Request;

class KrsController extends Controller
{
    public function index(Request $request)
    {
        $role = auth()->user()->role ?? 'guest';

        $query = Krs::query()->with(['mahasiswa', 'mataKuliah']);

        $data = [
            'role' => $role,
            'editing' => null,
        ];

        if ($role === 'mahasiswa') {
            $query->where('nim', auth()->user()->nim);
            $data['krs'] = $query->orderByDesc('id')->paginate(10)->withQueryString();
        } else {
            if ($request->filled('q')) {
                $q = $request->string('q');
                $query->whereHas('mahasiswa', function ($sub) use ($q) {
                    $sub->where('nim', 'like', "%{$q}%")
                        ->orWhere('nama', 'like', "%{$q}%");
                });
            }

            $data['krs'] = $query->orderByDesc('id')->paginate(10)->withQueryString();
            $data['mahasiswaList'] = Mahasiswa::orderBy('nim')->get();
            $data['mataKuliahList'] = MataKuliah::orderBy('kode_mk')->get();

            if ($request->filled('edit')) {
                $data['editing'] = Krs::with(['mahasiswa', 'mataKuliah'])->findOrFail($request->edit);
            }
        }

        return view('pages.krs.index', $data);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nim' => 'required|exists:mahasiswa,nim',
            'kode_mk' => 'required|exists:mata_kuliah,kode_mk',
            'tahun_akademik' => 'required|string|max:20',
            'semester_akademik' => 'required|string|max:20',
            'status_krs' => 'required|string|max:20',
            'nilai_akhir' => 'nullable|string|max:5',
            'nilai_angka' => 'nullable|numeric|min:0|max:100',
            'status_lulus' => 'required|string|max:20',
        ]);

        Krs::create($validated);

        return redirect()->route('krs.index')->with('success', 'KRS berhasil ditambahkan.');
    }

    public function update(Request $request, Krs $kr)
    {
        $validated = $request->validate([
            'nim' => 'required|exists:mahasiswa,nim',
            'kode_mk' => 'required|exists:mata_kuliah,kode_mk',
            'tahun_akademik' => 'required|string|max:20',
            'semester_akademik' => 'required|string|max:20',
            'status_krs' => 'required|string|max:20',
            'nilai_akhir' => 'nullable|string|max:5',
            'nilai_angka' => 'nullable|numeric|min:0|max:100',
            'status_lulus' => 'required|string|max:20',
        ]);

        $kr->update($validated);

        return redirect()->route('krs.index')->with('success', 'KRS berhasil diperbarui.');
    }

    public function destroy(Krs $kr)
    {
        $kr->delete();

        return redirect()->route('krs.index')->with('success', 'KRS berhasil dihapus.');
    }
}
