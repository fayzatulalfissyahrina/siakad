<aside class="sidebar d-none d-lg-block">
    <div class="p-4">
        <div class="mb-4">
            <div class="fw-bold">Menu Utama</div>
            <div class="small text-white-50">Navigasi Sistem</div>
        </div>
        <ul class="list-unstyled">
            <li class="mb-2"><a href="{{ route('dashboard') }}"><i class="fa-solid fa-chart-line me-2"></i>Dashboard</a></li>

            @if(auth()->user()?->role === 'admin')
                <li class="mb-2"><a href="{{ route('mahasiswa.index') }}"><i class="fa-solid fa-user-graduate me-2"></i>Mahasiswa</a></li>
                <li class="mb-2"><a href="{{ route('dosen.index') }}"><i class="fa-solid fa-user-tie me-2"></i>Dosen</a></li>
                <li class="mb-2"><a href="{{ route('mata-kuliah.index') }}"><i class="fa-solid fa-book me-2"></i>Mata Kuliah</a></li>
                <li class="mb-2"><a href="{{ route('jadwal.index') }}"><i class="fa-solid fa-calendar-days me-2"></i>Jadwal</a></li>
                <li class="mb-2"><a href="{{ route('krs.index') }}"><i class="fa-solid fa-clipboard-list me-2"></i>KRS</a></li>
                <li class="mb-2"><a href="{{ route('presensi.index') }}"><i class="fa-solid fa-qrcode me-2"></i>Presensi</a></li>
                <li class="mb-2"><a href="{{ route('laporan.index') }}"><i class="fa-solid fa-file-lines me-2"></i>Laporan</a></li>
            @elseif(auth()->user()?->role === 'dosen')
                <li class="mb-2"><a href="{{ route('dosen.jadwal') }}"><i class="fa-solid fa-calendar-days me-2"></i>Jadwal Mengajar</a></li>
                <li class="mb-2"><a href="{{ route('dosen.presensi') }}"><i class="fa-solid fa-qrcode me-2"></i>Presensi Mahasiswa</a></li>
                <li class="mb-2"><a href="{{ route('dosen.nilai') }}"><i class="fa-solid fa-pen-to-square me-2"></i>Input Nilai</a></li>
                <li class="mb-2"><a href="{{ route('dosen.laporan') }}"><i class="fa-solid fa-file-lines me-2"></i>Laporan</a></li>
            @else
                <li class="mb-2"><a href="{{ route('mahasiswa.jadwal') }}"><i class="fa-solid fa-calendar-days me-2"></i>Jadwal Kuliah</a></li>
                <li class="mb-2"><a href="{{ route('mahasiswa.krs') }}"><i class="fa-solid fa-clipboard-list me-2"></i>KRS</a></li>
                <li class="mb-2"><a href="{{ route('mahasiswa.presensi') }}"><i class="fa-solid fa-qrcode me-2"></i>Presensi</a></li>
            @endif
        </ul>
    </div>
</aside>
