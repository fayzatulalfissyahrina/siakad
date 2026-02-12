@extends('layouts.app')

@section('title', 'Jadwal Akademik')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Jadwal Akademik</h4>
    <button class="btn btn-primary"><i class="fa-solid fa-plus me-2"></i>Tambah</button>
</div>

<div class="card">
    <div class="card-body">
        <div class="row g-2 mb-3">
            <div class="col-md-3"><input class="form-control" placeholder="Cari jadwal..."></div>
            <div class="col-md-3"><input type="date" class="form-control"></div>
            <div class="col-md-3"><button class="btn btn-outline-secondary">Filter</button></div>
        </div>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Hari</th>
                        <th>Mata Kuliah</th>
                        <th>Ruang</th>
                        <th>Golongan</th>
                        <th>Jam</th>
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
</div>
@endsection
