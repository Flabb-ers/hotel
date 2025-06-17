@extends('admin.layouts.app')

@section('title', 'Data Transaksi')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Data Transaksi</h1>
        <a href="{{ route('admin.transaksi.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Tambah Transaksi
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow">
        <div class="card-header bg-white">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Transaksi</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>NO</th>
                            <th>TAMU</th>
                            <th>KAMAR</th>
                            <th>CHECK IN</th>
                            <th>CHECK OUT</th>
                            <th>TOTAL</th>
                            <th>STATUS</th>
                            <th>AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transaksi as $index => $item)
                        <tr>
                            <td>{{ $transaksi->firstItem() + $index }}</td>
                            <td>
                                <div class="fw-bold">{{ $item->tamu->nama_lengkap }}</div>
                                <small class="text-muted">{{ $item->tamu->email }}</small>
                            </td>
                            <td>
                                <div class="fw-bold">{{ $item->kamar->nomer_kamar }}</div>
                                <small class="text-muted">{{ $item->kamar->tipeKamar->nama_tipe }}</small>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($item->tgl_checkin)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->tgl_checkout)->format('d/m/Y') }}</td>
                            <td>Rp {{ number_format($item->sub_total, 0, ',', '.') }}</td>
                            <td>
                                @if($item->is_paid == 'paid')
                                    <span class="badge bg-success">Lunas</span>
                                @elseif($item->is_paid == 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @elseif($item->is_paid == 'failed')
                                    <span class="badge bg-danger">Gagal</span>
                                @else
                                    <span class="badge bg-secondary">Dibatalkan</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.transaksi.show', $item->id_transaksi) }}" 
                                       class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.transaksi.edit', $item->id_transaksi) }}" 
                                       class="btn btn-sm btn-outline-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.transaksi.destroy', $item->id_transaksi) }}" 
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Yakin ingin menghapus transaksi ini?')">
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
                            <td colspan="8" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-receipt fa-3x mb-3"></i>
                                    <p>Belum ada data transaksi</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($transaksi->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $transaksi->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
