@extends('layouts.app')

@section('title', 'Edit Dosen')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Edit Dosen</h4>
    <a href="{{ route('dosen.index') }}" class="btn btn-secondary">Kembali</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('dosen.update', $dosen->nip) }}">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">NIP</label>
                    <input class="form-control" value="{{ $dosen->nip }}" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nama <span class="text-danger">*</span></label>
                    <input class="form-control" name="nama" value="{{ old('nama', $dosen->nama) }}" required>
                    @error('nama') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email <span class="text-danger">*</span></label>
                    <input class="form-control" name="email" type="email" value="{{ old('email', $dosen->email) }}" required>
                    @error('email') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Alamat</label>
                    <input class="form-control" name="alamat" value="{{ old('alamat', $dosen->alamat) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">No HP</label>
                    <input class="form-control" name="no_hp" value="{{ old('no_hp', $dosen->no_hp) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <select class="form-select" name="status" required>
                        <option value="aktif" @selected(old('status', $dosen->status) === 'aktif')>Aktif</option>
                        <option value="nonaktif" @selected(old('status', $dosen->status) === 'nonaktif')>Nonaktif</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" placeholder="Kosongkan jika tidak diubah">
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="{{ route('dosen.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
