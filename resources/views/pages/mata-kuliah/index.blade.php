@extends('layouts.app')

@section('title', 'Mata Kuliah')

@section('content')
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Mata Kuliah</h4>
</div>

<div class="card mb-3">
    <div class="card-body">
        <h6 class="mb-3">{{ $editing ? 'Edit Mata Kuliah' : 'Tambah Mata Kuliah' }}</h6>
        <form method="POST" action="{{ $editing ? route('mata-kuliah.update', $editing->kode_mk) : route('mata-kuliah.store') }}">
            @csrf
            @if($editing) @method('PUT') @endif
            <div class="row g-2">
                <div class="col-md-3">
                    <label class="form-label">Kode</label>
                    <input class="form-control" name="kode_mk" value="{{ old('kode_mk', $editing->kode_mk ?? '') }}" {{ $editing ? 'readonly' : '' }}>
                </div>
                <div class="col-md-5">
                    <label class="form-label">Nama</label>
                    <input class="form-control" name="nama_mk" value="{{ old('nama_mk', $editing->nama_mk ?? '') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">SKS</label>
                    <input type="number" class="form-control" name="sks" value="{{ old('sks', $editing->sks ?? 3) }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Semester</label>
                    <input type="number" class="form-control" name="semester" value="{{ old('semester', $editing->semester ?? 1) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Jenis</label>
                    <select class="form-select" name="jenis">
                        @php($jenisVal = old('jenis', $editing->jenis ?? 'Wajib'))
                        <option value="Wajib" @selected($jenisVal === 'Wajib')>Wajib</option>
                        <option value="Pilihan" @selected($jenisVal === 'Pilihan')>Pilihan</option>
                    </select>
                </div>
                <div class="col-md-9">
                    <label class="form-label">Deskripsi</label>
                    <input class="form-control" name="deskripsi" value="{{ old('deskripsi', $editing->deskripsi ?? '') }}">
                </div>
            </div>
            <div class="mt-3">
                <button class="btn btn-primary">{{ $editing ? 'Simpan Perubahan' : 'Tambah' }}</button>
                @if($editing)
                    <a href="{{ route('mata-kuliah.index') }}" class="btn btn-secondary">Batal</a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form class="row g-2 mb-3">
            <div class="col-md-4">
                <input class="form-control" name="q" value="{{ request('q') }}" placeholder="Cari mata kuliah...">
            </div>
            <div class="col-md-2">
                <button class="btn btn-outline-secondary">Filter</button>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>SKS</th>
                        <th>Semester</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mataKuliah as $item)
                        <tr>
                            <td>{{ $item->kode_mk }}</td>
                            <td>{{ $item->nama_mk }}</td>
                            <td>{{ $item->sks }}</td>
                            <td>{{ $item->semester }}</td>
                            <td class="d-flex gap-2">
                                <a class="btn btn-sm btn-outline-primary" href="{{ route('mata-kuliah.index', ['edit' => $item->kode_mk]) }}">Edit</a>
                                <form method="POST" action="{{ route('mata-kuliah.destroy', $item->kode_mk) }}" onsubmit="return confirm('Hapus data ini?')">
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

        {{ $mataKuliah->links() }}
    </div>
</div>
@endsection
