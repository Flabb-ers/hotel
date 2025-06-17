@extends('frontend.layouts.app')

@section('title', 'Pembayaran - Hotel Maju Makmur')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0"><i class="fas fa-credit-card me-2"></i>Pembayaran</h4>
                </div>
                <div class="card-body">
                    <!-- Booking Details -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="fw-bold">Detail Booking</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td>Nama Tamu:</td>
                                    <td>{{ $transaksi->tamu->nama_lengkap }}</td>
                                </tr>
                                <tr>
                                    <td>Email:</td>
                                    <td>{{ $transaksi->tamu->email }}</td>
                                </tr>
                                <tr>
                                    <td>No. HP:</td>
                                    <td>{{ $transaksi->tamu->no_telp }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold">Detail Kamar</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td>Kamar:</td>
                                    <td>{{ $transaksi->kamar->tipeKamar->nama_tipe }}</td>
                                </tr>
                                <tr>
                                    <td>No. Kamar:</td>
                                    <td>{{ $transaksi->kamar->nomer_kamar }}</td>
                                </tr>
                                <tr>
                                    <td>Check In:</td>
                                    <td>{{ \Carbon\Carbon::parse($transaksi->tgl_checkin)->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <td>Check Out:</td>
                                    <td>{{ \Carbon\Carbon::parse($transaksi->tgl_checkout)->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <td>Total Malam:</td>
                                    <td>{{ \Carbon\Carbon::parse($transaksi->tgl_checkin)->diffInDays(\Carbon\Carbon::parse($transaksi->tgl_checkout)) }} malam</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Payment Summary -->
                    <div class="border-top pt-3 mb-4">
                        <h6 class="fw-bold">Ringkasan Pembayaran</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="d-flex justify-content-between">
                                    <span>Harga per malam:</span>
                                    <span>Rp. {{ number_format($transaksi->kamar->harga, 0, ',', '.') }}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>Total malam:</span>
                                    <span>{{ \Carbon\Carbon::parse($transaksi->tgl_checkin)->diffInDays(\Carbon\Carbon::parse($transaksi->tgl_checkout)) }}</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between fw-bold h5">
                                    <span>Total Bayar:</span>
                                    <span class="text-success">Rp. {{ number_format($transaksi->sub_total, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Button -->
                    <div class="text-center">
                        <button id="pay-button" class="btn btn-success btn-lg px-5">
                            <i class="fas fa-credit-card me-2"></i>Bayar Sekarang
                        </button>
                        @if(isset($demoMode) && $demoMode)
                            <div class="alert alert-warning mt-3">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Demo Mode:</strong> Ini adalah simulasi pembayaran.
                                Untuk menggunakan Midtrans yang sesungguhnya, silakan konfigurasi API keys di file .env
                            </div>
                        @else
                            <p class="text-muted mt-2 small">
                                <i class="fas fa-shield-alt me-1"></i>
                                Pembayaran aman dengan Midtrans
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Back Button -->
            <div class="mt-3 text-center">
                <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@if(isset($demoMode) && $demoMode)
    <!-- Demo Mode Script -->
    <script>
        document.getElementById('pay-button').onclick = function() {
            // Simulate payment process
            if (confirm('Demo Mode: Simulasi pembayaran berhasil?')) {
                // Simulate successful payment
                const orderId = '{{ $transaksi->midtrans_order_id }}';
                window.location.href = '{{ route("payment.finish") }}?order_id=' + orderId;
            } else {
                // Simulate failed payment
                window.location.href = '{{ route("payment.error") }}';
            }
        };
    </script>
@else
    <!-- Real Midtrans Script -->
    @if(config('midtrans.is_production'))
        <script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ $clientKey }}"></script>
    @else
        <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ $clientKey }}"></script>
    @endif
    <script>
        document.getElementById('pay-button').onclick = function() {
            snap.pay('{{ $snapToken }}', {
                onSuccess: function(result) {
                    window.location.href = '{{ route("payment.finish") }}?order_id=' + result.order_id;
                },
                onPending: function(result) {
                    window.location.href = '{{ route("payment.unfinish") }}?order_id=' + result.order_id;
                },
                onError: function(result) {
                    window.location.href = '{{ route("payment.error") }}';
                },
                onClose: function() {
                    alert('Anda menutup popup pembayaran tanpa menyelesaikan pembayaran');
                }
            });
        };
    </script>
@endif
@endpush
