@extends('layouts.app')

@section('title', 'KRS')

@push('scripts')
<script>
    document.querySelector('.btn-primary')?.addEventListener('click', () => {
        Swal.fire({
            title: 'Ajukan KRS',
            text: 'Lanjutkan pengajuan KRS?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya',
            cancelButtonText: 'Batal'
        });
    });
</script>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Kartu Rencana Studi</h4>
    <button class="btn btn-primary"><i class="fa-solid fa-plus me-2"></i>Ajukan KRS</button>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Mata Kuliah</th>
                        <th>SKS</th>
                        <th>Status</th>
                        <th>Nilai</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="4" class="text-center text-muted">Belum ada data.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
