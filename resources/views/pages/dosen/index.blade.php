@extends('layouts.app')

@section('title', 'Dosen')

@section('content')
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Data Dosen</h4>
</div>

<div class="card mb-3">
    <div class="card-body">
        <h6 class="mb-3">{{ $editing ? 'Edit Dosen' : 'Tambah Dosen' }}</h6>
        <form method="POST" action="{{ $editing ? route('dosen.update', $editing->nip) : route('dosen.store') }}">
            @csrf
            @if($editing) @method('PUT') @endif
            <div class="row g-2">
                <div class="col-md-3">
                    <label class="form-label">NIP</label>
                    <input class="form-control" name="nip" value="{{ old('nip', $editing->nip ?? '') }}" {{ $editing ? 'readonly' : '' }}>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Nama</label>
                    <input class="form-control" name="nama" value="{{ old('nama', $editing->nama ?? '') }}">
                </div>
                <div class="col-md-5">
                    <label class="form-label">Email</label>
                    <input class="form-control" name="email" value="{{ old('email', $editing->email ?? '') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Alamat</label>
                    <input class="form-control" name="alamat" value="{{ old('alamat', $editing->alamat ?? '') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">No HP</label>
                    <input class="form-control" name="no_hp" value="{{ old('no_hp', $editing->no_hp ?? '') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select class="form-select" name="status">
                        @php($statusVal = old('status', $editing->status ?? 'aktif'))
                        <option value="aktif" @selected($statusVal === 'aktif')>Aktif</option>
                        <option value="nonaktif" @selected($statusVal === 'nonaktif')>Nonaktif</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Password (opsional)</label>
                    <input type="password" class="form-control" name="password">
                </div>
            </div>
            <div class="mt-3">
                <button class="btn btn-primary">{{ $editing ? 'Simpan Perubahan' : 'Tambah' }}</button>
                @if($editing)
                    <a href="{{ route('dosen.index') }}" class="btn btn-secondary">Batal</a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form class="row g-2 mb-3">
            <div class="col-md-4">
                <input class="form-control" name="q" value="{{ request('q') }}" placeholder="Cari dosen...">
            </div>
            <div class="col-md-3">
                <select class="form-select" name="status">
                    <option value="all">Semua Status</option>
                    <option value="aktif" @selected(request('status') === 'aktif')>Aktif</option>
                    <option value="nonaktif" @selected(request('status') === 'nonaktif')>Nonaktif</option>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-outline-secondary">Filter</button>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>NIP</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dosen as $item)
                        <tr>
                            <td>{{ $item->nip }}</td>
                            <td>{{ $item->nama }}</td>
                            <td>{{ $item->email }}</td>
                            <td>{{ ucfirst($item->status) }}</td>
                            <td class="d-flex gap-2">
                                <a class="btn btn-sm btn-outline-primary" href="{{ route('dosen.index', ['edit' => $item->nip]) }}">Edit</a>
                                <form method="POST" action="{{ route('dosen.destroy', $item->nip) }}" onsubmit="return confirm('Hapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Belum ada data.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $dosen->links() }}
    </div>
</div>
@endsection
