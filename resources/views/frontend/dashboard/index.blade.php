@extends('frontend.layouts.app')

@section('title', 'Dashboard Tamu')

@section('content')
<div class="container my-5">
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h3 class="mb-1">Selamat Datang, {{ $tamu->nama_lengkap }}!</h3>
                            <p class="mb-0">Kelola booking dan lihat riwayat transaksi Anda di sini.</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <i class="fas fa-user-circle fa-4x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-receipt fa-2x text-primary mb-2"></i>
                    <h4 class="fw-bold">{{ $stats['total_transaksi'] }}</h4>
                    <p class="text-muted mb-0">Total Booking</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                    <h4 class="fw-bold">{{ $stats['transaksi_lunas'] }}</h4>
                    <p class="text-muted mb-0">Booking Lunas</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-clock fa-2x text-warning mb-2"></i>
                    <h4 class="fw-bold">{{ $stats['transaksi_pending'] }}</h4>
                    <p class="text-muted mb-0">Booking Pending</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-money-bill-wave fa-2x text-info mb-2"></i>
                    <h4 class="fw-bold">Rp {{ number_format($stats['total_spent'], 0, ',', '.') }}</h4>
                    <p class="text-muted mb-0">Total Pengeluaran</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-history me-2"></i>Riwayat Transaksi
                    </h5>
                    <a href="{{ route('home') }}" class="btn btn-success btn-sm">
                        <i class="fas fa-plus me-2"></i>Booking Baru
                    </a>
                </div>
                <div class="card-body">
                    @if($transaksi->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>KAMAR</th>
                                        <th>CHECK IN</th>
                                        <th>CHECK OUT</th>
                                        <th>TOTAL</th>
                                        <th>STATUS</th>
                                        <th>AKSI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($transaksi as $item)
                                    <tr>
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
                                            <a href="{{ route('tamu.transaksi.show', $item->id_transaksi) }}" 
                                               class="btn btn-sm btn-outline-info">
                                                <i class="fas fa-eye"></i> Detail
                                            </a>
                                            @if($item->is_paid == 'pending' && $item->midtrans_order_id)
                                                <a href="{{ route('payment.create', $item->id_transaksi) }}" 
                                                   class="btn btn-sm btn-outline-success">
                                                    <i class="fas fa-credit-card"></i> Bayar
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if($transaksi->hasPages())
                            <div class="d-flex justify-content-center mt-4">
                                {{ $transaksi->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                            <h5>Belum ada transaksi</h5>
                            <p class="text-muted">Mulai booking kamar untuk melihat riwayat transaksi Anda.</p>
                            <a href="{{ route('home') }}" class="btn btn-success">
                                <i class="fas fa-plus me-2"></i>Booking Sekarang
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
