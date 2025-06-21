@extends('admin.layouts.app')

@section('title', 'Data Kamar')
@section('page-title', 'Data Kamar')

@section('content')
    <div class="row mb-3">
        {{-- ... (bagian ini tetap sama) ... --}}
    </div>

    <div class="card">
        <div class="card-header bg-white">
            <div class="row align-items-center">
                <div class="col-md-6">
                    {{-- Dihapus h6 total dari sini, karena sudah ada di dalam parsial --}}
                </div>
                <div class="col-md-6">
                    {{-- HAPUS TAG FORM, BERI ID PADA INPUT --}}
                    <div class="d-flex gap-2 justify-content-end">
                        <select id="statusFilter" class="form-select" style="width: auto;">
                            <option value="">Semua Status</option>
                            <option value="tersedia">Tersedia</option>
                            <option value="terisi">Terisi</option>
                            <option value="maintenance">Maintenance</option>
                        </select>
                        <input type="text" id="searchInput" class="form-control" placeholder="Cari kamar..."
                            style="width: 200px;">
                        {{-- Tombol search tidak diperlukan lagi untuk live search --}}
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            {{-- BUAT CONTAINER UNTUK KONTEN DINAMIS --}}
            <div id="kamarTableContainer">
                {{-- Load tabel awal dari file parsial --}}
                @include('admin.kamar._kamar_table')
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let searchInput = document.getElementById('searchInput');
            let statusFilter = document.getElementById('statusFilter');
            let kamarTableContainer = document.getElementById('kamarTableContainer');
            let typingTimer;
            let doneTypingInterval = 500; // 0.5 detik

            // Fungsi utama untuk fetch data
            function fetchData(url) {
                fetch(url, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest', // Penting untuk dideteksi sebagai request AJAX oleh Laravel
                        },
                    })
                    .then(response => response.text())
                    .then(html => {
                        kamarTableContainer.innerHTML = html;
                    })
                    .catch(error => console.error('Error fetching data:', error));
            }

            // Fungsi untuk membangun URL berdasarkan filter
            function getFilteredUrl(page = 1) {
                let baseUrl = "{{ route('admin.kamar.index') }}";
                let params = new URLSearchParams({
                    page: page,
                    search: searchInput.value,
                    status: statusFilter.value,
                });
                return `${baseUrl}?${params.toString()}`;
            }

            // Event listener untuk input search (dengan debounce)
            searchInput.addEventListener('keyup', function() {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(() => {
                    fetchData(getFilteredUrl());
                }, doneTypingInterval);
            });

            // Event listener untuk filter status
            statusFilter.addEventListener('change', function() {
                fetchData(getFilteredUrl());
            });

            // Event listener untuk klik paginasi (event delegation)
            kamarTableContainer.addEventListener('click', function(event) {
                // Cek jika yang diklik adalah link paginasi
                if (event.target.tagName === 'A' && event.target.closest('.pagination')) {
                    event.preventDefault(); // Mencegah reload halaman
                    let url = event.target.href;
                    if (url) {
                        fetchData(url);
                    }
                }
            });
        });
    </script>
@endpush
