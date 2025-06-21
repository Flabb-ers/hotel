@extends('admin.layouts.app')

@section('title', 'Data Kamar')
@section('page-title', 'Data Kamar')

@section('content')
    <div class="row mb-3">
    </div>

    <div class="card">
        <div class="card-header bg-white">
            <div class="row align-items-center">
                <div class="col-md-6">
                </div>
                <div class="col-md-6">
                    <div class="d-flex gap-2 justify-content-end">
                        <select id="statusFilter" class="form-select" style="width: auto;">
                            <option value="">Semua Status</option>
                            <option value="tersedia">Tersedia</option>
                            <option value="terisi">Terisi</option>
                            <option value="maintenance">Maintenance</option>
                        </select>
                        <input type="text" id="searchInput" class="form-control" placeholder="Cari kamar..."
                            style="width: 200px;">
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div id="kamarTableContainer">
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
            let doneTypingInterval = 500;

            function fetchData(url) {
                fetch(url, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    })
                    .then(response => response.text())
                    .then(html => {
                        kamarTableContainer.innerHTML = html;
                    })
                    .catch(error => console.error('Error fetching data:', error));
            }

            function getFilteredUrl(page = 1) {
                let baseUrl = "{{ route('admin.kamar.index') }}";
                let params = new URLSearchParams({
                    page: page,
                    search: searchInput.value,
                    status: statusFilter.value,
                });
                return `${baseUrl}?${params.toString()}`;
            }

            searchInput.addEventListener('keyup', function() {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(() => {
                    fetchData(getFilteredUrl());
                }, doneTypingInterval);
            });

            statusFilter.addEventListener('change', function() {
                fetchData(getFilteredUrl());
            });

            kamarTableContainer.addEventListener('click', function(event) {
                if (event.target.tagName === 'A' && event.target.closest('.pagination')) {
                    event.preventDefault();
                    let url = event.target.href;
                    if (url) {
                        fetchData(url);
                    }
                }
            });
        });
    </script>
@endpush
