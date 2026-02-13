@extends('layouts.app')

@section('title', 'Mahasiswa')

@push('styles')
<style>
    .table-wrap { background: #fff; border-radius: 8px; }
</style>
@endpush

@section('content')
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Data Mahasiswa</h4>
</div>

<div class="card mb-3">
    <div class="card-body">
        <h6 class="mb-3">{{ $editing ? 'Edit Mahasiswa' : 'Tambah Mahasiswa' }}</h6>
        <form method="POST" action="{{ $editing ? route('mahasiswa.update', $editing->nim) : route('mahasiswa.store') }}">
            @csrf
            @if($editing) @method('PUT') @endif
            <div class="row g-2">
                <div class="col-md-3">
                    <label class="form-label">NIM</label>
                    <input class="form-control" name="nim" value="{{ old('nim', $editing->nim ?? '') }}" {{ $editing ? 'readonly' : '' }}>
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
                    <label class="form-label">Semester</label>
                    <input type="number" class="form-control" name="semester" value="{{ old('semester', $editing->semester ?? 1) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Golongan</label>
                    <select class="form-select" name="id_gol">
                        @foreach($golonganList as $gol)
                            <option value="{{ $gol->id_gol }}" @selected(old('id_gol', $editing->id_gol ?? '') == $gol->id_gol)>
                                {{ $gol->nama_gol }} ({{ $gol->program_studi }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select class="form-select" name="status">
                        @php($statusVal = old('status', $editing->status ?? 'aktif'))
                        <option value="aktif" @selected($statusVal === 'aktif')>Aktif</option>
                        <option value="nonaktif" @selected($statusVal === 'nonaktif')>Nonaktif</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Tahun Masuk</label>
                    <input class="form-control" name="tahun_masuk" value="{{ old('tahun_masuk', $editing->tahun_masuk ?? date('Y')) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Password (opsional)</label>
                    <input type="password" class="form-control" name="password">
                </div>
            </div>
            <div class="mt-3">
                <button class="btn btn-primary">{{ $editing ? 'Simpan Perubahan' : 'Tambah' }}</button>
                @if($editing)
                    <a href="{{ route('mahasiswa.index') }}" class="btn btn-secondary">Batal</a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="table-wrap p-3">
    <form class="row g-2 mb-3">
        <div class="col-md-4">
            <input class="form-control" name="q" value="{{ request('q') }}" placeholder="Cari mahasiswa...">
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
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th>Semester</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mahasiswa as $item)
                    <tr>
                        <td>{{ $item->nim }}</td>
                        <td>{{ $item->nama }}</td>
                        <td>{{ $item->semester }}</td>
                        <td>{{ ucfirst($item->status) }}</td>
                        <td class="d-flex gap-2">
                            <a class="btn btn-sm btn-outline-primary" href="{{ route('mahasiswa.index', ['edit' => $item->nim]) }}">Edit</a>
                            <form method="POST" action="{{ route('mahasiswa.destroy', $item->nim) }}" onsubmit="return confirm('Hapus data ini?')">
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

    {{ $mahasiswa->links() }}
</div>
@endsection
