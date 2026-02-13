@extends('layouts.app')

@section('title', 'Edit Ruang')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Edit Ruang</h4>
    <a href="{{ route('ruang.index') }}" class="btn btn-secondary">Kembali</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('ruang.update', $ruang->id_ruang) }}">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">ID Ruang</label>
                    <input class="form-control" value="{{ $ruang->id_ruang }}" readonly>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Nama Ruang <span class="text-danger">*</span></label>
                    <input class="form-control" name="nama_ruang" value="{{ $ruang->nama_ruang }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Gedung <span class="text-danger">*</span></label>
                    <input class="form-control" name="gedung" value="{{ $ruang->gedung }}" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Lantai</label>
                    <input class="form-control" name="lantai" value="{{ $ruang->lantai }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Kapasitas</label>
                    <input type="number" class="form-control" name="kapasitas" value="{{ $ruang->kapasitas }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Fasilitas</label>
                    <input class="form-control" name="fasilitas" value="{{ $ruang->fasilitas }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <select class="form-select" name="status" required>
                        <option value="aktif" @selected($ruang->status == 'aktif')>Aktif</option>
                        <option value="nonaktif" @selected($ruang->status == 'nonaktif')>Nonaktif</option>
                    </select>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="{{ route('ruang.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
