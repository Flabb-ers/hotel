@extends('frontend.layouts.app')

@section('title', 'Hotel Maju Makmur - Selamat Datang')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-4 fw-bold mb-4">Selamat Datang</h1>
                <h2 class="h3 mb-4">Maju Makmur</h2>
                <p class="lead mb-0">Temukan Kenyamanan</p>
            </div>
        </div>
    </div>
</section>

<!-- Search Section -->
<section class="container">
    <div class="search-card">
        <form action="{{ route('search') }}" method="GET">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Check In</label>
                    <input type="date" class="form-control" name="checkin" 
                           value="{{ request('checkin', date('Y-m-d')) }}" 
                           min="{{ date('Y-m-d') }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Check Out</label>
                    <input type="date" class="form-control" name="checkout" 
                           value="{{ request('checkout', date('Y-m-d', strtotime('+1 day'))) }}" 
                           min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Tipe Kamar</label>
                    <select class="form-select" name="tipe">
                        <option value="">Semua Tipe</option>
                        @foreach($tipeKamar as $tipe)
                            <option value="{{ $tipe->id_tipe }}" {{ request('tipe') == $tipe->id_tipe ? 'selected' : '' }}>
                                {{ $tipe->nama_tipe }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-success w-100 btn-book">
                        <i class="fas fa-search me-2"></i>Cari
                    </button>
                </div>
            </div>
        </form>
    </div>
</section>

<!-- Rooms Section -->
<section class="container my-5" id="price">
    <div class="text-center mb-5">
        <h2 class="fw-bold">Kamar Tersedia</h2>
        <p class="text-muted">Pilih kamar yang sesuai dengan kebutuhan Anda</p>
    </div>

    @if($kamarTersedia->count() > 0)
        @foreach($kamarTersedia as $tipeId => $kamars)
            @php $tipe = $kamars->first()->tipeKamar; @endphp
            @php $kamarCount = $kamars->count(); @endphp
            
            <div class="row mb-5">
                <div class="col-12">
                    <div class="room-card card">
                        <div class="row g-0">
                            <div class="col-md-4">
                                @if($kamars->first()->thumbnail_kamar)
                                    <img src="{{ asset('uploads/kamar/' . $kamars->first()->thumbnail_kamar) }}" 
                                         class="room-image w-100" alt="{{ $tipe->nama_tipe }}">
                                @else
                                    <div class="room-image w-100 bg-light d-flex align-items-center justify-content-center">
                                        <i class="fas fa-bed fa-3x text-muted"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-8">
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <h4 class="card-title fw-bold">{{ $tipe->nama_tipe }}</h4>
                                            <div class="mb-2">
                                                @if($kamarCount > 5)
                                                    <span class="status-badge status-available">Available</span>
                                                @else
                                                    <span class="status-badge status-limited">Limited</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <div class="price-tag">
                                                Rp. {{ number_format($kamars->first()->harga, 0, ',', '.') }}
                                            </div>
                                            <small class="text-muted">Per Malam</small>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <p class="mb-2">
                                                <i class="fas fa-bed feature-icon"></i>
                                                {{ $tipe->nama_tipe }}
                                            </p>
                                            <p class="mb-2">
                                                <i class="fas fa-users feature-icon"></i>
                                                Capacity {{ $kamars->first()->kapasitas ?? 2 }}
                                            </p>
                                        </div>
                                        <div class="col-md-6">
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

                                    @if($kamars->first()->fasilitas)
                                        <p class="text-muted mb-3">{{ $kamars->first()->fasilitas }}</p>
                                    @endif

                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">{{ $kamarCount }} kamar tersedia</small>
                                        <a href="{{ route('booking.show', $kamars->first()->id_kamar) }}"
                                           class="btn btn-success btn-book">
                                            Book Now
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="text-center py-5">
            <i class="fas fa-bed fa-3x text-muted mb-3"></i>
            <h4>Tidak ada kamar tersedia</h4>
            <p class="text-muted">Silakan coba tanggal lain atau hubungi kami untuk informasi lebih lanjut.</p>
        </div>
    @endif
</section>

<!-- About Section -->
<section class="bg-white py-5" id="about">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <h3 class="fw-bold mb-4">Tentang Kami</h3>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
            </div>
            <div class="col-lg-6">
                <div class="row">
                    <div class="col-6 mb-3">
                        <div class="text-center p-3">
                            <i class="fas fa-bed fa-2x text-success mb-2"></i>
                            <h5>Kamar Nyaman</h5>
                            <p class="small text-muted">Kamar dengan fasilitas lengkap</p>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="text-center p-3">
                            <i class="fas fa-wifi fa-2x text-success mb-2"></i>
                            <h5>Free WiFi</h5>
                            <p class="small text-muted">Internet cepat 24 jam</p>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="text-center p-3">
                            <i class="fas fa-car fa-2x text-success mb-2"></i>
                            <h5>Parkir Gratis</h5>
                            <p class="small text-muted">Area parkir yang aman</p>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="text-center p-3">
                            <i class="fas fa-concierge-bell fa-2x text-success mb-2"></i>
                            <h5>Room Service</h5>
                            <p class="small text-muted">Layanan 24 jam</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Set minimum checkout date based on checkin
    document.querySelector('input[name="checkin"]').addEventListener('change', function() {
        const checkinDate = new Date(this.value);
        const checkoutDate = new Date(checkinDate);
        checkoutDate.setDate(checkoutDate.getDate() + 1);
        
        const checkoutInput = document.querySelector('input[name="checkout"]');
        checkoutInput.min = checkoutDate.toISOString().split('T')[0];
        
        if (new Date(checkoutInput.value) <= checkinDate) {
            checkoutInput.value = checkoutDate.toISOString().split('T')[0];
        }
    });
</script>
@endpush
