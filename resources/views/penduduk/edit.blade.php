@extends('layouts.app')
@section('title', 'Edit — ' . $penduduk->nama)

@section('content')
<div class="mb-4">
    <a href="{{ route('penduduk.index') }}" class="text-decoration-none text-muted small">
        <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar Penduduk
    </a>
    <h4 class="fw-bold mt-2 mb-0">Edit Data Penduduk</h4>
    <small class="text-muted">{{ $penduduk->nama }} — NIK: {{ $penduduk->nik }}</small>
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

                <form method="POST" action="{{ route('penduduk.update', $penduduk) }}" enctype="multipart/form-data">
                    @csrf @method('PUT')

                    <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">
                        <i class="bi bi-person me-2"></i>Data Pribadi
                    </h6>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">NIK <span class="text-danger">*</span></label>
                            <input type="text" name="nik"
                                   class="form-control @error('nik') is-invalid @enderror"
                                   value="{{ old('nik', $penduduk->nik) }}" maxlength="16" required>
                            @error('nik') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama"
                                   class="form-control @error('nama') is-invalid @enderror"
                                   value="{{ old('nama', $penduduk->nama) }}" required>
                            @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">No. HP</label>
                            <input type="text" name="no_hp" class="form-control"
                                   value="{{ old('no_hp', $penduduk->no_hp) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Jumlah Anggota Keluarga <span class="text-danger">*</span></label>
                            <input type="number" name="jumlah_anggota_keluarga" class="form-control"
                                   value="{{ old('jumlah_anggota_keluarga', $penduduk->jumlah_anggota_keluarga) }}"
                                   min="1" required>
                        </div>
                    </div>

                    <h6 class="fw-bold text-primary border-bottom pb-2 mb-3 mt-4">
                        <i class="bi bi-geo-alt me-2"></i>Alamat
                    </h6>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Alamat Lengkap <span class="text-danger">*</span></label>
                        <textarea name="alamat" class="form-control" rows="2" required>{{ old('alamat', $penduduk->alamat) }}</textarea>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">RT</label>
                            <input type="text" name="rt" class="form-control"
                                   value="{{ old('rt', $penduduk->rt) }}" maxlength="5">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">RW</label>
                            <input type="text" name="rw" class="form-control"
                                   value="{{ old('rw', $penduduk->rw) }}" maxlength="5">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Desa <span class="text-danger">*</span></label>
                            <input type="text" name="kelurahan" class="form-control"
                                   value="{{ old('kelurahan', $penduduk->kelurahan) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kecamatan <span class="text-danger">*</span></label>
                            <input type="text" name="kecamatan" class="form-control"
                                   value="{{ old('kecamatan', $penduduk->kecamatan) }}" required>
                        </div>
                    </div>

                    <h6 class="fw-bold text-primary border-bottom pb-2 mb-3 mt-4">
                        <i class="bi bi-camera me-2"></i>Foto Rumah
                    </h6>

                    <div class="mb-4">
                        @if($penduduk->foto_rumah)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $penduduk->foto_rumah) }}"
                                     class="img-thumbnail" style="max-height: 150px;" alt="Foto Rumah">
                                <div class="form-text">Foto saat ini. Upload baru untuk mengganti.</div>
                            </div>
                        @endif
                        <input type="file" name="foto_rumah" class="form-control"
                               accept="image/jpg,image/jpeg,image/png">
                        <div class="form-text">Format: JPG/PNG. Maks. 2MB. Kosongkan jika tidak ingin mengubah.</div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-warning flex-grow-1 fw-semibold text-dark">
                            <i class="bi bi-save me-2"></i>Perbarui Data
                        </button>
                        <a href="{{ route('penduduk.show', $penduduk) }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
