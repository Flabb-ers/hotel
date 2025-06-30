<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>NO</th>
                <th>NAMA</th>
                <th>EMAIL</th>
                <th>NO. TELP</th>
                <th>TGL. DAFTAR</th>
                <th>STATUS</th>
                <th>AKSI</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recentGuests as $index => $tamu)
                <tr>
                    <td>{{ $recentGuests->firstItem() + $index }}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3"
                                style="width: 40px; height: 40px;">
                                <span class="text-white fw-bold">{{ substr($tamu->nama_lengkap, 0, 2) }}</span>
                            </div>
                            <div>
                                <div class="fw-bold">{{ $tamu->nama_lengkap }}</div>
                            </div>
                        </div>
                    </td>
                    <td>{{ $tamu->email }}</td>
                    <td>{{ $tamu->no_telp }}</td>
                    <td>{{ $tamu->created_at->format('d M Y') }}</td>
                    <td>
                        <span class="badge bg-success">Active</span>
                    </td>
                    <td>
                        <div class="btn-group" role="group">
                            <a href="{{ route('admin.tamu.show', $tamu->id_tamu) }}" class="btn btn-sm btn-outline-info"
                                title="Lihat">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.tamu.edit', $tamu->id_tamu) }}"
                                class="btn btn-sm btn-outline-warning" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.tamu.destroy', $tamu->id_tamu) }}" method="POST"
                                class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-4">
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Data tamu tidak ditemukan.</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if ($recentGuests->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $recentGuests->links() }}
    </div>
@endif
