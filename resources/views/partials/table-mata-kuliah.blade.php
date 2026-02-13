<tbody>
    @forelse($mataKuliah as $item)
        <tr>
            <td>{{ $item->kode_mk }}</td>
            <td>{{ $item->nama_mk }}</td>
            <td>{{ $item->sks }}</td>
            <td>{{ $item->semester }}</td>
            <td>{{ ucfirst($item->jenis) }}</td>
            <td class="d-flex gap-2">
                <a class="btn btn-sm btn-outline-primary" href="{{ route('mata-kuliah.index', ['edit' => $item->kode_mk]) }}">Edit</a>
                <form method="POST" action="{{ route('mata-kuliah.destroy', $item->kode_mk) }}" onsubmit="return confirm('Hapus data ini?')">
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
