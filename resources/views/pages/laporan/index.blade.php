@extends('layouts.app')

@section('title', 'Laporan Presensi')

@section('content')
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Laporan Presensi</h4>
    <div class="btn-group">
        <a class="btn btn-outline-primary" href="{{ route('laporan.export', 'csv') }}">CSV</a>
        <a class="btn btn-outline-success" href="{{ route('laporan.export', 'xlsx') }}">Excel</a>
        <a class="btn btn-outline-danger" href="{{ route('laporan.export', 'pdf') }}">PDF</a>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-2">
        <div class="card text-center">
            <div class="card-body py-2">
                <div class="text-muted small">Total</div>
                <h5 class="mb-0">{{ $stats['total'] }}</h5>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center border-success">
            <div class="card-body py-2">
                <div class="text-muted small">Hadir</div>
                <h5 class="mb-0 text-success">{{ $stats['hadir'] }}</h5>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center border-warning">
            <div class="card-body py-2">
                <div class="text-muted small">Izin</div>
                <h5 class="mb-0 text-warning">{{ $stats['izin'] }}</h5>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center border-info">
            <div class="card-body py-2">
                <div class="text-muted small">Sakit</div>
                <h5 class="mb-0 text-info">{{ $stats['sakit'] }}</h5>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center border-danger">
            <div class="card-body py-2">
                <div class="text-muted small">Alpha</div>
                <h5 class="mb-0 text-danger">{{ $stats['alpha'] }}</h5>
            </div>
        </div>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2">
            <div class="col-md-3">
                <select class="form-select" name="golongan">
                    <option value="">Semua Golongan</option>
                    @foreach($golonganList as $gol)
                        <option value="{{ $gol->id_gol }}" @selected($selectedGol == $gol->id_gol)>
                            {{ $gol->nama_gol }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select" name="semester">
                    <option value="">Semua Semester</option>
                    @for($i = 1; $i <= 14; $i++)
                        <option value="{{ $i }}" @selected($selectedSemester == $i)>Semester {{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" class="form-control" name="tanggal_awal" value="{{ request('tanggal_awal') }}" placeholder="Tgl Awal">
            </div>
            <div class="col-md-2">
                <input type="date" class="form-control" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}" placeholder="Tgl Akhir">
            </div>
            <div class="col-md-2">
                <button class="btn btn-outline-secondary w-100">Filter</button>
            </div>
            <div class="col-md-1">
                <a href="{{ route('laporan.index') }}" class="btn btn-outline-danger w-100">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>Mata Kuliah</th>
                        <th>Pertemuan</th>
                        <th>Status</th>
                        <th>Jam</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($presensi as $item)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                            <td>{{ $item->nim }}</td>
                            <td>{{ $item->mahasiswa->nama ?? '-' }}</td>
                            <td>{{ $item->mataKuliah->nama_mk ?? $item->kode_mk }}</td>
                            <td>{{ $item->pertemuan_ke }}</td>
                            <td>
                                @if($item->status_kehadiran === 'hadir')
                                    <span class="badge bg-success">Hadir</span>
                                @elseif($item->status_kehadiran === 'izin')
                                    <span class="badge bg-warning">Izin</span>
                                @elseif($item->status_kehadiran === 'sakit')
                                    <span class="badge bg-info">Sakit</span>
                                @else
                                    <span class="badge bg-danger">Alpha</span>
                                @endif
                            </td>
                            <td>{{ $item->jam_masuk ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Belum ada data.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $presensi->links() }}
    </div>
</div>
@endsection
