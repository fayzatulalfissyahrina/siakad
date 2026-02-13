<tbody>
    @forelse($jadwal as $item)
        <tr>
            <td>{{ $item->hari }}</td>
            <td>{{ $item->mataKuliah->nama_mk ?? $item->kode_mk }}</td>
            <td>{{ $item->ruang->nama_ruang ?? $item->id_ruang }}</td>
            <td>{{ $item->golongan->nama_gol ?? $item->id_gol }}</td>
            <td>{{ $item->semester_akademik }} {{ $item->tahun_akademik }}</td>
            <td>Semester {{ $item->semester_kelas ?? '-' }}</td>
            <td>{{ $item->jam_mulai }} - {{ $item->jam_selesai }}</td>
            @if(($role ?? 'admin') === 'admin')
                <td class="d-flex gap-2">
                    <a class="btn btn-sm btn-outline-primary" href="{{ route('jadwal.index', ['edit' => $item->id]) }}">Edit</a>
                    <form method="POST" action="{{ route('jadwal.destroy', $item->id) }}" onsubmit="return confirm('Hapus data ini?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">Hapus</button>
                    </form>
                </td>
            @endif
        </tr>
    @empty
        <tr>
            <td colspan="{{ ($role ?? 'admin') === 'admin' ? 8 : 7 }}" class="text-center text-muted">Belum ada data.</td>
        </tr>
    @endforelse
</tbody>
