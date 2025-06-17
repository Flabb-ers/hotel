@extends('frontend.layouts.app')

@section('title', 'Detail Transaksi')

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Detail Transaksi</h2>
                <a href="{{ route('tamu.dashboard') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Transaction Info -->
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-receipt me-2"></i>Informasi Booking
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3">Detail Kamar</h6>
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td width="40%"><strong>No. Kamar</strong></td>
                                    <td>: {{ $transaksi->kamar->nomer_kamar }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tipe Kamar</strong></td>
                                    <td>: {{ $transaksi->kamar->tipeKamar->nama_tipe }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Harga/Malam</strong></td>
                                    <td>: Rp {{ number_format($transaksi->kamar->harga, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Fasilitas</strong></td>
                                    <td>: {{ $transaksi->kamar->fasilitas ?: 'Standar' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3">Detail Booking</h6>
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td width="40%"><strong>Tanggal Booking</strong></td>
                                    <td>: {{ \Carbon\Carbon::parse($transaksi->tgl_transaksi)->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Check In</strong></td>
                                    <td>: {{ \Carbon\Carbon::parse($transaksi->tgl_checkin)->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Check Out</strong></td>
                                    <td>: {{ \Carbon\Carbon::parse($transaksi->tgl_checkout)->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Total Malam</strong></td>
                                    <td>: {{ \Carbon\Carbon::parse($transaksi->tgl_checkin)->diffInDays(\Carbon\Carbon::parse($transaksi->tgl_checkout)) }} malam</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Info -->
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-credit-card me-2"></i>Informasi Pembayaran
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td width="40%"><strong>Status Bayar</strong></td>
                                    <td>: 
                                        @if($transaksi->is_paid == 'paid')
                                            <span class="badge bg-success fs-6">Lunas</span>
                                        @elseif($transaksi->is_paid == 'pending')
                                            <span class="badge bg-warning fs-6">Pending</span>
                                        @elseif($transaksi->is_paid == 'failed')
                                            <span class="badge bg-danger fs-6">Gagal</span>
                                        @else
                                            <span class="badge bg-secondary fs-6">Dibatalkan</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Total Bayar</strong></td>
                                    <td>: <span class="fw-bold text-success fs-5">Rp {{ number_format($transaksi->sub_total, 0, ',', '.') }}</span></td>
                                </tr>
                                @if($transaksi->midtrans_order_id)
                                <tr>
                                    <td><strong>Order ID</strong></td>
                                    <td>: {{ $transaksi->midtrans_order_id }}</td>
                                </tr>
                                @endif
                                @if($transaksi->midtrans_transaction_id)
                                <tr>
                                    <td><strong>Transaction ID</strong></td>
                                    <td>: {{ $transaksi->midtrans_transaction_id }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                        <div class="col-md-6">
                            @if($transaksi->is_paid == 'pending')
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <strong>Pembayaran Pending</strong><br>
                                    Silakan lakukan pembayaran untuk menyelesaikan booking Anda.
                                </div>
                                @if($transaksi->midtrans_order_id)
                                    <div class="d-grid">
                                        <a href="{{ route('payment.create', $transaksi->id_transaksi) }}" 
                                           class="btn btn-success btn-lg">
                                            <i class="fas fa-credit-card me-2"></i>Bayar Sekarang
                                        </a>
                                    </div>
                                @endif
                            @elseif($transaksi->is_paid == 'paid')
                                <div class="alert alert-success">
                                    <i class="fas fa-check-circle me-2"></i>
                                    <strong>Pembayaran Berhasil</strong><br>
                                    Terima kasih! Booking Anda telah dikonfirmasi.
                                </div>
                            @elseif($transaksi->is_paid == 'failed')
                                <div class="alert alert-danger">
                                    <i class="fas fa-times-circle me-2"></i>
                                    <strong>Pembayaran Gagal</strong><br>
                                    Silakan coba lakukan pembayaran ulang.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Guest Info -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-user me-2"></i>Data Tamu
                    </h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td><strong>Nama</strong></td>
                            <td>: {{ $transaksi->tamu->nama_lengkap }}</td>
                        </tr>
                        <tr>
                            <td><strong>Email</strong></td>
                            <td>: {{ $transaksi->tamu->email }}</td>
                        </tr>
                        <tr>
                            <td><strong>No. Telp</strong></td>
                            <td>: {{ $transaksi->tamu->no_telp }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Actions -->
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-cogs me-2"></i>Aksi
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if($transaksi->is_paid == 'pending' && $transaksi->midtrans_order_id)
                            <a href="{{ route('payment.create', $transaksi->id_transaksi) }}" 
                               class="btn btn-success">
                                <i class="fas fa-credit-card me-2"></i>Bayar Sekarang
                            </a>
                        @endif
                        
                        @if($transaksi->is_paid == 'paid')
                            <button class="btn btn-info" onclick="window.print()">
                                <i class="fas fa-print me-2"></i>Cetak Bukti
                            </button>
                        @endif
                        
                        <a href="{{ route('tamu.dashboard') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
