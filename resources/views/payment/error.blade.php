@extends('frontend.layouts.app')

@section('title', 'Pembayaran Gagal - Hotel Maju Makmur')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card text-center">
                <div class="card-body py-5">
                    <div class="mb-4">
                        <i class="fas fa-times-circle fa-5x text-danger"></i>
                    </div>
                    
                    <h2 class="text-danger fw-bold mb-3">Pembayaran Gagal</h2>
                    <p class="text-muted mb-4">
                        Maaf, terjadi kesalahan dalam proses pembayaran. Silakan coba lagi atau hubungi kami untuk bantuan.
                    </p>

                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong>Informasi:</strong><br>
                        - Pembayaran tidak berhasil diproses<br>
                        - Kamar belum dibooking<br>
                        - Tidak ada biaya yang dikenakan
                    </div>

                    <div class="d-grid gap-2">
                        <a href="{{ route('home') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-redo me-2"></i>Coba Booking Lagi
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
