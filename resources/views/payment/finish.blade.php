@extends('frontend.layouts.app')

@section('title', 'Pembayaran Berhasil - Hotel Maju Makmur')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card text-center">
                <div class="card-body py-5">
                    <div class="mb-4">
                        <i class="fas fa-check-circle fa-5x text-success"></i>
                    </div>
                    
                    <h2 class="text-success fw-bold mb-3">Pembayaran Berhasil!</h2>
                    <p class="text-muted mb-4">
                        Terima kasih atas pembayaran Anda. Booking kamar telah dikonfirmasi.
                    </p>

                    @if($transaksi)
                        <div class="border rounded p-3 mb-4 bg-light">
                            <h6 class="fw-bold mb-3">Detail Booking</h6>
                            <div class="row text-start">
                                <div class="col-6">
                                    <small class="text-muted">Nama Tamu:</small>
                                    <div class="fw-semibold">{{ $transaksi->tamu->nama_lengkap }}</div>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Kamar:</small>
                                    <div class="fw-semibold">{{ $transaksi->kamar->tipeKamar->nama_tipe }}</div>
                                </div>
                                <div class="col-6 mt-2">
                                    <small class="text-muted">Check In:</small>
                                    <div class="fw-semibold">{{ \Carbon\Carbon::parse($transaksi->tgl_checkin)->format('d/m/Y') }}</div>
                                </div>
                                <div class="col-6 mt-2">
                                    <small class="text-muted">Check Out:</small>
                                    <div class="fw-semibold">{{ \Carbon\Carbon::parse($transaksi->tgl_checkout)->format('d/m/Y') }}</div>
                                </div>
                                <div class="col-12 mt-2">
                                    <small class="text-muted">Total Bayar:</small>
                                    <div class="fw-bold text-success h5">Rp. {{ number_format($transaksi->sub_total, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Informasi Penting:</strong><br>
                        - Silakan datang ke hotel pada tanggal check-in<br>
                        - Bawa identitas diri yang valid<br>
                        - Konfirmasi booking akan dikirim ke email Anda
                    </div>

                    <div class="d-grid gap-2">
                        <a href="{{ route('home') }}" class="btn btn-success btn-lg">
                            <i class="fas fa-home me-2"></i>Kembali ke Beranda
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
