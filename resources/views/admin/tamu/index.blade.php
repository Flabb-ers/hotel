@extends('admin.layouts.app')

@section('title', 'Data Tamu')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Data Tamu</h1>
        <a href="{{ route('admin.tamu.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Tambah Tamu
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
            <h6 class="m-0 font-weight-bold text-primary">Daftar Tamu</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>NO</th>
                            <th>NAMA LENGKAP</th>
                            <th>EMAIL</th>
                            <th>NO. TELP</th>
                            <th>ALAMAT</th>
                            <th>TANDA PENGENAL</th>
                            <th>AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tamu as $index => $item)
                        <tr>
                            <td>{{ $tamu->firstItem() + $index }}</td>
                            <td>
                                <div class="fw-bold">{{ $item->nama_lengkap }}</div>
                            </td>
                            <td>{{ $item->email }}</td>
                            <td>{{ $item->no_telp }}</td>
                            <td>{{ Str::limit($item->alamat, 30) }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->tanda_pengenal)->format('d/m/Y') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.tamu.show', $item->id_tamu) }}" 
                                       class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.tamu.edit', $item->id_tamu) }}" 
                                       class="btn btn-sm btn-outline-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.tamu.destroy', $item->id_tamu) }}" 
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Yakin ingin menghapus data ini?')">
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
                                <div class="text-muted">
                                    <i class="fas fa-users fa-3x mb-3"></i>
                                    <p>Belum ada data tamu</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($tamu->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $tamu->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
