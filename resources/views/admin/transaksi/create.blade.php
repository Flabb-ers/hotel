@extends('admin.layouts.app')

@section('title', 'Tambah Transaksi')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tambah Transaksi</h1>
        <a href="{{ route('admin.transaksi.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-white">
                    <h6 class="m-0 font-weight-bold text-primary">Form Tambah Transaksi</h6>
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

                    <form action="{{ route('admin.transaksi.store') }}" method="POST" id="transaksiForm">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="id_tamu" class="form-label">Tamu</label>
                                    <select class="form-select" id="id_tamu" name="id_tamu" required>
                                        <option value="">Pilih Tamu</option>
                                        @foreach($tamu as $item)
                                            <option value="{{ $item->id_tamu }}" {{ old('id_tamu') == $item->id_tamu ? 'selected' : '' }}>
                                                {{ $item->nama_lengkap }} - {{ $item->email }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="id_kamar" class="form-label">Kamar</label>
                                    <select class="form-select" id="id_kamar" name="id_kamar" required>
                                        <option value="">Pilih Kamar</option>
                                        @foreach($kamar as $item)
                                            <option value="{{ $item->id_kamar }}" 
                                                    data-harga="{{ $item->harga }}"
                                                    {{ old('id_kamar') == $item->id_kamar ? 'selected' : '' }}>
                                                {{ $item->nomer_kamar }} - {{ $item->tipeKamar->nama_tipe }} 
                                                (Rp {{ number_format($item->harga, 0, ',', '.') }}/malam)
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tgl_checkin" class="form-label">Tanggal Check In</label>
                                    <input type="date" class="form-control" id="tgl_checkin" name="tgl_checkin" 
                                           value="{{ old('tgl_checkin', date('Y-m-d')) }}" 
                                           min="{{ date('Y-m-d') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tgl_checkout" class="form-label">Tanggal Check Out</label>
                                    <input type="date" class="form-control" id="tgl_checkout" name="tgl_checkout" 
                                           value="{{ old('tgl_checkout', date('Y-m-d', strtotime('+1 day'))) }}" 
                                           min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                                </div>
                            </div>
                        </div>

                        <!-- Ringkasan -->
                        <div class="border-top pt-3 mb-3">
                            <h6 class="fw-bold">Ringkasan Booking</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="d-flex justify-content-between">
                                        <span>Harga per malam:</span>
                                        <span id="hargaPerMalam">Rp 0</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>Total malam:</span>
                                        <span id="totalMalam">0</span>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between fw-bold">
                                        <span>Total Bayar:</span>
                                        <span id="totalBayar" class="text-success">Rp 0</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="reset" class="btn btn-secondary me-2">Reset</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const kamarSelect = document.getElementById('id_kamar');
    const checkinInput = document.getElementById('tgl_checkin');
    const checkoutInput = document.getElementById('tgl_checkout');

    function calculateTotal() {
        const selectedKamar = kamarSelect.options[kamarSelect.selectedIndex];
        const harga = selectedKamar.dataset.harga || 0;
        const checkin = new Date(checkinInput.value);
        const checkout = new Date(checkoutInput.value);

        if (checkin && checkout && checkout > checkin) {
            const timeDiff = checkout.getTime() - checkin.getTime();
            const daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24));
            const total = daysDiff * harga;

            document.getElementById('hargaPerMalam').textContent = 'Rp ' + parseInt(harga).toLocaleString('id-ID');
            document.getElementById('totalMalam').textContent = daysDiff;
            document.getElementById('totalBayar').textContent = 'Rp ' + total.toLocaleString('id-ID');
        }
    }

    // Set minimum checkout date
    checkinInput.addEventListener('change', function() {
        const checkinDate = new Date(this.value);
        const checkoutDate = new Date(checkinDate);
        checkoutDate.setDate(checkoutDate.getDate() + 1);

        checkoutInput.min = checkoutDate.toISOString().split('T')[0];

        if (new Date(checkoutInput.value) <= checkinDate) {
            checkoutInput.value = checkoutDate.toISOString().split('T')[0];
        }

        calculateTotal();
    });

    kamarSelect.addEventListener('change', calculateTotal);
    checkoutInput.addEventListener('change', calculateTotal);

    // Initial calculation
    calculateTotal();
});
</script>
@endpush
@endsection
