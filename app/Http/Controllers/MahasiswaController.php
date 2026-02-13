<?php

namespace App\Http\Controllers;

use App\Models\Golongan;
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MahasiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = Mahasiswa::query()->with('golongan');

        if ($request->filled('q')) {
            $q = $request->string('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('nim', 'like', "%{$q}%")
                    ->orWhere('nama', 'like', "%{$q}%");
            });
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $data = [
            'mahasiswa' => $query->orderBy('nim')->paginate(10)->withQueryString(),
            'golonganList' => Golongan::orderBy('nama_gol')->get(),
        ];

        return view('pages.mahasiswa.index', $data);
    }

    public function create()
    {
        return view('pages.mahasiswa.create', [
            'golonganList' => Golongan::orderBy('nama_gol')->get(),
        ]);
    }

    public function edit(Mahasiswa $mahasiswa)
    {
        return view('pages.mahasiswa.edit', [
            'mahasiswa' => $mahasiswa,
            'golonganList' => Golongan::orderBy('nama_gol')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nim' => 'required|string|max:20|unique:mahasiswa,nim|unique:users,username',
            'nama' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'no_hp' => 'nullable|string|max:20',
            'email' => 'required|email|max:255|unique:mahasiswa,email|unique:users,email',
            'semester' => 'required|integer|min:1|max:14',
            'id_gol' => 'required|exists:golongan,id_gol',
            'status' => 'required|string',
            'tahun_masuk' => 'required|string|max:10',
            'password' => 'nullable|string|min:6',
        ]);

        Mahasiswa::create([
            'nim' => $validated['nim'],
            'nama' => $validated['nama'],
            'alamat' => $validated['alamat'] ?? null,
            'no_hp' => $validated['no_hp'] ?? null,
            'email' => $validated['email'],
            'semester' => $validated['semester'],
            'id_gol' => $validated['id_gol'],
            'foto' => null,
            'status' => $validated['status'],
            'tahun_masuk' => $validated['tahun_masuk'],
        ]);

        User::create([
            'username' => $validated['nim'],
            'name' => $validated['nama'],
            'email' => $validated['email'],
            'role' => 'mahasiswa',
            'nim' => $validated['nim'],
            'nip' => null,
            'password' => Hash::make($validated['password'] ?? 'mhs123'),
        ]);

        return redirect()->route('mahasiswa.index')->with('success', 'Mahasiswa berhasil ditambahkan.');
    }

    public function update(Request $request, Mahasiswa $mahasiswa)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'no_hp' => 'nullable|string|max:20',
            'email' => 'required|email|max:255|unique:mahasiswa,email,' . $mahasiswa->nim . ',nim|unique:users,email,' . $mahasiswa->nim . ',username',
            'semester' => 'required|integer|min:1|max:14',
            'id_gol' => 'required|exists:golongan,id_gol',
            'status' => 'required|string',
            'tahun_masuk' => 'required|string|max:10',
            'password' => 'nullable|string|min:6',
        ]);

        $mahasiswa->update([
            'nama' => $validated['nama'],
            'alamat' => $validated['alamat'] ?? null,
            'no_hp' => $validated['no_hp'] ?? null,
            'email' => $validated['email'],
            'semester' => $validated['semester'],
            'id_gol' => $validated['id_gol'],
            'status' => $validated['status'],
            'tahun_masuk' => $validated['tahun_masuk'],
        ]);

        $user = User::where('username', $mahasiswa->nim)->first();
        if ($user) {
            $user->name = $validated['nama'];
            $user->email = $validated['email'];
            if (!empty($validated['password'])) {
                $user->password = Hash::make($validated['password']);
            }
            $user->save();
        }

        return redirect()->route('mahasiswa.index')->with('success', 'Mahasiswa berhasil diperbarui.');
    }

    public function destroy(Mahasiswa $mahasiswa)
    {
        User::where('username', $mahasiswa->nim)->delete();
        $mahasiswa->delete();

        return redirect()->route('mahasiswa.index')->with('success', 'Mahasiswa berhasil dihapus.');
    }
}
