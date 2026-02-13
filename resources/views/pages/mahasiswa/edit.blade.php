@extends('layouts.app')

@section('title', 'Edit Mahasiswa')

@section('content')
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Edit Mahasiswa</h4>
    <a href="{{ route('mahasiswa.index') }}" class="btn btn-secondary">Kembali</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('mahasiswa.update', $mahasiswa->nim) }}">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">NIM</label>
                    <input class="form-control" value="{{ $mahasiswa->nim }}" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nama <span class="text-danger">*</span></label>
                    <input class="form-control" name="nama" value="{{ old('nama', $mahasiswa->nama) }}" required>
                    @error('nama') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email <span class="text-danger">*</span></label>
                    <input class="form-control" name="email" type="email" value="{{ old('email', $mahasiswa->email) }}" required>
                    @error('email') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Alamat</label>
                    <input class="form-control" name="alamat" value="{{ old('alamat', $mahasiswa->alamat) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">No HP</label>
                    <input class="form-control" name="no_hp" value="{{ old('no_hp', $mahasiswa->no_hp) }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Semester <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="semester" value="{{ old('semester', $mahasiswa->semester) }}" min="1" max="14" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Golongan <span class="text-danger">*</span></label>
                    <select class="form-select" name="id_gol" required>
                        @foreach($golonganList as $gol)
                            <option value="{{ $gol->id_gol }}" @selected(old('id_gol', $mahasiswa->id_gol) == $gol->id_gol)>
                                {{ $gol->nama_gol }} ({{ $gol->program_studi }})
                            </option>
                        @endforeach
                    </select>
                    @error('id_gol') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <select class="form-select" name="status" required>
                        <option value="aktif" @selected(old('status', $mahasiswa->status) === 'aktif')>Aktif</option>
                        <option value="nonaktif" @selected(old('status', $mahasiswa->status) === 'nonaktif')>Nonaktif</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tahun Masuk <span class="text-danger">*</span></label>
                    <input class="form-control" name="tahun_masuk" value="{{ old('tahun_masuk', $mahasiswa->tahun_masuk) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" placeholder="Kosongkan jika tidak diubah">
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="{{ route('mahasiswa.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
