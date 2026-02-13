@extends('layouts.app')

@section('title', 'Edit Pengampu')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Edit Pengampu</h4>
    <a href="{{ route('pengampu.index') }}" class="btn btn-secondary">Kembali</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('pengampu.update', $pengampu->id) }}">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Mata Kuliah <span class="text-danger">*</span></label>
                    <select class="form-select" name="kode_mk" required>
                        @foreach($mataKuliahList as $mk)
                            <option value="{{ $mk->kode_mk }}" @selected($pengampu->kode_mk == $mk->kode_mk)>{{ $mk->kode_mk }} - {{ $mk->nama_mk }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Dosen <span class="text-danger">*</span></label>
                    <select class="form-select" name="nip" required>
                        @foreach($dosenList as $dsn)
                            <option value="{{ $dsn->nip }}" @selected($pengampu->nip == $dsn->nip)>{{ $dsn->nama }} ({{ $dsn->nip }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tahun Akademik <span class="text-danger">*</span></label>
                    <input class="form-control" name="tahun_akademik" value="{{ $pengampu->tahun_akademik }}" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Semester <span class="text-danger">*</span></label>
                    <select class="form-select" name="semester_akademik" required>
                        <option value="Ganjil" @selected($pengampu->semester_akademik == 'Ganjil')>Ganjil</option>
                        <option value="Genap" @selected($pengampu->semester_akademik == 'Genap')>Genap</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <select class="form-select" name="status" required>
                        <option value="aktif" @selected($pengampu->status == 'aktif')>Aktif</option>
                        <option value="nonaktif" @selected($pengampu->status == 'nonaktif')>Nonaktif</option>
                    </select>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="{{ route('pengampu.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
