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
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Mahasiswa</th>
                            <th>Mata Kuliah</th>
                            <th>Status</th>
                            <th>Jam</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($presensi as $item)
                            <tr>
                                <td>{{ $item->mahasiswa->nama ?? $item->nim }}</td>
                                <td>{{ $item->mataKuliah->nama_mk ?? $item->kode_mk }}</td>
                                <td>
                                    @if($item->status_kehadiran === 'hadir')
                                        <span class="badge bg-success">Hadir</span>
                                    @elseif($item->status_kehadiran === 'sakit')
                                        <span class="badge bg-info">Sakit</span>
                                    @elseif($item->status_kehadiran === 'izin')
                                        <span class="badge bg-warning">Izin</span>
                                    @else
                                        <span class="badge bg-danger">Alpha</span>
                                    @endif
                                </td>
                                <td>{{ $item->jam_masuk ?? '-' }} - {{ $item->jam_keluar ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
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
            <h6 class="mb-2">Presensi Klik</h6>
            <div class="text-muted">Pilih status kehadiran saat dosen membuka sesi presensi (durasi 5-15 menit).</div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <h6 class="mb-3">Sesi Aktif - Pilih Status</h6>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Mata Kuliah</th>
                            <th>Golongan</th>
                            <th>Pertemuan</th>
                            <th>Semester</th>
                            <th>Berakhir</th>
                            <th>Status Kehadiran</th>
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
                                    <form method="POST" action="{{ route('mahasiswa.presensi.status') }}" class="d-flex gap-2">
                                        @csrf
                                        <input type="hidden" name="session_id" value="{{ $session->id }}">
                                        <div class="btn-group" role="group">
                                            <input type="radio" class="btn-check" name="status_kehadiran" id="hadir-{{ $session->id }}" value="hadir" autocomplete="off" checked>
                                            <label class="btn btn-sm btn-outline-success" for="hadir-{{ $session->id }}">Hadir</label>
                                            
                                            <input type="radio" class="btn-check" name="status_kehadiran" id="sakit-{{ $session->id }}" value="sakit" autocomplete="off">
                                            <label class="btn btn-sm btn-outline-info" for="sakit-{{ $session->id }}">Sakit</label>
                                            
                                            <input type="radio" class="btn-check" name="status_kehadiran" id="izin-{{ $session->id }}" value="izin" autocomplete="off">
                                            <label class="btn btn-sm btn-outline-warning" for="izin-{{ $session->id }}">Izin</label>
                                        </div>
                                </td>
                                <td>
                                        <button type="submit" class="btn btn-sm btn-primary">Kirim</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">Belum ada sesi aktif.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h6 class="mb-3">Riwayat Presensi</h6>
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
                                <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                                <td>{{ $item->mataKuliah->nama_mk ?? $item->kode_mk }}</td>
                                <td>
                                    @if($item->status_kehadiran === 'hadir')
                                        <span class="badge bg-success">Hadir</span>
                                    @elseif($item->status_kehadiran === 'sakit')
                                        <span class="badge bg-info">Sakit</span>
                                    @elseif($item->status_kehadiran === 'izin')
                                        <span class="badge bg-warning">Izin</span>
                                    @else
                                        <span class="badge bg-danger">Alpha</span>
                                    @endif
                                </td>
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
