@extends('frontend.layouts.app')

@section('title', 'Pembayaran Pending - Hotel Maju Makmur')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card text-center">
                <div class="card-body py-5">
                    <div class="mb-4">
                        <i class="fas fa-clock fa-5x text-warning"></i>
                    </div>
                    
                    <h2 class="text-warning fw-bold mb-3">Pembayaran Pending</h2>
                    <p class="text-muted mb-4">
                        Pembayaran Anda sedang diproses. Silakan tunggu konfirmasi atau selesaikan pembayaran Anda.
                    </p>

                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Perhatian:</strong><br>
                        - Pembayaran belum selesai<br>
                        - Kamar belum dikonfirmasi<br>
                        - Silakan selesaikan pembayaran dalam 24 jam
                    </div>

                    <div class="d-grid gap-2">
                        <a href="{{ route('home') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-home me-2"></i>Kembali ke Beranda
                        </a>
                        <a href="mailto:info@majumakmur.com" class="btn btn-outline-secondary">
                            <i class="fas fa-envelope me-2"></i>Hubungi Kami
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
