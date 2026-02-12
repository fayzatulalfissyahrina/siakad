@extends('layouts.app')

@section('title', 'Presensi')

@push('scripts')
<script>
    document.querySelector('.btn-success')?.addEventListener('click', () => {
        Swal.fire({
            title: 'Scan QR Presensi',
            text: 'Arahkan kamera ke QR code.',
            icon: 'info',
        });
    });
</script>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Presensi Akademik</h4>
    <button class="btn btn-success"><i class="fa-solid fa-qrcode me-2"></i>Scan QR</button>
</div>

<div class="card">
    <div class="card-body">
        <div class="row g-2 mb-3">
            <div class="col-md-4"><input class="form-control" placeholder="Cari mahasiswa..."></div>
            <div class="col-md-3"><input type="date" class="form-control"></div>
            <div class="col-md-3"><select class="form-select"><option>Semua Status</option></select></div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Mahasiswa</th>
                        <th>Mata Kuliah</th>
                        <th>Status</th>
                        <th>Jam</th>
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
