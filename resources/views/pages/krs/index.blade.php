@extends('layouts.app')

@section('title', 'KRS')

@section('content')
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Kartu Rencana Studi</h4>
</div>

@if(($role ?? 'admin') !== 'mahasiswa')
<div class="card mb-3">
    <div class="card-body">
        <h6 class="mb-3">{{ $editing ? 'Edit KRS' : 'Tambah KRS' }}</h6>
        <form method="POST" action="{{ $editing ? route('krs.update', $editing->id) : route('krs.store') }}">
            @csrf
            @if($editing) @method('PUT') @endif
            <div class="row g-2">
                <div class="col-md-4">
                    <label class="form-label">Mahasiswa</label>
                    <select class="form-select" name="nim">
                        @foreach($mahasiswaList as $mhs)
                            <option value="{{ $mhs->nim }}" @selected(old('nim', $editing->nim ?? '') == $mhs->nim)>
                                {{ $mhs->nim }} - {{ $mhs->nama }}
                            </option>
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
                <div class="col-md-2">
                    <label class="form-label">Tahun</label>
                    <input class="form-control" name="tahun_akademik" value="{{ old('tahun_akademik', $editing->tahun_akademik ?? '2025/2026') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Semester</label>
                    <input class="form-control" name="semester_akademik" value="{{ old('semester_akademik', $editing->semester_akademik ?? 'Genap') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status KRS</label>
                    <select class="form-select" name="status_krs">
                        @php($statusVal = old('status_krs', $editing->status_krs ?? 'disetujui'))
                        <option value="diajukan" @selected($statusVal === 'diajukan')>Diajukan</option>
                        <option value="disetujui" @selected($statusVal === 'disetujui')>Disetujui</option>
                        <option value="ditolak" @selected($statusVal === 'ditolak')>Ditolak</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Nilai Akhir</label>
                    <input class="form-control" name="nilai_akhir" value="{{ old('nilai_akhir', $editing->nilai_akhir ?? '') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Nilai Angka</label>
                    <input class="form-control" name="nilai_angka" value="{{ old('nilai_angka', $editing->nilai_angka ?? '') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status Lulus</label>
                    <select class="form-select" name="status_lulus">
                        @php($lulusVal = old('status_lulus', $editing->status_lulus ?? 'belum'))
                        <option value="belum" @selected($lulusVal === 'belum')>Belum</option>
                        <option value="lulus" @selected($lulusVal === 'lulus')>Lulus</option>
                        <option value="tidak" @selected($lulusVal === 'tidak')>Tidak</option>
                    </select>
                </div>
            </div>
            <div class="mt-3">
                <button class="btn btn-primary">{{ $editing ? 'Simpan Perubahan' : 'Tambah' }}</button>
                @if($editing)
                    <a href="{{ route('krs.index') }}" class="btn btn-secondary">Batal</a>
                @endif
            </div>
        </form>
    </div>
</div>
@endif

<div class="card">
    <div class="card-body">
        @if(($role ?? 'admin') !== 'mahasiswa')
        <form class="row g-2 mb-3">
            <div class="col-md-4">
                <input class="form-control" name="q" value="{{ request('q') }}" placeholder="Cari mahasiswa...">
            </div>
            <div class="col-md-2">
                <button class="btn btn-outline-secondary">Filter</button>
            </div>
        </form>
        @endif
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Mahasiswa</th>
                        <th>Mata Kuliah</th>
                        <th>Status</th>
                        <th>Nilai</th>
                        @if(($role ?? 'admin') !== 'mahasiswa')
                            <th>Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($krs as $item)
                        <tr>
                            <td>{{ $item->mahasiswa->nama ?? $item->nim }}</td>
                            <td>{{ $item->mataKuliah->nama_mk ?? $item->kode_mk }}</td>
                            <td>{{ ucfirst($item->status_krs) }}</td>
                            <td>{{ $item->nilai_akhir ?? '-' }}</td>
                            @if(($role ?? 'admin') !== 'mahasiswa')
                                <td class="d-flex gap-2">
                                    <a class="btn btn-sm btn-outline-primary" href="{{ route('krs.index', ['edit' => $item->id]) }}">Edit</a>
                                    <form method="POST" action="{{ route('krs.destroy', $item->id) }}" onsubmit="return confirm('Hapus data ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                    </form>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ ($role ?? 'admin') !== 'mahasiswa' ? 5 : 4 }}" class="text-center text-muted">Belum ada data.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $krs->links() }}
    </div>
</div>
@endsection
