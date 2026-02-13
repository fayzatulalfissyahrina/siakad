@extends('layouts.app')

@section('title', 'Ruang')

@push('styles')
<style>
    .table-wrap { background: #fff; border-radius: 8px; }
</style>
@endpush

@section('content')
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Data Ruang</h4>
    <a href="{{ route('ruang.create') }}" class="btn btn-primary">Tambah Ruang</a>
</div>

<div class="table-wrap p-3">
    <div class="row g-2 mb-3">
        <div class="col-md-6">
            <input class="form-control" id="live-search" placeholder="Ketik nama atau ID ruang untuk mencari..." autocomplete="off">
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped" id="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Ruang</th>
                    <th>Gedung</th>
                    <th>Lantai</th>
                    <th>Kapasitas</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="table-body">
                @include('partials.table-ruang', ['ruang' => $ruang])
            </tbody>
        </table>
    </div>

    <div id="pagination-wrapper">
        {{ $ruang->links() }}
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
        
        fetch(`/api/filter/ruang?q=${encodeURIComponent(q)}&page=${page}`)
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
