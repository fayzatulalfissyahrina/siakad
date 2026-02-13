@extends('layouts.app')

@section('title', 'Input Nilai')

@section('content')
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Input Nilai</h4>
</div>

<div class="card">
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-4">
                <input class="form-control" name="q" value="{{ request('q') }}" placeholder="Cari mahasiswa (NIM / nama)...">
            </div>
            <div class="col-md-4">
                <select class="form-select" name="paket">
                    <option value="">Pilih Mata Kuliah</option>
                    @foreach($pengampuList as $item)
                        @php($paket = $item->kode_mk.'|'.$item->tahun_akademik.'|'.$item->semester_akademik)
                        @php($selectedPaket = $selectedKodeMk.'|'.$selectedTahun.'|'.$selectedSemester)
                        <option value="{{ $paket }}" @selected($selectedPaket === $paket)>
                            {{ $item->mataKuliah->nama_mk ?? $item->kode_mk }} -
                            {{ $item->tahun_akademik }} {{ $item->semester_akademik }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-outline-secondary w-100">Tampilkan</button>
            </div>
        </form>

        @if($selectedKodeMk === '')
            <div class="alert alert-info mb-0">Pilih mata kuliah terlebih dahulu untuk menampilkan daftar mahasiswa.</div>
        @else
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Mahasiswa</th>
                        <th>Mata Kuliah</th>
                        <th>Nilai Angka</th>
                        <th>Nilai Akhir</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($krsList as $item)
                        <tr>
                            <td>{{ $item->mahasiswa->nim ?? $item->nim }} - {{ $item->mahasiswa->nama ?? '-' }}</td>
                            <td>{{ $item->mataKuliah->nama_mk ?? $item->kode_mk }}</td>
                            <td style="min-width: 170px;">
                                <input
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    max="100"
                                    class="form-control form-control-sm"
                                    name="nilai_angka"
                                    form="nilai-form-{{ $item->id }}"
                                    value="{{ old('nilai_angka', $item->nilai_angka) }}"
                                    placeholder="0 - 100"
                                    required
                                >
                            </td>
                            <td>{{ $item->nilai_akhir ?? '-' }}</td>
                            <td>{{ $item->status_lulus ?? '-' }}</td>
                            <td>
                                <form id="nilai-form-{{ $item->id }}" method="POST" action="{{ route($storeRoute) }}">
                                    @csrf
                                    <input type="hidden" name="nim" value="{{ $item->nim }}">
                                    <input type="hidden" name="kode_mk" value="{{ $item->kode_mk }}">
                                    <input type="hidden" name="tahun_akademik" value="{{ $item->tahun_akademik }}">
                                    <input type="hidden" name="semester_akademik" value="{{ $item->semester_akademik }}">
                                    <button class="btn btn-sm btn-primary">Simpan</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Belum ada data mahasiswa pada mata kuliah ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    const paket = document.querySelector('select[name="paket"]');
    paket?.addEventListener('change', function () {
        const value = this.value || '';
        const form = this.closest('form');
        form?.submit();
    });
</script>
@endpush
@endsection
