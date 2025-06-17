@extends('admin.layouts.app')

@section('title', 'Tambah Kamar')
@section('page-title', 'Tambah Kamar')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Form Tambah Kamar</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.kamar.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nomer_kamar" class="form-label">Nomor Kamar <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('nomer_kamar') is-invalid @enderror" 
                                   id="nomer_kamar" 
                                   name="nomer_kamar" 
                                   value="{{ old('nomer_kamar') }}" 
                                   placeholder="Contoh: 101">
                            @error('nomer_kamar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="id_tipe_kamar" class="form-label">Tipe Kamar <span class="text-danger">*</span></label>
                            <select class="form-select @error('id_tipe_kamar') is-invalid @enderror" 
                                    id="id_tipe_kamar" 
                                    name="id_tipe_kamar">
                                <option value="">Pilih Tipe Kamar</option>
                                @foreach($tipeKamar as $tipe)
                                    <option value="{{ $tipe->id_tipe }}" 
                                            {{ old('id_tipe_kamar') == $tipe->id_tipe ? 'selected' : '' }}>
                                        {{ $tipe->nama_tipe }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_tipe_kamar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="jumlah_bed" class="form-label">Jumlah Bed <span class="text-danger">*</span></label>
                            <input type="number" 
                                   class="form-control @error('jumlah_bed') is-invalid @enderror" 
                                   id="jumlah_bed" 
                                   name="jumlah_bed" 
                                   value="{{ old('jumlah_bed') }}" 
                                   min="1"
                                   placeholder="1">
                            @error('jumlah_bed')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="harga" class="form-label">Harga per Malam <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" 
                                       class="form-control @error('harga') is-invalid @enderror" 
                                       id="harga" 
                                       name="harga" 
                                       value="{{ old('harga') }}" 
                                       min="0"
                                       placeholder="300000">
                                @error('harga')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" 
                                id="status" 
                                name="status">
                            <option value="">Pilih Status</option>
                            <option value="tersedia" {{ old('status') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                            <option value="terisi" {{ old('status') == 'terisi' ? 'selected' : '' }}>Terisi</option>
                            <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="fasilitas" class="form-label">Fasilitas</label>
                        <textarea class="form-control @error('fasilitas') is-invalid @enderror" 
                                  id="fasilitas" 
                                  name="fasilitas" 
                                  rows="3"
                                  placeholder="Contoh: AC, TV, WiFi, Kamar Mandi Dalam">{{ old('fasilitas') }}</textarea>
                        @error('fasilitas')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="thumbnail_kamar" class="form-label">Foto Kamar</label>
                        <input type="file" 
                               class="form-control @error('thumbnail_kamar') is-invalid @enderror" 
                               id="thumbnail_kamar" 
                               name="thumbnail_kamar"
                               accept="image/*">
                        <div class="form-text">Format: JPG, JPEG, PNG. Maksimal 2MB.</div>
                        @error('thumbnail_kamar')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>
                            Simpan
                        </button>
                        <a href="{{ route('admin.kamar.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>
                            Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-white">
                <h6 class="mb-0">Informasi</h6>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Tips:</strong>
                    <ul class="mb-0 mt-2">
                        <li>Nomor kamar harus unik</li>
                        <li>Gunakan foto dengan kualitas baik</li>
                        <li>Deskripsikan fasilitas dengan jelas</li>
                        <li>Pastikan harga sesuai dengan tipe kamar</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.getElementById('thumbnail_kamar').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            // You can add image preview here if needed
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endsection
