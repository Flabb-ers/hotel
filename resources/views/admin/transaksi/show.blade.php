@extends('admin.layouts.app')

@section('title', 'Detail Transaksi')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Transaksi</h1>
        <div>
            <a href="{{ route('admin.transaksi.edit', $transaksi->id_transaksi) }}" class="btn btn-warning">
                <i class="fas fa-edit me-2"></i>Edit
            </a>
            <a href="{{ route('admin.transaksi.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-white">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Transaksi</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3">Data Tamu</h6>
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td width="40%"><strong>Nama</strong></td>
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
                                <tr>
                                    <td><strong>Alamat</strong></td>
                                    <td>: {{ $transaksi->tamu->alamat }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3">Data Kamar</h6>
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
                                    <td>: {{ $transaksi->kamar->fasilitas ?: '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3">Detail Booking</h6>
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td width="40%"><strong>Tanggal Transaksi</strong></td>
                                    <td>: {{ \Carbon\Carbon::parse($transaksi->tgl_transaksi)->format('d/m/Y') }}</td>
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
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3">Status & Pembayaran</h6>
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td width="40%"><strong>Status Bayar</strong></td>
                                    <td>: 
                                        @if($transaksi->is_paid == 'paid')
                                            <span class="badge bg-success">Lunas</span>
                                        @elseif($transaksi->is_paid == 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif($transaksi->is_paid == 'failed')
                                            <span class="badge bg-danger">Gagal</span>
                                        @else
                                            <span class="badge bg-secondary">Dibatalkan</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Sub Total</strong></td>
                                    <td>: <span class="fw-bold text-success">Rp {{ number_format($transaksi->sub_total, 0, ',', '.') }}</span></td>
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
                    </div>

                    @if($transaksi->midtrans_response)
                    <hr>
                    <h6 class="fw-bold mb-3">Response Midtrans</h6>
                    <div class="border rounded p-3 bg-light">
                        <pre class="mb-0" style="font-size: 12px;">{{ json_encode(json_decode($transaksi->midtrans_response), JSON_PRETTY_PRINT) }}</pre>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header bg-white">
                    <h6 class="m-0 font-weight-bold text-primary">Aksi</h6>
                </div>
                <div class="card-body">
                    @if($transaksi->is_paid == 'pending')
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Pembayaran Pending</strong><br>
                            Transaksi ini masih menunggu pembayaran dari tamu.
                        </div>
                    @elseif($transaksi->is_paid == 'paid')
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>Pembayaran Lunas</strong><br>
                            Transaksi ini sudah dibayar lunas.
                        </div>
                    @endif

                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.transaksi.edit', $transaksi->id_transaksi) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>Edit Status
                        </a>
                        
                        @if($transaksi->is_paid == 'pending')
                        <button class="btn btn-success" onclick="updateStatus('paid')">
                            <i class="fas fa-check me-2"></i>Tandai Lunas
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function updateStatus(status) {
    if (confirm('Yakin ingin mengubah status transaksi?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.transaksi.update", $transaksi->id_transaksi) }}';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'PUT';
        
        const statusField = document.createElement('input');
        statusField.type = 'hidden';
        statusField.name = 'is_paid';
        statusField.value = status;
        
        form.appendChild(csrfToken);
        form.appendChild(methodField);
        form.appendChild(statusField);
        
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection
