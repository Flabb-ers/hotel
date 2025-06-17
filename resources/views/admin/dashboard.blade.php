@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<!-- Welcome Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1">Welcome back, Admin User!</h4>
                        <p class="mb-0 opacity-75">Here's what's happening with your hotel today.</p>
                    </div>
                    <div>
                        <button class="btn btn-light">
                            <i class="fas fa-download me-2"></i>
                            Download Report
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card stats-card">
            <div class="card-body text-center">
                <div class="d-flex align-items-center justify-content-center mb-3">
                    <div class="bg-white bg-opacity-20 rounded-circle p-3">
                        <i class="fas fa-bed fa-2x"></i>
                    </div>
                </div>
                <h3 class="mb-1">{{ $stats['total_kamar'] }}</h3>
                <p class="mb-0">Jumlah Kamar</p>
                <small class="opacity-75">
                    <i class="fas fa-arrow-up me-1"></i>
                    2% dari bulan lalu
                </small>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card stats-card green">
            <div class="card-body text-center">
                <div class="d-flex align-items-center justify-content-center mb-3">
                    <div class="bg-white bg-opacity-20 rounded-circle p-3">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                </div>
                <h3 class="mb-1">{{ $stats['kamar_kosong'] }}</h3>
                <p class="mb-0">Kamar Kosong</p>
                <small class="opacity-75">
                    <i class="fas fa-arrow-down me-1"></i>
                    5% dari bulan lalu
                </small>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card stats-card orange">
            <div class="card-body text-center">
                <div class="d-flex align-items-center justify-content-center mb-3">
                    <div class="bg-white bg-opacity-20 rounded-circle p-3">
                        <i class="fas fa-door-closed fa-2x"></i>
                    </div>
                </div>
                <h3 class="mb-1">{{ $stats['kamar_terisi'] }}</h3>
                <p class="mb-0">Kamar Terisi</p>
                <small class="opacity-75">
                    <i class="fas fa-arrow-up me-1"></i>
                    8% dari bulan lalu
                </small>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card stats-card purple">
            <div class="card-body text-center">
                <div class="d-flex align-items-center justify-content-center mb-3">
                    <div class="bg-white bg-opacity-20 rounded-circle p-3">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
                <h3 class="mb-1">{{ $stats['total_tamu'] }}</h3>
                <p class="mb-0">Jumlah Tamu</p>
                <small class="opacity-75">
                    <i class="fas fa-arrow-up me-1"></i>
                    12% dari bulan lalu
                </small>
            </div>
        </div>
    </div>
</div>

<!-- Recent Guests Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Daftar Tamu</h5>
                    <div class="d-flex gap-2">
                        <input type="date" class="form-control" style="width: auto;" value="{{ date('Y-m-d') }}">
                        <button class="btn btn-success">
                            <i class="fas fa-filter me-2"></i>
                            Filter
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>NAMA</th>
                                <th>EMAIL</th>
                                <th>NOMOR HP</th>
                                <th>CHECK-IN</th>
                                <th>STATUS</th>
                                <th>AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentGuests as $index => $tamu)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                            <span class="text-white fw-bold">{{ substr($tamu->nama_lengkap, 0, 2) }}</span>
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $tamu->nama_lengkap }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $tamu->email }}</td>
                                <td>{{ $tamu->no_telp }}</td>
                                <td>{{ $tamu->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <span class="badge bg-success">Active</span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-warning">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Belum ada data tamu</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($recentGuests->count() > 0)
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <small class="text-muted">Showing 1 to {{ $recentGuests->count() }} of {{ $recentGuests->count() }} entries</small>
                    <nav>
                        <ul class="pagination pagination-sm mb-0">
                            <li class="page-item disabled">
                                <span class="page-link">Previous</span>
                            </li>
                            <li class="page-item active">
                                <span class="page-link">1</span>
                            </li>
                            <li class="page-item disabled">
                                <span class="page-link">Next</span>
                            </li>
                        </ul>
                    </nav>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
