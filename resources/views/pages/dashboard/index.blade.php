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
                <h3>{{ $mahasiswaCount ?? 0 }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="text-muted">Dosen</div>
                <h3>{{ $dosenCount ?? 0 }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="text-muted">Mata Kuliah</div>
                <h3>{{ $mataKuliahCount ?? 0 }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="text-muted">Presensi Hari Ini</div>
                <h3>{{ $presensiHariIniCount ?? 0 }}</h3>
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
                    @if(empty($recentPresensi) || $recentPresensi->count() === 0)
                        <li class="mb-2">Belum ada aktivitas.</li>
                    @else
                        @foreach($recentPresensi as $item)
                            <li class="mb-2">
                                {{ optional($item->mahasiswa)->nama ?? 'Mahasiswa' }}
                                ({{ $item->nim }})
                                • {{ optional($item->mataKuliah)->nama_mk ?? $item->kode_mk }}
                                • {{ ucfirst($item->status_kehadiran) }}
                                • {{ \Illuminate\Support\Carbon::parse($item->tanggal)->format('d M Y') }}
                            </li>
                        @endforeach
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>

@if(!empty($dosenJadwal))
<div class="card mt-4">
    <div class="card-header">Kelas Yang Diajar</div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Mata Kuliah</th>
                        <th>Golongan</th>
                        <th>Semester</th>
                        <th>Tahun Akademik</th>
                        <th>Semester Akademik</th>
                        <th>Jadwal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dosenJadwal as $row)
                        <tr>
                            <td>{{ $row->mataKuliah->nama_mk ?? $row->kode_mk }}</td>
                            <td>{{ $row->golongan->nama_gol ?? $row->id_gol }}</td>
                            <td>{{ $row->semester_kelas ?? '-' }}</td>
                            <td>{{ $row->tahun_akademik }}</td>
                            <td>{{ $row->semester_akademik }}</td>
                            <td>{{ $row->hari }} {{ $row->jam_mulai }}-{{ $row->jam_selesai }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Belum ada jadwal.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
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
