@extends('layouts.app')
@section('title', 'Edit Kriteria — ' . $kriterium->nama)

@section('content')
<div class="mb-4">
    <a href="{{ route('kriteria.index') }}" class="text-decoration-none text-muted small">
        <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar Kriteria
    </a>
    <h4 class="fw-bold mt-2 mb-0">Edit Kriteria</h4>
    <small class="text-muted">
        <span class="badge bg-secondary">{{ $kriterium->kode }}</span> {{ $kriterium->nama }}
    </small>
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

                <form method="POST" action="{{ route('kriteria.update', $kriterium) }}">
                    @csrf @method('PUT')

                    {{-- Kode tidak bisa diubah --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kode Kriteria</label>
                        <input type="text" class="form-control bg-light" value="{{ $kriterium->kode }}" disabled>
                        <div class="form-text">Kode kriteria tidak dapat diubah.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Kriteria <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                               value="{{ old('nama', $kriterium->nama) }}" required>
                        @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tipe <span class="text-danger">*</span></label>
                            <select name="tipe" class="form-select @error('tipe') is-invalid @enderror" required>
                                <option value="benefit" {{ old('tipe', $kriterium->tipe) === 'benefit' ? 'selected' : '' }}>
                                    Benefit
                                </option>
                                <option value="cost" {{ old('tipe', $kriterium->tipe) === 'cost' ? 'selected' : '' }}>
                                    Cost
                                </option>
                            </select>
                            @error('tipe') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Bobot (%) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="bobot"
                                       class="form-control @error('bobot') is-invalid @enderror"
                                       value="{{ old('bobot', $kriterium->bobot) }}"
                                       min="0" max="100" step="0.01" required>
                                <span class="input-group-text">%</span>
                            </div>
                            <div class="form-text">Bobot lama: <strong>{{ $kriterium->bobot }}%</strong></div>
                            @error('bobot') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan', $kriterium->keterangan) }}</textarea>
                    </div>

                    {{-- Alasan perubahan bobot (untuk audit trail) --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            Alasan Perubahan Bobot
                            <span class="text-muted fw-normal small">(opsional, dicatat di audit trail)</span>
                        </label>
                        <textarea name="alasan" class="form-control @error('alasan') is-invalid @enderror"
                                  rows="2" placeholder="Jelaskan alasan perubahan bobot jika ada...">{{ old('alasan') }}</textarea>
                        @error('alasan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-warning flex-grow-1 text-dark fw-semibold">
                            <i class="bi bi-save me-2"></i>Perbarui Kriteria
                        </button>
                        <a href="{{ route('kriteria.audit', $kriterium) }}" class="btn btn-outline-secondary">
                            <i class="bi bi-clock-history"></i>
                        </a>
                        <a href="{{ route('kriteria.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
