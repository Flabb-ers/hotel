@extends('admin.layouts.app')

@section('title', 'Detail Tamu')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Tamu</h1>
        <div>
            <a href="{{ route('admin.tamu.edit', $tamu->id_tamu) }}" class="btn btn-warning">
                <i class="fas fa-edit me-2"></i>Edit
            </a>
            <a href="{{ route('admin.tamu.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-header bg-white">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Tamu</h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td width="40%"><strong>Nama Lengkap</strong></td>
                            <td>: {{ $tamu->nama_lengkap }}</td>
                        </tr>
                        <tr>
                            <td><strong>Email</strong></td>
                            <td>: {{ $tamu->email }}</td>
                        </tr>
                        <tr>
                            <td><strong>No. Telepon</strong></td>
                            <td>: {{ $tamu->no_telp }}</td>
                        </tr>
                        <tr>
                            <td><strong>Alamat</strong></td>
                            <td>: {{ $tamu->alamat }}</td>
                        </tr>
                        <tr>
                            <td><strong>Tanda Pengenal</strong></td>
                            <td>: {{ \Carbon\Carbon::parse($tamu->tanda_pengenal)->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Terdaftar</strong></td>
                            <td>: {{ $tamu->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-header bg-white">
                    <h6 class="m-0 font-weight-bold text-primary">Riwayat Transaksi</h6>
                </div>
                <div class="card-body">
                    @if($tamu->transaksi->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Kamar</th>
                                        <th>Check In</th>
                                        <th>Check Out</th>
                                        <th>Status</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tamu->transaksi as $transaksi)
                                    <tr>
                                        <td>
                                            <div class="fw-bold">{{ $transaksi->kamar->nomer_kamar }}</div>
                                            <small class="text-muted">{{ $transaksi->kamar->tipeKamar->nama_tipe }}</small>
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($transaksi->tgl_checkin)->format('d/m/Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($transaksi->tgl_checkout)->format('d/m/Y') }}</td>
                                        <td>
                                            @if($transaksi->is_paid == 'paid')
                                                <span class="badge bg-success">Lunas</span>
                                            @elseif($transaksi->is_paid == 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @else
                                                <span class="badge bg-danger">Gagal</span>
                                            @endif
                                        </td>
                                        <td>Rp {{ number_format($transaksi->sub_total, 0, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <div class="text-muted">
                                <i class="fas fa-receipt fa-3x mb-3"></i>
                                <p>Belum ada transaksi</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
