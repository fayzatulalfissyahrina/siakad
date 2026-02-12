@extends('layouts.app')

@section('title', 'Mahasiswa')

@push('styles')
<style>
    .table-wrap { background: #fff; border-radius: 8px; }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Data Mahasiswa</h4>
    <button class="btn btn-primary"><i class="fa-solid fa-plus me-2"></i>Tambah</button>
</div>

<div class="table-wrap p-3">
    <div class="row g-2 mb-3">
        <div class="col-md-4">
            <input class="form-control" placeholder="Cari mahasiswa...">
        </div>
        <div class="col-md-3">
            <select class="form-select">
                <option>Semua Status</option>
            </select>
        </div>
    </div>

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
                <tr>
                    <td colspan="5" class="text-center text-muted">Belum ada data.</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
