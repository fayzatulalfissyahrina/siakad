@extends('layouts.app')

@section('title', 'Tambah Dosen')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Tambah Dosen</h4>
    <a href="{{ route('dosen.index') }}" class="btn btn-secondary">Kembali</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('dosen.store') }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">NIP <span class="text-danger">*</span></label>
                    <input class="form-control" name="nip" value="{{ old('nip') }}" required>
                    @error('nip') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nama <span class="text-danger">*</span></label>
                    <input class="form-control" name="nama" value="{{ old('nama') }}" required>
                    @error('nama') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email <span class="text-danger">*</span></label>
                    <input class="form-control" name="email" type="email" value="{{ old('email') }}" required>
                    @error('email') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Alamat</label>
                    <input class="form-control" name="alamat" value="{{ old('alamat') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">No HP</label>
                    <input class="form-control" name="no_hp" value="{{ old('no_hp') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <select class="form-select" name="status" required>
                        <option value="aktif" @selected(old('status', 'aktif') === 'aktif')>Aktif</option>
                        <option value="nonaktif" @selected(old('status') === 'nonaktif')>Nonaktif</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" placeholder="Kosongkan untuk default">
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('dosen.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
