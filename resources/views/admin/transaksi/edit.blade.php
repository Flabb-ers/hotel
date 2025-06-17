@extends('admin.layouts.app')

@section('title', 'Edit Transaksi')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Transaksi</h1>
        <a href="{{ route('admin.transaksi.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-white">
                    <h6 class="m-0 font-weight-bold text-primary">Form Edit Transaksi</h6>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Info Transaksi -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="fw-bold">Informasi Tamu</h6>
                            <p class="mb-1"><strong>Nama:</strong> {{ $transaksi->tamu->nama_lengkap }}</p>
                            <p class="mb-1"><strong>Email:</strong> {{ $transaksi->tamu->email }}</p>
                            <p class="mb-0"><strong>No. Telp:</strong> {{ $transaksi->tamu->no_telp }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold">Informasi Kamar</h6>
                            <p class="mb-1"><strong>Kamar:</strong> {{ $transaksi->kamar->nomer_kamar }}</p>
                            <p class="mb-1"><strong>Tipe:</strong> {{ $transaksi->kamar->tipeKamar->nama_tipe }}</p>
                            <p class="mb-0"><strong>Harga:</strong> Rp {{ number_format($transaksi->kamar->harga, 0, ',', '.') }}/malam</p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <p class="mb-1"><strong>Check In:</strong></p>
                            <p>{{ \Carbon\Carbon::parse($transaksi->tgl_checkin)->format('d/m/Y') }}</p>
                        </div>
                        <div class="col-md-4">
                            <p class="mb-1"><strong>Check Out:</strong></p>
                            <p>{{ \Carbon\Carbon::parse($transaksi->tgl_checkout)->format('d/m/Y') }}</p>
                        </div>
                        <div class="col-md-4">
                            <p class="mb-1"><strong>Total Bayar:</strong></p>
                            <p class="fw-bold text-success">Rp {{ number_format($transaksi->sub_total, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <hr>

                    <form action="{{ route('admin.transaksi.update', $transaksi->id_transaksi) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="is_paid" class="form-label">Status Pembayaran</label>
                            <select class="form-select" id="is_paid" name="is_paid" required>
                                <option value="pending" {{ $transaksi->is_paid == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="paid" {{ $transaksi->is_paid == 'paid' ? 'selected' : '' }}>Lunas</option>
                                <option value="failed" {{ $transaksi->is_paid == 'failed' ? 'selected' : '' }}>Gagal</option>
                                <option value="cancelled" {{ $transaksi->is_paid == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                        </div>

                        @if($transaksi->midtrans_order_id)
                        <div class="mb-3">
                            <label class="form-label">Informasi Midtrans</label>
                            <div class="border rounded p-3 bg-light">
                                <p class="mb-1"><strong>Order ID:</strong> {{ $transaksi->midtrans_order_id }}</p>
                                @if($transaksi->midtrans_transaction_id)
                                    <p class="mb-0"><strong>Transaction ID:</strong> {{ $transaksi->midtrans_transaction_id }}</p>
                                @endif
                            </div>
                        </div>
                        @endif

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Update Status
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
