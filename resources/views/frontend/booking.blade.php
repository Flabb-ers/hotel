@extends('frontend.layouts.app')

@section('title', 'Booking - ' . $kamar->tipeKamar->nama_tipe)

@section('content')
<div class="container my-5">
    <div class="row">
        <!-- Room Details -->
        <div class="col-lg-8">
            <div class="card room-card mb-4">
                <div class="row g-0">
                    <div class="col-md-6">
                        @if($kamar->thumbnail_kamar)
                            <img src="{{ asset('uploads/kamar/' . $kamar->thumbnail_kamar) }}" 
                                 class="room-image w-100" alt="{{ $kamar->tipeKamar->nama_tipe }}">
                        @else
                            <div class="room-image w-100 bg-light d-flex align-items-center justify-content-center">
                                <i class="fas fa-bed fa-3x text-muted"></i>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <div class="card-body p-4">
                            <h3 class="card-title fw-bold">{{ $kamar->tipeKamar->nama_tipe }}</h3>
                            <p class="text-muted mb-3">Kamar {{ $kamar->nomer_kamar }}</p>
                            
                            <div class="mb-3">
                                <div class="price-tag d-inline-block">
                                    Rp. {{ number_format($kamar->harga, 0, ',', '.') }}
                                </div>
                                <small class="text-muted ms-2">Per Malam</small>
                            </div>

                            <div class="row mb-3">
                                <div class="col-6">
                                    <p class="mb-2">
                                        <i class="fas fa-bed feature-icon"></i>
                                        {{ $kamar->tipeKamar->nama_tipe }}
                                    </p>
                                    <p class="mb-2">
                                        <i class="fas fa-users feature-icon"></i>
                                        Capacity {{ $kamar->kapasitas ?? 2 }}
                                    </p>
                                </div>
                                <div class="col-6">
                                    <p class="mb-2">
                                        <i class="fas fa-car feature-icon"></i>
                                        Parking
                                    </p>
                                    <p class="mb-2">
                                        <i class="fas fa-wifi feature-icon"></i>
                                        Free Wi-Fi
                                    </p>
                                </div>
                            </div>

                            @if($kamar->fasilitas)
                                <p class="text-muted">{{ $kamar->fasilitas }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Booking Form -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-calendar-check me-2"></i>Booking Form</h5>
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

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('booking.store') }}" method="POST" id="bookingForm">
                        @csrf
                        <input type="hidden" name="kamar_id" value="{{ $kamar->id_kamar }}">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Lengkap</label>
                            <input type="text" class="form-control" name="nama_tamu" 
                                   value="{{ old('nama_tamu') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" class="form-control" name="email" 
                                   value="{{ old('email') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nomor HP</label>
                            <input type="tel" class="form-control" name="no_hp" 
                                   value="{{ old('no_hp') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Alamat</label>
                            <textarea class="form-control" name="alamat" rows="3" required>{{ old('alamat') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Check In</label>
                            <input type="date" class="form-control" name="checkin_date" 
                                   value="{{ old('checkin_date', request('checkin', date('Y-m-d'))) }}" 
                                   min="{{ date('Y-m-d') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Check Out</label>
                            <input type="date" class="form-control" name="checkout_date" 
                                   value="{{ old('checkout_date', request('checkout', date('Y-m-d', strtotime('+1 day')))) }}" 
                                   min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                        </div>

                        <!-- Booking Summary -->
                        <div class="border-top pt-3 mb-3">
                            <h6 class="fw-bold">Ringkasan Booking</h6>
                            <div class="d-flex justify-content-between">
                                <span>Kamar:</span>
                                <span>{{ $kamar->tipeKamar->nama_tipe }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Harga per malam:</span>
                                <span>Rp. {{ number_format($kamar->harga, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Total malam:</span>
                                <span id="totalNights">1</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between fw-bold">
                                <span>Total Bayar:</span>
                                <span id="totalAmount">Rp. {{ number_format($kamar->harga, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success w-100 btn-book">
                            <i class="fas fa-credit-card me-2"></i>Lanjut ke Pembayaran
                        </button>
                    </form>
                </div>
            </div>

            <!-- Back Button -->
            <div class="mt-3">
                <a href="{{ route('home') }}" class="btn btn-outline-secondary w-100">
                    <i class="fas fa-arrow-left me-2"></i>Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const pricePerNight = {{ $kamar->harga }};
    
    function calculateTotal() {
        const checkinDate = new Date(document.querySelector('input[name="checkin_date"]').value);
        const checkoutDate = new Date(document.querySelector('input[name="checkout_date"]').value);
        
        if (checkinDate && checkoutDate && checkoutDate > checkinDate) {
            const timeDiff = checkoutDate.getTime() - checkinDate.getTime();
            const daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24));
            const totalAmount = daysDiff * pricePerNight;
            
            document.getElementById('totalNights').textContent = daysDiff;
            document.getElementById('totalAmount').textContent = 'Rp. ' + totalAmount.toLocaleString('id-ID');
        }
    }
    
    // Set minimum checkout date based on checkin
    document.querySelector('input[name="checkin_date"]').addEventListener('change', function() {
        const checkinDate = new Date(this.value);
        const checkoutDate = new Date(checkinDate);
        checkoutDate.setDate(checkoutDate.getDate() + 1);
        
        const checkoutInput = document.querySelector('input[name="checkout_date"]');
        checkoutInput.min = checkoutDate.toISOString().split('T')[0];
        
        if (new Date(checkoutInput.value) <= checkinDate) {
            checkoutInput.value = checkoutDate.toISOString().split('T')[0];
        }
        
        calculateTotal();
    });
    
    document.querySelector('input[name="checkout_date"]').addEventListener('change', calculateTotal);
    
    // Calculate on page load
    calculateTotal();
</script>
@endpush
