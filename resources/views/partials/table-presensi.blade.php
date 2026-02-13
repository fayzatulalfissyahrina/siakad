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
            <td>{{ $item->jam_masuk ?? '-' }}</td>
            <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="5" class="text-center text-muted">Belum ada data.</td>
        </tr>
    @endforelse
</tbody>
