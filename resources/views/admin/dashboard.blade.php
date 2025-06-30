@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1">Welcome back, Admin User!</h4>
                            <p class="mb-0 opacity-75">Here's what's happening with your hotel today.</p>
                        </div>
                        <div>
                            <a href="{{ route('admin.dashboard.export') }}" id="downloadReportBtn" class="btn btn-light">
                                <i class="fas fa-download me-2"></i>
                                Download Report
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card stats-card">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center mb-3">
                        <div class="bg-white bg-opacity-20 rounded-circle p-3">
                            <i class="fas fa-bed fa-2x"></i>
                        </div>
                    </div>
                    <h3 class="mb-1">{{ $stats['total_kamar'] }}</h3>
                    <p class="mb-0">Jumlah Kamar</p>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card stats-card green">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center mb-3">
                        <div class="bg-white bg-opacity-20 rounded-circle p-3">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                    <h3 class="mb-1">{{ $stats['kamar_kosong'] }}</h3>
                    <p class="mb-0">Kamar Kosong</p>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card stats-card orange">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center mb-3">
                        <div class="bg-white bg-opacity-20 rounded-circle p-3">
                            <i class="fas fa-door-closed fa-2x"></i>
                        </div>
                    </div>
                    <h3 class="mb-1">{{ $stats['kamar_terisi'] }}</h3>
                    <p class="mb-0">Kamar Terisi</p>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card stats-card purple">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center mb-3">
                        <div class="bg-white bg-opacity-20 rounded-circle p-3">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                    </div>
                    <h3 class="mb-1">{{ $stats['total_tamu'] }}</h3>
                    <p class="mb-0">Jumlah Tamu</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Guests Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Daftar Tamu Terbaru</h5>
                        <form action="{{ route('admin.dashboard') }}" method="GET" class="d-flex gap-2">
                            <input type="text" name="search" class="form-control" placeholder="Cari nama/email..."
                                value="{{ request('search') }}">
                            <input type="date" name="tanggal" class="form-control" style="width: auto;"
                                value="{{ request('tanggal') }}">
                            <button type="submit" class="btn btn-primary" title="Filter">
                                <i class="fas fa-search"></i>
                            </button>
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary" title="Reset Filter">
                                <i class="fas fa-sync-alt"></i>
                            </a>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <div id="guestTableContainer">
                        @include('admin._dashboard_guest_table')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.querySelector('input[name="search"]');
            const dateInput = document.querySelector('input[name="tanggal"]');
            const guestTableContainer = document.getElementById('guestTableContainer');
            const form = searchInput.closest('form');
            let typingTimer;
            const doneTypingInterval = 500;

            function fetchData(url) {
                fetch(url, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    })
                    .then(response => response.text())
                    .then(html => {
                        guestTableContainer.innerHTML = html;
                    })
                    .catch(error => console.error('Error fetching data:', error));
            }

            function getFilteredUrl(page = 1) {
                const baseUrl = "{{ route('admin.dashboard') }}";
                const params = new URLSearchParams({
                    page: page,
                    search: searchInput.value,
                    tanggal: dateInput.value,
                });
                return `${baseUrl}?${params.toString()}`;
            }

            form.addEventListener('submit', function(e) {
                e.preventDefault();
                fetchData(getFilteredUrl());
            });

            searchInput.addEventListener('keyup', function() {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(() => {
                    fetchData(getFilteredUrl());
                }, doneTypingInterval);
            });

            dateInput.addEventListener('change', function() {
                fetchData(getFilteredUrl());
            });

            guestTableContainer.addEventListener('click', function(event) {
                if (event.target.tagName === 'A' && event.target.closest('.pagination')) {
                    event.preventDefault();
                    const url = event.target.href;
                    if (url) {
                        fetchData(url);
                    }
                }
            });

            // Update download link with current filters
            const downloadBtn = document.getElementById('downloadReportBtn');
            const baseUrl = downloadBtn.href;

            function updateDownloadLink() {
                const params = new URLSearchParams({
                    search: searchInput.value,
                    tanggal: dateInput.value,
                });
                downloadBtn.href = `${baseUrl}?${params.toString()}`;
            }

            searchInput.addEventListener('keyup', updateDownloadLink);
            dateInput.addEventListener('change', updateDownloadLink);
            updateDownloadLink(); // Initial call
        });
    </script>
@endpush
