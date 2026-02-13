@extends('layouts.app')

@section('title', 'Tambah Mata Kuliah')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Tambah Mata Kuliah</h4>
    <a href="{{ route('mata-kuliah.index') }}" class="btn btn-secondary">Kembali</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('mata-kuliah.store') }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Kode <span class="text-danger">*</span></label>
                    <input class="form-control" name="kode_mk" value="{{ old('kode_mk') }}" required>
                    @error('kode_mk') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nama <span class="text-danger">*</span></label>
                    <input class="form-control" name="nama_mk" value="{{ old('nama_mk') }}" required>
                    @error('nama_mk') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-2">
                    <label class="form-label">SKS <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="sks" value="{{ old('sks', 3) }}" min="1" max="6" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Semester <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="semester" value="{{ old('semester', 1) }}" min="1" max="14" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Jenis <span class="text-danger">*</span></label>
                    <select class="form-select" name="jenis" required>
                        <option value="Wajib" @selected(old('jenis', 'Wajib') === 'Wajib')>Wajib</option>
                        <option value="Pilihan" @selected(old('jenis') === 'Pilihan')>Pilihan</option>
                    </select>
                </div>
                <div class="col-md-12">
                    <label class="form-label">Deskripsi</label>
                    <textarea class="form-control" name="deskripsi" rows="3">{{ old('deskripsi') }}</textarea>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('mata-kuliah.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
