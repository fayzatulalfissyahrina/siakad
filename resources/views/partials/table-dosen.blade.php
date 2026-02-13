<tbody>
    @forelse($dosen as $item)
        <tr>
            <td>{{ $item->nip }}</td>
            <td>{{ $item->nama }}</td>
            <td>{{ $item->email }}</td>
            <td>{{ ucfirst($item->status) }}</td>
            <td class="d-flex gap-2">
                <a class="btn btn-sm btn-outline-primary" href="{{ route('dosen.index', ['edit' => $item->nip]) }}">Edit</a>
                <form method="POST" action="{{ route('dosen.destroy', $item->nip) }}" onsubmit="return confirm('Hapus data ini?')">
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
