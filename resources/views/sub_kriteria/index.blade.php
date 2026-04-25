@extends('layouts.app')
@section('title', 'Sub Kriteria — ' . $kriterium->nama)

@section('content')
<div class="mb-4">
    <a href="{{ route('kriteria.index') }}" class="text-decoration-none text-muted small">
        <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar Kriteria
    </a>
    <h4 class="fw-bold mt-2 mb-0">Sub Kriteria</h4>
    <small class="text-muted">
        Skala nilai untuk <span class="badge bg-secondary">{{ $kriterium->kode }}</span>
        {{ $kriterium->nama }}
        <span class="badge bg-{{ $kriterium->tipe_color }} ms-1">{{ $kriterium->tipe_label }}</span>
        <span class="badge bg-light text-dark ms-1">Bobot: {{ $kriterium->bobot }}%</span>
    </small>
</div>

<div class="row g-4">
    {{-- Form Tambah Sub Kriteria --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm sticky-top" style="top: 80px;">
            <div class="card-header bg-white border-0 pt-3 fw-bold">
                <i class="bi bi-plus-circle me-2 text-primary"></i>Tambah Sub Kriteria
            </div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger py-2 small">
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('kriteria.sub.store', $kriterium) }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Label <span class="text-danger">*</span></label>
                        <input type="text" name="label"
                               class="form-control form-control-sm @error('label') is-invalid @enderror"
                               value="{{ old('label') }}"
                               placeholder="Contoh: Rusak Berat, < Rp 500.000 ...">
                        @error('label') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Nilai Skala <span class="text-danger">*</span></label>
                        <input type="number" name="nilai"
                               class="form-control form-control-sm @error('nilai') is-invalid @enderror"
                               value="{{ old('nilai') }}" min="1" max="10"
                               placeholder="1 – 10">
                        <div class="form-text small">
                            Nilai skala numerik (1 = paling rendah, 10 = paling tinggi).
                        </div>
                        @error('nilai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Keterangan</label>
                        <textarea name="keterangan" class="form-control form-control-sm" rows="2"
                                  placeholder="Opsional...">{{ old('keterangan') }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="bi bi-plus-lg me-1"></i>Tambahkan
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Daftar Sub Kriteria --}}
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 pt-3 px-4 d-flex align-items-center justify-content-between">
                <h6 class="fw-bold mb-0">
                    <i class="bi bi-list-ol me-2 text-primary"></i>Daftar Skala Nilai
                </h6>
                <span class="badge bg-secondary">{{ $subKriteria->count() }} sub kriteria</span>
            </div>
            <div class="card-body p-0">
                @if($subKriteria->isEmpty())
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-list-ul fs-2 d-block mb-2"></i>
                        Belum ada sub kriteria. Tambahkan di form sebelah kiri.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" style="width: 80px">Nilai</th>
                                    <th>Label / Deskripsi</th>
                                    <th>Keterangan</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($subKriteria as $sub)
                                    <tr>
                                        <td class="text-center">
                                            <span class="badge bg-dark fs-6 px-3">{{ $sub->nilai }}</span>
                                        </td>
                                        <td class="fw-semibold">{{ $sub->label }}</td>
                                        <td>
                                            <small class="text-muted">{{ $sub->keterangan ?? '—' }}</small>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm">
                                                {{-- Edit inline dengan modal --}}
                                                <button class="btn btn-outline-warning"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editModal{{ $sub->id }}">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <form method="POST"
                                                      action="{{ route('kriteria.sub.destroy', [$kriterium, $sub]) }}"
                                                      onsubmit="return confirm('Hapus sub kriteria ini?')">
                                                    @csrf @method('DELETE')
                                                    <button class="btn btn-outline-danger">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>

                                    {{-- Modal Edit --}}
                                    <div class="modal fade" id="editModal{{ $sub->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-sm">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h6 class="modal-title fw-bold">Edit Sub Kriteria</h6>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form method="POST"
                                                      action="{{ route('kriteria.sub.update', [$kriterium, $sub]) }}">
                                                    @csrf @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="mb-2">
                                                            <label class="form-label small fw-semibold">Label</label>
                                                            <input type="text" name="label"
                                                                   class="form-control form-control-sm"
                                                                   value="{{ $sub->label }}" required>
                                                        </div>
                                                        <div class="mb-2">
                                                            <label class="form-label small fw-semibold">Nilai</label>
                                                            <input type="number" name="nilai"
                                                                   class="form-control form-control-sm"
                                                                   value="{{ $sub->nilai }}" min="1" max="10" required>
                                                        </div>
                                                        <div class="mb-0">
                                                            <label class="form-label small fw-semibold">Keterangan</label>
                                                            <textarea name="keterangan" class="form-control form-control-sm"
                                                                      rows="2">{{ $sub->keterangan }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-sm btn-secondary"
                                                                data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-sm btn-warning">Simpan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
