@extends('layouts.app')

@section('title', 'Mata Kuliah')

@section('content')
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Mata Kuliah</h4>
    <a href="{{ route('mata-kuliah.create') }}" class="btn btn-primary">Tambah Mata Kuliah</a>
</div>

<div class="card">
    <div class="card-body">
        <div class="row g-2 mb-3">
            <div class="col-md-6">
                <input class="form-control" id="live-search" placeholder="Ketik kode atau nama untuk mencari..." autocomplete="off">
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered" id="data-table">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>SKS</th>
                        <th>Semester</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="table-body">
                    @include('partials.table-mata-kuliah', ['mataKuliah' => $mataKuliah])
                </tbody>
            </table>
        </div>

        <div id="pagination-wrapper">
            {{ $mataKuliah->links() }}
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
        
        fetch(`/api/filter/mata-kuliah?q=${encodeURIComponent(q)}&page=${page}`)
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
