@extends('layouts.app')

@section('title', 'Dashboard')

@push('styles')
<style>
    .stat-card { border-left: 4px solid var(--accent); }
</style>
@endpush

@section('content')
<div class="row g-3">
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="text-muted">Mahasiswa</div>
                <h3>0</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="text-muted">Dosen</div>
                <h3>0</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="text-muted">Mata Kuliah</div>
                <h3>0</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="text-muted">Presensi Hari Ini</div>
                <h3>0</h3>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4 g-3">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">Dashboard Analytics</div>
            <div class="card-body">
                <div class="text-muted">Grafik akan ditampilkan di sini.</div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">Aktivitas Terbaru</div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">Belum ada aktivitas.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const toast = () => Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'info',
        title: 'Selamat datang di Dashboard',
        showConfirmButton: false,
        timer: 2000
    });
    setTimeout(toast, 500);
</script>
@endpush
