<tbody>
    @forelse($pengampu as $item)
        <tr>
            <td>{{ $item->kode_mk }}</td>
            <td>{{ $item->mataKuliah->nama_mk ?? '-' }}</td>
            <td>{{ $item->dosen->nama ?? '-' }}</td>
            <td>{{ $item->tahun_akademik }}</td>
            <td>{{ $item->semester_akademik }}</td>
            <td>{{ ucfirst($item->status) }}</td>
            <td class="d-flex gap-2">
                <a class="btn btn-sm btn-outline-primary" href="{{ route('pengampu.edit', $item->id) }}">Edit</a>
                <form method="POST" action="{{ route('pengampu.destroy', $item->id) }}" onsubmit="return confirm('Hapus data ini?')">
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
