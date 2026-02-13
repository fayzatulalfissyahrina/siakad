@extends('layouts.app')

@section('title', 'Tambah Ruang')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Tambah Ruang</h4>
    <a href="{{ route('ruang.index') }}" class="btn btn-secondary">Kembali</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('ruang.store') }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">ID Ruang <span class="text-danger">*</span></label>
                    <input class="form-control" name="id_ruang" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Nama Ruang <span class="text-danger">*</span></label>
                    <input class="form-control" name="nama_ruang" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Gedung <span class="text-danger">*</span></label>
                    <input class="form-control" name="gedung" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Lantai</label>
                    <input class="form-control" name="lantai">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Kapasitas</label>
                    <input type="number" class="form-control" name="kapasitas" value="30">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Fasilitas</label>
                    <input class="form-control" name="fasilitas">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <select class="form-select" name="status" required>
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('ruang.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
