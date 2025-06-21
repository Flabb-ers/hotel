{{-- File ini hanya berisi tabel dan paginasi --}}
<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>NO</th>
                <th>NOMOR KAMAR</th>
                <th>TIPE KAMAR</th>
                <th>JUMLAH BED</th>
                <th>HARGA</th>
                <th>STATUS</th>
                <th>AKSI</th>
            </tr>
        </thead>
        <tbody>
            @forelse($kamar as $index => $item)
                <tr>
                    <td>{{ $kamar->firstItem() + $index }}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            @if ($item->thumbnail_kamar)
                                <img src="{{ asset('uploads/kamar/' . $item->thumbnail_kamar) }}"
                                    alt="Kamar {{ $item->nomer_kamar }}" class="rounded me-3"
                                    style="width: 50px; height: 50px; object-fit: cover;">
                            @else
                                <div class="bg-secondary rounded d-flex align-items-center justify-content-center me-3"
                                    style="width: 50px; height: 50px;">
                                    <i class="fas fa-bed text-white"></i>
                                </div>
                            @endif
                            <div>
                                <div class="fw-bold">{{ $item->nomer_kamar }}</div>
                                <small class="text-muted">{{ Str::limit($item->fasilitas, 30) }}</small>
                            </div>
                        </div>
                    </td>
                    <td>{{ $item->tipeKamar->nama_tipe ?? '-' }}</td>
                    <td>{{ $item->jumlah_bed }} bed</td>
                    <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                    <td>
                        @if ($item->status == 'tersedia')
                            <span class="badge bg-success">Tersedia</span>
                        @elseif($item->status == 'terisi')
                            <span class="badge bg-warning">Terisi</span>
                        @else
                            <span class="badge bg-danger">Maintenance</span>
                        @endif
                    </td>
                    <td>
                        <div class="btn-group" role="group">
                            <a href="{{ route('admin.kamar.show', $item->id_kamar) }}"
                                class="btn btn-sm btn-outline-info">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.kamar.edit', $item->id_kamar) }}"
                                class="btn btn-sm btn-outline-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.kamar.destroy', $item->id_kamar) }}" method="POST"
                                class="d-inline" onsubmit="return confirm('Yakin ingin menghapus kamar ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
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
                        <p class="text-muted">Data kamar tidak ditemukan.</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if ($kamar->hasPages())
    <div class="d-flex justify-content-between align-items-center mt-3">
        <div>
            <small class="text-muted">
                Menampilkan {{ $kamar->firstItem() }} sampai {{ $kamar->lastItem() }}
                dari {{ $kamar->total() }} data
            </small>
        </div>
        <div>
            {{ $kamar->links() }}
        </div>
    </div>
@endif
