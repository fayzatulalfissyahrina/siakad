@extends('layouts.app')

@section('title', 'Edit Mata Kuliah')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Edit Mata Kuliah</h4>
    <a href="{{ route('mata-kuliah.index') }}" class="btn btn-secondary">Kembali</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('mata-kuliah.update', $mataKuliah->kode_mk) }}">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Kode</label>
                    <input class="form-control" value="{{ $mataKuliah->kode_mk }}" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nama <span class="text-danger">*</span></label>
                    <input class="form-control" name="nama_mk" value="{{ old('nama_mk', $mataKuliah->nama_mk) }}" required>
                    @error('nama_mk') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-2">
                    <label class="form-label">SKS <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="sks" value="{{ old('sks', $mataKuliah->sks) }}" min="1" max="6" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Semester <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="semester" value="{{ old('semester', $mataKuliah->semester) }}" min="1" max="14" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Jenis <span class="text-danger">*</span></label>
                    <select class="form-select" name="jenis" required>
                        <option value="Wajib" @selected(old('jenis', $mataKuliah->jenis) === 'Wajib')>Wajib</option>
                        <option value="Pilihan" @selected(old('jenis', $mataKuliah->jenis) === 'Pilihan')>Pilihan</option>
                    </select>
                </div>
                <div class="col-md-12">
                    <label class="form-label">Deskripsi</label>
                    <textarea class="form-control" name="deskripsi" rows="3">{{ old('deskripsi', $mataKuliah->deskripsi) }}</textarea>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="{{ route('mata-kuliah.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
