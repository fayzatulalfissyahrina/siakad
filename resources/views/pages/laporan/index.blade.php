@extends('layouts.app')

@section('title', 'Laporan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Laporan Akademik</h4>
    <div class="btn-group">
        <a class="btn btn-outline-primary" href="{{ route('laporan.export', 'csv') }}">CSV</a>
        <a class="btn btn-outline-success" href="{{ route('laporan.export', 'xlsx') }}">Excel</a>
        <a class="btn btn-outline-danger" href="{{ route('laporan.export', 'pdf') }}">PDF</a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="row g-2 mb-3">
            <div class="col-md-3"><input type="date" class="form-control"></div>
            <div class="col-md-3"><input type="date" class="form-control"></div>
            <div class="col-md-3"><button class="btn btn-outline-secondary">Filter</button></div>
        </div>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Jenis</th>
                        <th>Periode</th>
                        <th>Deskripsi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="3" class="text-center text-muted">Belum ada data.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
