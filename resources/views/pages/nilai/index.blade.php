@extends('layouts.app')

@section('title', 'Input Nilai')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Input Nilai</h4>
    <button class="btn btn-primary"><i class="fa-solid fa-floppy-disk me-2"></i>Simpan</button>
</div>

<div class="card">
    <div class="card-body">
        <div class="row g-2 mb-3">
            <div class="col-md-4"><input class="form-control" placeholder="Cari mahasiswa..."></div>
            <div class="col-md-3"><select class="form-select"><option>Pilih Mata Kuliah</option></select></div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Mahasiswa</th>
                        <th>Nilai Angka</th>
                        <th>Nilai Akhir</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="4" class="text-center text-muted">Belum ada data.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
