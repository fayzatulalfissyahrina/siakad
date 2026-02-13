@extends('layouts.app')

@section('title', 'Presensi')

@section('content')
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Presensi Akademik</h4>
</div>

@if(($role ?? 'admin') === 'admin')
    <div class="card mb-3">
        <div class="card-body">
            <h6 class="mb-3">{{ $editing ? 'Edit Presensi' : 'Tambah Presensi' }}</h6>
            <form method="POST" action="{{ $editing ? route('presensi.update', $editing->id) : route('presensi.store') }}">
                @csrf
                @if($editing) @method('PUT') @endif
                <div class="row g-2">
                    <div class="col-md-3">
                        <label class="form-label">Tanggal</label>
                        <input type="date" class="form-control" name="tanggal" value="{{ old('tanggal', ($editing && $editing->tanggal) ? \Illuminate\Support\Carbon::parse($editing->tanggal)->format('Y-m-d') : date('Y-m-d')) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Hari</label>
                        <input class="form-control" name="hari" value="{{ old('hari', $editing->hari ?? 'Senin') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Mahasiswa</label>
                        <select class="form-select" name="nim">
                            @foreach($mahasiswaList as $mhs)
                                <option value="{{ $mhs->nim }}" @selected(old('nim', $editing->nim ?? '') == $mhs->nim)>
                                    {{ $mhs->nim }} - {{ $mhs->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
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
                        <label class="form-label">Status</label>
                        @php($statusVal = old('status_kehadiran', $editing->status_kehadiran ?? 'hadir'))
                        <select class="form-select" name="status_kehadiran">
                            <option value="hadir" @selected($statusVal === 'hadir')>Hadir</option>
                            <option value="izin" @selected($statusVal === 'izin')>Izin</option>
                            <option value="sakit" @selected($statusVal === 'sakit')>Sakit</option>
                            <option value="alpha" @selected($statusVal === 'alpha')>Alpha</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Jam Masuk</label>
                        <input type="time" class="form-control" name="jam_masuk" value="{{ old('jam_masuk', $editing->jam_masuk ?? '') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Jam Keluar</label>
                        <input type="time" class="form-control" name="jam_keluar" value="{{ old('jam_keluar', $editing->jam_keluar ?? '') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Pertemuan</label>
                        <input type="number" class="form-control" name="pertemuan_ke" value="{{ old('pertemuan_ke', $editing->pertemuan_ke ?? 1) }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Metode</label>
                        <input class="form-control" name="metode_presensi" value="{{ old('metode_presensi', $editing->metode_presensi ?? 'Manual') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Keterangan</label>
                        <input class="form-control" name="keterangan" value="{{ old('keterangan', $editing->keterangan ?? '') }}">
                    </div>
                </div>
                <div class="mt-3">
                    <button class="btn btn-primary">{{ $editing ? 'Simpan Perubahan' : 'Tambah' }}</button>
                    @if($editing)
                        <a href="{{ route('presensi.index') }}" class="btn btn-secondary">Batal</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form class="row g-2 mb-3">
                <div class="col-md-4"><input class="form-control" name="q" value="{{ request('q') }}" placeholder="Cari mahasiswa..."></div>
                <div class="col-md-3"><input type="date" class="form-control" name="tanggal" value="{{ request('tanggal') }}"></div>
                <div class="col-md-3">
                    <select class="form-select" name="status">
                        <option value="all">Semua Status</option>
                        <option value="hadir" @selected(request('status') === 'hadir')>Hadir</option>
                        <option value="izin" @selected(request('status') === 'izin')>Izin</option>
                        <option value="sakit" @selected(request('status') === 'sakit')>Sakit</option>
                        <option value="alpha" @selected(request('status') === 'alpha')>Alpha</option>
                    </select>
                </div>
                <div class="col-md-2"><button class="btn btn-outline-secondary">Filter</button></div>
            </form>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Mahasiswa</th>
                            <th>Mata Kuliah</th>
                            <th>Status</th>
                            <th>Jam</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($presensi as $item)
                            <tr>
                                <td>{{ $item->mahasiswa->nama ?? $item->nim }}</td>
                                <td>{{ $item->mataKuliah->nama_mk ?? $item->kode_mk }}</td>
                                <td>{{ ucfirst($item->status_kehadiran) }}</td>
                                <td>{{ $item->jam_masuk ?? '-' }} - {{ $item->jam_keluar ?? '-' }}</td>
                                <td class="d-flex gap-2">
                                    <a class="btn btn-sm btn-outline-primary" href="{{ route('presensi.index', ['edit' => $item->id]) }}">Edit</a>
                                    <form method="POST" action="{{ route('presensi.destroy', $item->id) }}" onsubmit="return confirm('Hapus data ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Belum ada data.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $presensi->links() }}
        </div>
    </div>
@elseif(($role ?? '') === 'dosen')
    <div class="row g-3">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h6 class="mb-3">Buka Sesi Presensi</h6>
                    <form method="POST" action="{{ route('dosen.presensi.open') }}">
                        @csrf
                        <div class="row g-2">
                            <div class="col-md-12">
                                <label class="form-label">Jadwal (Mata Kuliah - Golongan)</label>
                                <select class="form-select" name="jadwal_id">
                                    @foreach($jadwal as $j)
                                        <option value="{{ $j->id }}">
                                            {{ $j->mataKuliah->nama_mk ?? $j->kode_mk }}
                                            - {{ $j->golongan->nama_gol ?? $j->id_gol }}
                                            (Semester: {{ $j->semester_kelas ?? '-' }})
                                            - {{ $j->hari }} {{ $j->jam_mulai }}-{{ $j->jam_selesai }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Pertemuan</label>
                                <input type="number" class="form-control" name="pertemuan_ke" value="1">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Tahun</label>
                                <input class="form-control" name="tahun_akademik" value="2025/2026">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Semester</label>
                                <input class="form-control" name="semester_akademik" value="Genap">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Durasi (menit)</label>
                                <input type="number" class="form-control" name="expires_in" value="5" min="5" max="15">
                            </div>
                        </div>
                        <div class="mt-3">
                            <button class="btn btn-success">Buka Sesi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h6 class="mb-3">Sesi Terakhir</h6>
                    @if($sessionList->count() > 0)
                        @php($latest = $sessionList->first())
                        <div>
                            <div><strong>Mata Kuliah:</strong> {{ $latest->mataKuliah->nama_mk ?? $latest->kode_mk }}</div>
                            <div><strong>Golongan:</strong> {{ $latest->golongan->nama_gol ?? $latest->id_gol }}</div>
                            <div><strong>Pertemuan:</strong> {{ $latest->pertemuan_ke }}</div>
                            <div><strong>Semester:</strong> {{ $latest->semester_kelas ?? '-' }}</div>
                            <div><strong>Berakhir:</strong> {{ \Illuminate\Support\Carbon::parse($latest->expires_at)->format('d M Y H:i') }}</div>
                            <div><strong>Status:</strong> {{ \Illuminate\Support\Carbon::parse($latest->expires_at)->isPast() ? 'expired' : $latest->status }}</div>
                            <div><strong>Hadir:</strong> {{ $latest->hadir_count ?? 0 }}</div>
                            @if($latest->status === 'aktif' && \Illuminate\Support\Carbon::parse($latest->expires_at)->isFuture())
                                <form class="mt-2" method="POST" action="{{ route('dosen.presensi.close', $latest->id) }}">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-danger">Tutup Sesi</button>
                                </form>
                            @endif
                        </div>
                    @else
                        <div class="text-muted">Belum ada sesi.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-body">
            <h6 class="mb-3">Riwayat Sesi</h6>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Mata Kuliah</th>
                            <th>Golongan</th>
                            <th>Pertemuan</th>
                            <th>Semester</th>
                            <th>Status</th>
                            <th>Hadir</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sessionList as $item)
                            <tr>
                                <td>{{ \Illuminate\Support\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                                <td>{{ $item->mataKuliah->nama_mk ?? $item->kode_mk }}</td>
                                <td>{{ $item->golongan->nama_gol ?? $item->id_gol }}</td>
                                <td>{{ $item->pertemuan_ke }}</td>
                                <td>{{ $item->semester_kelas ?? '-' }}</td>
                                <td>{{ \Illuminate\Support\Carbon::parse($item->expires_at)->isPast() ? 'expired' : $item->status }}</td>
                                <td>{{ $item->hadir_count ?? 0 }}</td>
                                <td>
                                    @if($item->status === 'aktif' && \Illuminate\Support\Carbon::parse($item->expires_at)->isFuture())
                                        <form method="POST" action="{{ route('dosen.presensi.close', $item->id) }}">
                                            @csrf
                                            <button class="btn btn-sm btn-outline-danger">Tutup</button>
                                        </form>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">Belum ada data.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@else
    <div class="card mb-3">
        <div class="card-body">
            <h6 class="mb-2">Presensi Klik Hadir</h6>
            <div class="text-muted">Klik tombol Hadir saat dosen membuka sesi presensi (durasi 5-15 menit).</div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <h6 class="mb-3">Sesi Aktif</h6>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Mata Kuliah</th>
                            <th>Golongan</th>
                            <th>Pertemuan</th>
                            <th>Semester</th>
                            <th>Berakhir</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($activeSessions as $session)
                            <tr>
                                <td>{{ $session->mataKuliah->nama_mk ?? $session->kode_mk }}</td>
                                <td>{{ $session->golongan->nama_gol ?? $session->id_gol }}</td>
                                <td>{{ $session->pertemuan_ke }}</td>
                                <td>{{ $session->semester_kelas ?? '-' }}</td>
                                <td>{{ \Illuminate\Support\Carbon::parse($session->expires_at)->format('H:i') }}</td>
                                <td>
                                    <form method="POST" action="{{ route('mahasiswa.presensi.hadir') }}">
                                        @csrf
                                        <input type="hidden" name="session_id" value="{{ $session->id }}">
                                        <button class="btn btn-sm btn-success">Hadir</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">Belum ada sesi aktif.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Mata Kuliah</th>
                            <th>Status</th>
                            <th>Jam</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($presensi as $item)
                            <tr>
                                <td>{{ \Illuminate\Support\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                                <td>{{ $item->mataKuliah->nama_mk ?? $item->kode_mk }}</td>
                                <td>{{ ucfirst($item->status_kehadiran) }}</td>
                                <td>{{ $item->jam_masuk ?? '-' }} - {{ $item->jam_keluar ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">Belum ada data.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $presensi->links() }}
        </div>
    </div>
@endif
@endsection
