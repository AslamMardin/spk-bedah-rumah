@extends('layouts.app')
@section('title', 'Tambah Data Penduduk')

@section('content')
<div class="mb-4">
    <a href="{{ route('penduduk.index') }}" class="text-decoration-none text-muted small">
        <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar Penduduk
    </a>
    <h4 class="fw-bold mt-2 mb-0">Tambah Data Penduduk</h4>
    <small class="text-muted">Calon penerima bantuan bedah rumah</small>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('penduduk.store') }}" enctype="multipart/form-data">
                    @csrf

                    <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">
                        <i class="bi bi-person me-2"></i>Data Pribadi
                    </h6>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">NIK <span class="text-danger">*</span></label>
                            <input type="text" name="nik"
                                   class="form-control @error('nik') is-invalid @enderror"
                                   value="{{ old('nik') }}" maxlength="16"
                                   placeholder="16 digit NIK" required>
                            @error('nik') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama"
                                   class="form-control @error('nama') is-invalid @enderror"
                                   value="{{ old('nama') }}" required>
                            @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">No. HP</label>
                            <input type="text" name="no_hp"
                                   class="form-control @error('no_hp') is-invalid @enderror"
                                   value="{{ old('no_hp') }}" placeholder="08xxxxxxxxxx">
                            @error('no_hp') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Jumlah Anggota Keluarga <span class="text-danger">*</span></label>
                            <input type="number" name="jumlah_anggota_keluarga"
                                   class="form-control @error('jumlah_anggota_keluarga') is-invalid @enderror"
                                   value="{{ old('jumlah_anggota_keluarga', 1) }}" min="1" required>
                            @error('jumlah_anggota_keluarga') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <h6 class="fw-bold text-primary border-bottom pb-2 mb-3 mt-4">
                        <i class="bi bi-geo-alt me-2"></i>Alamat
                    </h6>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Alamat Lengkap <span class="text-danger">*</span></label>
                        <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror"
                                  rows="2" required>{{ old('alamat') }}</textarea>
                        @error('alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">RT</label>
                            <input type="text" name="rt"
                                   class="form-control @error('rt') is-invalid @enderror"
                                   value="{{ old('rt') }}" maxlength="5" placeholder="001">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">RW</label>
                            <input type="text" name="rw"
                                   class="form-control @error('rw') is-invalid @enderror"
                                   value="{{ old('rw') }}" maxlength="5" placeholder="001">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kelurahan <span class="text-danger">*</span></label>
                            <input type="text" name="kelurahan"
                                   class="form-control @error('kelurahan') is-invalid @enderror"
                                   value="{{ old('kelurahan') }}" required>
                            @error('kelurahan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kecamatan <span class="text-danger">*</span></label>
                            <input type="text" name="kecamatan"
                                   class="form-control @error('kecamatan') is-invalid @enderror"
                                   value="{{ old('kecamatan') }}" required>
                            @error('kecamatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <h6 class="fw-bold text-primary border-bottom pb-2 mb-3 mt-4">
                        <i class="bi bi-camera me-2"></i>Foto Kondisi Rumah
                    </h6>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Upload Foto Rumah</label>
                        <input type="file" name="foto_rumah" id="foto_rumah"
                               class="form-control @error('foto_rumah') is-invalid @enderror"
                               accept="image/jpg,image/jpeg,image/png">
                        <div class="form-text">Format: JPG/PNG. Maks. 2MB. Foto sebagai bukti kondisi rumah.</div>
                        @error('foto_rumah') <div class="invalid-feedback">{{ $message }}</div> @enderror

                        {{-- Preview foto --}}
                        <div id="previewContainer" class="mt-2 d-none">
                            <img id="previewImg" src="" class="img-thumbnail" style="max-height: 150px;">
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <i class="bi bi-save me-2"></i>Simpan Data Penduduk
                        </button>
                        <a href="{{ route('penduduk.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Preview foto sebelum upload
    document.getElementById('foto_rumah').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = (ev) => {
            document.getElementById('previewImg').src = ev.target.result;
            document.getElementById('previewContainer').classList.remove('d-none');
        };
        reader.readAsDataURL(file);
    });
</script>
@endpush
