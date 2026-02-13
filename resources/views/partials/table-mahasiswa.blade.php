<tbody>
    @forelse($mahasiswa as $item)
        <tr>
            <td>{{ $item->nim }}</td>
            <td>{{ $item->nama }}</td>
            <td>{{ $item->semester }}</td>
            <td>{{ $item->golongan->nama_gol ?? '-' }}</td>
            <td>{{ ucfirst($item->status) }}</td>
            <td class="d-flex gap-2">
                <a class="btn btn-sm btn-outline-primary" href="{{ route('mahasiswa.edit', $item->nim) }}">Edit</a>
                <form method="POST" action="{{ route('mahasiswa.destroy', $item->nim) }}" onsubmit="return confirm('Hapus data ini?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger">Hapus</button>
                </form>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="6" class="text-center text-muted">Belum ada data.</td>
        </tr>
    @endforelse
</tbody>
