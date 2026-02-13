@extends('layouts.app')

@section('title', 'Edit Jadwal')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Edit Jadwal</h4>
    <a href="{{ route('jadwal.index') }}" class="btn btn-secondary">Kembali</a>
</div>

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('jadwal.update', $jadwal->id) }}" id="jadwal-form">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Hari <span class="text-danger">*</span></label>
                    <select class="form-select" name="hari" id="hari" required>
                        @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $hari)
                            <option value="{{ $hari }}" @selected(old('hari', $jadwal->hari) == $hari)>{{ $hari }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Golongan <span class="text-danger">*</span></label>
                    <select class="form-select" name="id_gol" id="id_gol" required>
                        <option value="">Pilih Golongan</option>
                        @foreach($golonganList as $gol)
                            <option value="{{ $gol->id_gol }}" @selected(old('id_gol', $jadwal->id_gol) == $gol->id_gol) data-angkatan="{{ $gol->angkatan }}">
                                {{ $gol->nama_gol }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Tahun Akademik <span class="text-danger">*</span></label>
                    <input class="form-control" name="tahun_akademik" id="tahun_akademik" value="{{ old('tahun_akademik', $jadwal->tahun_akademik) }}" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Semester <span class="text-danger">*</span></label>
                    <select class="form-select" name="semester_akademik" id="semester_akademik" required>
                        <option value="Ganjil" @selected(old('semester_akademik', $jadwal->semester_akademik) == 'Ganjil')>Ganjil</option>
                        <option value="Genap" @selected(old('semester_akademik', $jadwal->semester_akademik) == 'Genap')>Genap</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Semester MK</label>
                    <select class="form-select" id="semester_mk" disabled>
                        <option value="">Loading...</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Mata Kuliah <span class="text-danger">*</span></label>
                    <select class="form-select" name="kode_mk" id="kode_mk" required>
                        <option value="">Pilih Mata Kuliah</option>
                    </select>
                    <div class="invalid-feedback" id="kode_mk_error"></div>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Ruang <span class="text-danger">*</span></label>
                    <select class="form-select" name="id_ruang" id="id_ruang" required>
                        <option value="">Pilih Ruang</option>
                        @foreach($ruangList as $ruang)
                            <option value="{{ $ruang->id_ruang }}" @selected(old('id_ruang', $jadwal->id_ruang) == $ruang->id_ruang)>
                                {{ $ruang->nama_ruang }} ({{ $ruang->id_ruang }})
                            </option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback" id="ruang-error"></div>
                </div>
            </div>
            <div class="row g-3 mt-2">
                <div class="col-md-2">
                    <label class="form-label">Jam Mulai <span class="text-danger">*</span></label>
                    <input type="time" class="form-control" name="jam_mulai" id="jam_mulai" value="{{ old('jam_mulai', $jadwal->jam_mulai) }}" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Jam Selesai <span class="text-danger">*</span></label>
                    <input type="time" class="form-control" name="jam_selesai" id="jam_selesai" value="{{ old('jam_selesai', $jadwal->jam_selesai) }}" required>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary" id="submit-btn">Simpan Perubahan</button>
                <a href="{{ route('jadwal.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('jadwal-form');
    const ruangSelect = document.getElementById('id_ruang');
    const jamMulai = document.getElementById('jam_mulai');
    const jamSelesai = document.getElementById('jam_selesai');
    const hari = document.getElementById('hari');
    const tahunAkademik = document.getElementById('tahun_akademik');
    const semesterAkademik = document.getElementById('semester_akademik');
    const idGol = document.getElementById('id_gol');
    const semesterMk = document.getElementById('semester_mk');
    const kodeMk = document.getElementById('kode_mk');
    const submitBtn = document.getElementById('submit-btn');
    const ruangError = document.getElementById('ruang-error');
    const excludeId = {{ $jadwal->id }};
    
    function updateSemesterOptions() {
        if (!idGol.value || !tahunAkademik.value || !semesterAkademik.value) {
            semesterMk.innerHTML = '<option value="">Pilih dulu...</option>';
            semesterMk.disabled = true;
            return;
        }

        const params = new URLSearchParams({
            id_gol: idGol.value,
            tahun_akademik: tahunAkademik.value,
            semester_akademik: semesterAkademik.value
        });

        fetch(`/api/jadwal/semester-golongan?${params}`)
            .then(response => response.json())
            .then(data => {
                if (data.semester) {
                    semesterMk.innerHTML = `<option value="${data.semester}">Semester ${data.semester}</option>`;
                    semesterMk.disabled = false;
                    loadMataKuliah(data.semester);
                } else {
                    semesterMk.innerHTML = '<option value="">Tidak dapat menghitung semester</option>';
                    semesterMk.disabled = true;
                }
            });
    }

    function loadMataKuliah(semester) {
        const params = new URLSearchParams({ semester: semester });
        
        fetch(`/api/jadwal/mata-kuliah?${params}`)
            .then(response => response.json())
            .then(data => {
                if (data.mata_kuliah && data.mata_kuliah.length > 0) {
                    let options = '<option value="">Pilih Mata Kuliah</option>';
                    data.mata_kuliah.forEach(mk => {
                        const selected = mk.kode_mk === '{{ $jadwal->kode_mk }}' ? 'selected' : '';
                        options += `<option value="${mk.kode_mk}" ${selected}>${mk.kode_mk} - ${mk.nama_mk} (${mk.sks} SKS)</option>`;
                    });
                    kodeMk.innerHTML = options;
                    kodeMk.disabled = false;
                } else {
                    kodeMk.innerHTML = '<option value="">Tidak ada mata kuliah untuk semester ini</option>';
                    kodeMk.disabled = true;
                }
            });
    }
    
    function checkRuangAvailability() {
        if (!ruangSelect.value || !jamMulai.value || !jamSelesai.value || !hari.value || !tahunAkademik.value || !semesterAkademik.value) {
            return;
        }
        
        const params = new URLSearchParams({
            id_ruang: ruangSelect.value,
            hari: hari.value,
            jam_mulai: jamMulai.value,
            jam_selesai: jamSelesai.value,
            tahun_akademik: tahunAkademik.value,
            semester_akademik: semesterAkademik.value,
            exclude_id: excludeId
        });
        
        fetch(`/api/ruang/available?${params}`)
            .then(response => response.json())
            .then(data => {
                if (!data.available) {
                    ruangSelect.classList.add('is-invalid');
                    ruangError.textContent = data.message;
                } else {
                    ruangSelect.classList.remove('is-invalid');
                    ruangError.textContent = '';
                }
            });
    }

    function validateForm() {
        return idGol.value && tahunAkademik.value && semesterAkademik.value && 
               kodeMk.value && ruangSelect.value && jamMulai.value && jamSelesai.value && hari.value;
    }

    function checkRequiredFields() {
        if (!idGol.value || !tahunAkademik.value || !semesterAkademik.value || 
            !kodeMk.value || !ruangSelect.value || !jamMulai.value || !jamSelesai.value || !hari.value) {
            submitBtn.textContent = 'Lengkapi Data';
            submitBtn.classList.add('btn-secondary');
            submitBtn.classList.remove('btn-primary');
        } else {
            submitBtn.textContent = 'Simpan Perubahan';
            submitBtn.classList.remove('btn-secondary');
            submitBtn.classList.add('btn-primary');
        }
    }
    
    [idGol, tahunAkademik, semesterAkademik].forEach(el => {
        el.addEventListener('change', function() {
            updateSemesterOptions();
            checkRequiredFields();
        });
    });
    
    [ruangSelect, jamMulai, jamSelesai, hari].forEach(el => {
        el.addEventListener('change', function() {
            checkRuangAvailability();
            checkRequiredFields();
        });
        el.addEventListener('input', function() {
            checkRuangAvailability();
        });
    });

    kodeMk.addEventListener('change', function() {
        checkRequiredFields();
    });

    form.addEventListener('submit', function(e) {
        if (!validateForm()) {
            e.preventDefault();
            alert('Mohon lengkapi semua field yang diperlukan.');
        }
    });

    updateSemesterOptions();
    checkRequiredFields();
});
</script>
@endpush
@endsection
