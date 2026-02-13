@extends('layouts.app')

@section('title', 'Dosen')

@section('content')
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Data Dosen</h4>
    <a href="{{ route('dosen.create') }}" class="btn btn-primary">Tambah Dosen</a>
</div>

<div class="card">
    <div class="card-body">
        <div class="row g-2 mb-3">
            <div class="col-md-6">
                <input class="form-control" id="live-search" placeholder="Ketik NIP atau nama untuk mencari..." autocomplete="off">
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover" id="data-table">
                <thead>
                    <tr>
                        <th>NIP</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="table-body">
                    @include('partials.table-dosen', ['dosen' => $dosen])
                </tbody>
            </table>
        </div>

        <div id="pagination-wrapper">
            {{ $dosen->links() }}
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
        
        fetch(`/api/filter/dosen?q=${encodeURIComponent(q)}&page=${page}`)
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
