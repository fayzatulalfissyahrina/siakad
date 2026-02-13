<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DosenController extends Controller
{
    public function index(Request $request)
    {
        $query = Dosen::query();

        if ($request->filled('q')) {
            $q = $request->string('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('nip', 'like', "%{$q}%")
                    ->orWhere('nama', 'like', "%{$q}%");
            });
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $data = [
            'dosen' => $query->orderBy('nip')->paginate(10)->withQueryString(),
            'editing' => null,
        ];

        if ($request->filled('edit')) {
            $data['editing'] = Dosen::where('nip', $request->edit)->firstOrFail();
        }

        return view('pages.dosen.index', $data);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nip' => 'required|string|max:20|unique:dosen,nip|unique:users,username',
            'nama' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'no_hp' => 'nullable|string|max:20',
            'email' => 'required|email|max:255|unique:dosen,email|unique:users,email',
            'status' => 'required|string',
            'password' => 'nullable|string|min:6',
        ]);

        Dosen::create([
            'nip' => $validated['nip'],
            'nama' => $validated['nama'],
            'alamat' => $validated['alamat'] ?? null,
            'no_hp' => $validated['no_hp'] ?? null,
            'email' => $validated['email'],
            'foto' => null,
            'status' => $validated['status'],
        ]);

        User::create([
            'username' => $validated['nip'],
            'name' => $validated['nama'],
            'email' => $validated['email'],
            'role' => 'dosen',
            'nim' => null,
            'nip' => $validated['nip'],
            'password' => Hash::make($validated['password'] ?? 'dosen123'),
        ]);

        return redirect()->route('dosen.index')->with('success', 'Dosen berhasil ditambahkan.');
    }

    public function update(Request $request, Dosen $dosen)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'no_hp' => 'nullable|string|max:20',
            'email' => 'required|email|max:255|unique:dosen,email,' . $dosen->nip . ',nip|unique:users,email,' . $dosen->nip . ',username',
            'status' => 'required|string',
            'password' => 'nullable|string|min:6',
        ]);

        $dosen->update([
            'nama' => $validated['nama'],
            'alamat' => $validated['alamat'] ?? null,
            'no_hp' => $validated['no_hp'] ?? null,
            'email' => $validated['email'],
            'status' => $validated['status'],
        ]);

        $user = User::where('username', $dosen->nip)->first();
        if ($user) {
            $user->name = $validated['nama'];
            $user->email = $validated['email'];
            if (!empty($validated['password'])) {
                $user->password = Hash::make($validated['password']);
            }
            $user->save();
        }

        return redirect()->route('dosen.index')->with('success', 'Dosen berhasil diperbarui.');
    }

    public function destroy(Dosen $dosen)
    {
        User::where('username', $dosen->nip)->delete();
        $dosen->delete();

        return redirect()->route('dosen.index')->with('success', 'Dosen berhasil dihapus.');
    }
}
