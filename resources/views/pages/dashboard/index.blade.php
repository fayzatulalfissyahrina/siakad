@extends('layouts.app')

@section('title', 'Dashboard')

@push('styles')
<style>
    .stat-card { border-left: 4px solid var(--accent); }
</style>
@endpush

@section('content')
@if($role === 'admin')
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
                    <div class="text-muted">Golongan</div>
                    <h3>{{ $golonganCount ?? 0 }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4 g-3">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">Aktivitas Presensi Terbaru</div>
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
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">Presensi Hari Ini</div>
                <div class="card-body text-center">
                    <h2 class="mb-0">{{ $presensiHariIniCount ?? 0 }}</h2>
                    <p class="text-muted mb-0">Record</p>
                </div>
            </div>
        </div>
    </div>

@elseif($role === 'dosen')
    <div class="row g-3">
        <div class="col-md-4">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="text-muted">Mata Kuliah Diajar</div>
                    <h3>{{ $pengampuCount ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="text-muted">Jadwal Mengajar</div>
                    <h3>{{ $dosenJadwal->count() ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="text-muted">Presensi Hari Ini</div>
                    <h3>{{ $presensiHariIni ?? 0 }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">Jadwal Mengajar</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Hari</th>
                            <th>Mata Kuliah</th>
                            <th>Golongan</th>
                            <th>Semester</th>
                            <th>Jam</th>
                            <th>Ruang</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dosenJadwal as $row)
                            <tr>
                                <td>{{ $row->hari }}</td>
                                <td>{{ $row->mataKuliah->nama_mk ?? $row->kode_mk }}</td>
                                <td>{{ $row->golongan->nama_gol ?? $row->id_gol }}</td>
                                <td>{{ $row->semester_kelas ?? '-' }}</td>
                                <td>{{ $row->jam_mulai }}-{{ $row->jam_selesai }}</td>
                                <td>{{ $row->ruang->nama_ruang ?? '-' }}</td>
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

    <div class="row mt-4 g-3">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">Presensi Terbaru</div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        @if(empty($recentPresensi) || $recentPresensi->count() === 0)
                            <li class="mb-2">Belum ada presensi.</li>
                        @else
                            @foreach($recentPresensi as $item)
                                <li class="mb-2">
                                    {{ optional($item->mahasiswa)->nama ?? 'Mahasiswa' }}
                                    • {{ optional($item->mataKuliah)->nama_mk ?? $item->kode_mk }}
                                    • {{ ucfirst($item->status_kehadiran) }}
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">Menu Cepat</div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('dosen.jadwal') }}" class="btn btn-outline-primary">Jadwal Mengajar</a>
                        <a href="{{ route('dosen.presensi') }}" class="btn btn-outline-success">Presensi Kelas</a>
                        <a href="{{ route('dosen.nilai') }}" class="btn btn-outline-warning">Input Nilai</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@elseif($role === 'mahasiswa')
    <div class="row g-3">
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="text-muted">Prodi</div>
                    <h6>{{ $prodi ?? '-' }}</h6>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="text-muted">Jurusan/Kelas</div>
                    <h6>{{ $jurusan ?? '-' }}</h6>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="text-muted">Semester</div>
                    <h3>{{ $semester ?? 1 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="text-muted">Status Presensi</div>
                    <h6>{{ $presensiHadir ?? 0 }} / {{ $totalPresensi ?? 0 }}</h6>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">Jadwal Kuliah</div>
        <div class="card-body">
            @if($jadwalKuliah->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Hari</th>
                                <th>Mata Kuliah</th>
                                <th>Jam</th>
                                <th>Ruang</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($jadwalKuliah as $row)
                                <tr>
                                    <td>{{ $row->hari }}</td>
                                    <td>{{ $row->mataKuliah->nama_mk ?? $row->kode_mk }}</td>
                                    <td>{{ $row->jam_mulai }}-{{ $row->jam_selesai }}</td>
                                    <td>{{ $row->ruang->nama_ruang ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted mb-0">Belum ada jadwal untuk semester ini.</p>
            @endif
        </div>
    </div>

    <div class="row mt-4 g-3">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">Riwayat Presensi</div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        @if(empty($recentPresensi) || $recentPresensi->count() === 0)
                            <li class="mb-2">Belum ada presensi.</li>
                        @else
                            @foreach($recentPresensi as $item)
                                <li class="mb-2">
                                    {{ optional($item->mataKuliah)->nama_mk ?? $item->kode_mk }}
                                    • {{ ucfirst($item->status_kehadiran) }}
                                    • {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">Menu Cepat</div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('mahasiswa.jadwal') }}" class="btn btn-outline-primary">Jadwal Kuliah</a>
                        <a href="{{ route('mahasiswa.presensi') }}" class="btn btn-outline-success">Presensi</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

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
@endsection
