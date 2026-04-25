@extends('layouts.app')
@section('title', 'Tambah Kriteria')

@section('content')
<div class="mb-4">
    <a href="{{ route('kriteria.index') }}" class="text-decoration-none text-muted small">
        <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar Kriteria
    </a>
    <h4 class="fw-bold mt-2 mb-0">Tambah Kriteria Baru</h4>
    <small class="text-muted">Tambahkan kriteria penilaian untuk metode SAW</small>
</div>

<div class="row justify-content-center">
    <div class="col-lg-7">
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

                <form method="POST" action="{{ route('kriteria.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kode Kriteria <span class="text-danger">*</span></label>
                        <input type="text" name="kode" class="form-control @error('kode') is-invalid @enderror"
                               value="{{ old('kode') }}" placeholder="Contoh: C1, C2, C3 ..."
                               maxlength="10" required>
                        <div class="form-text">Kode unik singkat untuk identifikasi kriteria.</div>
                        @error('kode') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Kriteria <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                               value="{{ old('nama') }}" placeholder="Contoh: Penghasilan Keluarga" required>
                        @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tipe <span class="text-danger">*</span></label>
                            <select name="tipe" class="form-select @error('tipe') is-invalid @enderror" required>
                                <option value="">-- Pilih Tipe --</option>
                                <option value="benefit" {{ old('tipe') === 'benefit' ? 'selected' : '' }}>
                                    Benefit (semakin tinggi = semakin baik)
                                </option>
                                <option value="cost" {{ old('tipe') === 'cost' ? 'selected' : '' }}>
                                    Cost (semakin rendah = semakin baik)
                                </option>
                            </select>
                            @error('tipe') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Bobot (%) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="bobot"
                                       class="form-control @error('bobot') is-invalid @enderror"
                                       value="{{ old('bobot') }}" min="0" max="100" step="0.01"
                                       placeholder="0 - 100" required>
                                <span class="input-group-text">%</span>
                            </div>
                            <div class="form-text">Total seluruh kriteria harus = 100%</div>
                            @error('bobot') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Keterangan</label>
                        <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror"
                                  rows="3" placeholder="Deskripsi singkat tentang kriteria ini...">{{ old('keterangan') }}</textarea>
                        @error('keterangan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Info tipe --}}
                    <div class="alert alert-info small mb-4">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Benefit:</strong> Nilai lebih tinggi = lebih diprioritaskan. Contoh: kondisi rumah rusak berat (nilai tinggi).<br>
                        <strong>Cost:</strong> Nilai lebih rendah = lebih diprioritaskan. Contoh: penghasilan rendah (lebih butuh bantuan).
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <i class="bi bi-save me-2"></i>Simpan Kriteria
                        </button>
                        <a href="{{ route('kriteria.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
