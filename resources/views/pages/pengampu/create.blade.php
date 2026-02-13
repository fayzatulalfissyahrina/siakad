@extends('layouts.app')

@section('title', 'Tambah Pengampu')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Tambah Pengampu</h4>
    <a href="{{ route('pengampu.index') }}" class="btn btn-secondary">Kembali</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('pengampu.store') }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Semester Mata Kuliah <span class="text-danger">*</span></label>
                    <select class="form-select" id="semester_mk" required>
                        <option value="">Pilih Semester</option>
                        @for($i = 1; $i <= 8; $i++)
                            <option value="{{ $i }}">Semester {{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Mata Kuliah <span class="text-danger">*</span></label>
                    <select class="form-select" name="kode_mk" id="kode_mk" required disabled>
                        <option value="">Pilih Semester terlebih dahulu</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Dosen <span class="text-danger">*</span></label>
                    <select class="form-select" name="nip" required>
                        @foreach($dosenList as $dsn)
                            <option value="{{ $dsn->nip }}">{{ $dsn->nama }} ({{ $dsn->nip }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Tahun Akademik <span class="text-danger">*</span></label>
                    <input class="form-control" name="tahun_akademik" value="{{ date('Y') }}/{{ date('Y')+1 }}" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Semester Akademik <span class="text-danger">*</span></label>
                    <select class="form-select" name="semester_akademik" required>
                        <option value="Ganjil">Ganjil</option>
                        <option value="Genap">Genap</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <select class="form-select" name="status" required>
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('pengampu.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const semesterSelect = document.getElementById('semester_mk');
    const kodeMkSelect = document.getElementById('kode_mk');
    
    semesterSelect.addEventListener('change', function() {
        const semester = this.value;
        
        if (semester) {
            kodeMkSelect.disabled = true;
            kodeMkSelect.innerHTML = '<option value="">Memuat...</option>';
            
            fetch(`/api/mata-kuliah/by-semester?semester=${semester}`)
                .then(response => response.json())
                .then(data => {
                    kodeMkSelect.innerHTML = '<option value="">Pilih Mata Kuliah</option>';
                    data.forEach(mk => {
                        const option = document.createElement('option');
                        option.value = mk.kode_mk;
                        option.textContent = `${mk.kode_mk} - ${mk.nama_mk} (${mk.sks} SKS)`;
                        kodeMkSelect.appendChild(option);
                    });
                    kodeMkSelect.disabled = false;
                })
                .catch(error => {
                    console.error('Error:', error);
                    kodeMkSelect.innerHTML = '<option value="">Error memuat data</option>';
                });
        } else {
            kodeMkSelect.disabled = true;
            kodeMkSelect.innerHTML = '<option value="">Pilih Semester terlebih dahulu</option>';
        }
    });
});
</script>
@endpush
@endsection
