<tbody>
    @forelse($ruang as $item)
        <tr>
            <td>{{ $item->id_ruang }}</td>
            <td>{{ $item->nama_ruang }}</td>
            <td>{{ $item->gedung }}</td>
            <td>{{ $item->lantai }}</td>
            <td>{{ $item->kapasitas }}</td>
            <td>{{ ucfirst($item->status) }}</td>
            <td class="d-flex gap-2">
                <a class="btn btn-sm btn-outline-primary" href="{{ route('ruang.edit', $item->id_ruang) }}">Edit</a>
                <form method="POST" action="{{ route('ruang.destroy', $item->id_ruang) }}" onsubmit="return confirm('Hapus data ini?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger">Hapus</button>
                </form>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="7" class="text-center text-muted">Belum ada data.</td>
        </tr>
    @endforelse
</tbody>
