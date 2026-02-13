@extends('layouts.app')

@section('title', 'Jadwal Akademik')

@section('content')
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Jadwal Akademik</h4>
</div>

@if(($role ?? 'admin') === 'admin')
<div class="card mb-3">
    <div class="card-body">
        <h6 class="mb-3">{{ $editing ? 'Edit Jadwal' : 'Tambah Jadwal' }}</h6>
        <form method="POST" action="{{ $editing ? route('jadwal.update', $editing->id) : route('jadwal.store') }}">
            @csrf
            @if($editing) @method('PUT') @endif
            <div class="row g-2">
                <div class="col-md-2">
                    <label class="form-label">Hari</label>
                    <select class="form-select" name="hari">
                        @php($hariVal = old('hari', $editing->hari ?? 'Senin'))
                        @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $hari)
                            <option value="{{ $hari }}" @selected($hariVal === $hari)>{{ $hari }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Mata Kuliah</label>
                    <select class="form-select" name="kode_mk">
                        @foreach($mataKuliahList as $mk)
                            <option value="{{ $mk->kode_mk }}" @selected(old('kode_mk', $editing->kode_mk ?? '') == $mk->kode_mk)>
                                {{ $mk->kode_mk }} - {{ $mk->nama_mk }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Ruang</label>
                    <select class="form-select" name="id_ruang">
                        @foreach($ruangList as $ruang)
                            <option value="{{ $ruang->id_ruang }}" @selected(old('id_ruang', $editing->id_ruang ?? '') == $ruang->id_ruang)>
                                {{ $ruang->nama_ruang }} ({{ $ruang->id_ruang }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Golongan</label>
                    <select class="form-select" name="id_gol">
                        @foreach($golonganList as $gol)
                            <option value="{{ $gol->id_gol }}" @selected(old('id_gol', $editing->id_gol ?? '') == $gol->id_gol)>
                                {{ $gol->nama_gol }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Jam Mulai</label>
                    <input type="time" class="form-control" name="jam_mulai" value="{{ old('jam_mulai', $editing->jam_mulai ?? '08:00') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Jam Selesai</label>
                    <input type="time" class="form-control" name="jam_selesai" value="{{ old('jam_selesai', $editing->jam_selesai ?? '10:00') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tahun Akademik</label>
                    <input class="form-control" name="tahun_akademik" value="{{ old('tahun_akademik', $editing->tahun_akademik ?? '2025/2026') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Semester Akademik</label>
                    <input class="form-control" name="semester_akademik" value="{{ old('semester_akademik', $editing->semester_akademik ?? 'Genap') }}">
                </div>
            </div>
            <div class="mt-3">
                <button class="btn btn-primary">{{ $editing ? 'Simpan Perubahan' : 'Tambah' }}</button>
                @if($editing)
                    <a href="{{ route('jadwal.index') }}" class="btn btn-secondary">Batal</a>
                @endif
            </div>
        </form>
    </div>
</div>
@endif

<div class="card">
    <div class="card-body">
        @if(($role ?? 'admin') === 'admin')
        <form class="row g-2 mb-3">
            <div class="col-md-3"><input class="form-control" name="q" value="{{ request('q') }}" placeholder="Cari jadwal..."></div>
            <div class="col-md-3"><button class="btn btn-outline-secondary">Filter</button></div>
        </form>
        @endif
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Hari</th>
                        <th>Mata Kuliah</th>
                        <th>Ruang</th>
                        <th>Golongan</th>
                        <th>Semester</th>
                        <th>Jam</th>
                        @if(($role ?? 'admin') === 'admin')
                            <th>Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($jadwal as $item)
                        <tr>
                            <td>{{ $item->hari }}</td>
                            <td>{{ $item->mataKuliah->nama_mk ?? $item->kode_mk }}</td>
                            <td>{{ $item->ruang->nama_ruang ?? $item->id_ruang }}</td>
                            <td>{{ $item->golongan->nama_gol ?? $item->id_gol }}</td>
                            <td>{{ $item->semester_kelas ?? '-' }}</td>
                            <td>{{ $item->jam_mulai }} - {{ $item->jam_selesai }}</td>
                            @if(($role ?? 'admin') === 'admin')
                                <td class="d-flex gap-2">
                                    <a class="btn btn-sm btn-outline-primary" href="{{ route('jadwal.index', ['edit' => $item->id]) }}">Edit</a>
                                    <form method="POST" action="{{ route('jadwal.destroy', $item->id) }}" onsubmit="return confirm('Hapus data ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                    </form>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ ($role ?? 'admin') === 'admin' ? 7 : 6 }}" class="text-center text-muted">Belum ada data.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $jadwal->links() }}
    </div>
</div>
@endsection
