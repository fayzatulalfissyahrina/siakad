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
    @if(($role ?? 'admin') === 'admin')
        <a href="{{ route('jadwal.create') }}" class="btn btn-primary">Tambah Jadwal</a>
    @endif
</div>

<div class="card">
    <div class="card-body">
        <div class="row g-2 mb-3">
            <div class="col-md-6">
                <input class="form-control" id="live-search" placeholder="Ketik untuk mencari..." autocomplete="off">
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-striped" id="data-table">
                <thead>
                    <tr>
                        <th>Hari</th>
                        <th>Mata Kuliah</th>
                        <th>Ruang</th>
                        <th>Golongan</th>
                        <th>Thn/Smt</th>
                        <th>Kelas</th>
                        <th>Jam</th>
                        @if(($role ?? 'admin') === 'admin')
                            <th>Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody id="table-body">
                    @include('partials.table-jadwal', ['jadwal' => $jadwal, 'role' => $role ?? 'admin'])
                </tbody>
            </table>
        </div>

        <div id="pagination-wrapper">
            {{ $jadwal->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('live-search');
    const tableBody = document.getElementById('table-body');
    const paginationWrapper = document.getElementById('pagination-wrapper');
    let searchTimeout = null;

    function loadData(page = 1) {
        const q = searchInput.value;
        
        fetch(`/api/filter/jadwal?q=${encodeURIComponent(q)}&page=${page}`)
            .then(response => response.json())
            .then(data => {
                tableBody.innerHTML = data.html;
                paginationWrapper.innerHTML = data.pagination;
            });
    }

    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(loadData, 300);
    });

    paginationWrapper.addEventListener('click', function(e) {
        e.preventDefault();
        const link = e.target.closest('a');
        if (link) {
            const url = new URL(link.href);
            const page = url.searchParams.get('page');
            loadData(page);
        }
    });
});
</script>
@endpush
@endsection
