@extends('layouts.app')

@section('title', 'Pengampu')

@section('content')
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Data Pengampu</h4>
    <a href="{{ route('pengampu.create') }}" class="btn btn-primary">Tambah Pengampu</a>
</div>

<div class="table-wrap p-3">
    <form class="row g-2 mb-3">
        <div class="col-md-4">
            <input class="form-control" name="q" value="{{ request('q') }}" placeholder="Cari pengampu...">
        </div>
        <div class="col-md-2">
            <button class="btn btn-outline-secondary">Filter</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Kode MK</th>
                    <th>Mata Kuliah</th>
                    <th>Dosen</th>
                    <th>Tahun Akademik</th>
                    <th>Semester</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengampu as $item)
                    <tr>
                        <td>{{ $item->kode_mk }}</td>
                        <td>{{ $item->mataKuliah->nama_mk ?? '-' }}</td>
                        <td>{{ $item->dosen->nama ?? '-' }}</td>
                        <td>{{ $item->tahun_akademik }}</td>
                        <td>{{ $item->semester_akademik }}</td>
                        <td>{{ ucfirst($item->status) }}</td>
                        <td class="d-flex gap-2">
                            <a class="btn btn-sm btn-outline-primary" href="{{ route('pengampu.edit', $item->id) }}">Edit</a>
                            <form method="POST" action="{{ route('pengampu.destroy', $item->id) }}" onsubmit="return confirm('Hapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">Belum ada data.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $pengampu->links() }}
</div>
@endsection
