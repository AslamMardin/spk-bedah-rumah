@extends('layouts.app')
@section('title', 'Detail Matriks Normalisasi')

@section('content')
<div class="mb-4">
    <a href="{{ route('hasil.index') }}" class="text-decoration-none text-muted small">
        <i class="bi bi-arrow-left me-1"></i>Kembali ke Hasil Ranking
    </a>
    <h4 class="fw-bold mt-2 mb-0">Detail Matriks Normalisasi</h4>
    <small class="text-muted">Transparansi proses perhitungan SAW — nilai mentah dan normalisasi tiap alternatif</small>
</div>

{{-- Tabel Bobot Kriteria --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white fw-bold border-0 pt-3">
        <i class="bi bi-sliders me-2 text-primary"></i>Bobot Kriteria
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sm align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        @foreach($kriteria as $k)
                            <th class="text-center">
                                <span class="badge bg-secondary">{{ $k->kode }}</span><br>
                                <small>{{ $k->nama }}</small><br>
                                <small class="text-{{ $k->tipe_color }}">({{ $k->tipe_label }})</small><br>
                                <strong>{{ $k->bobot }}%</strong>
                            </th>
                        @endforeach
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

{{-- Matriks Nilai Mentah --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white fw-bold border-0 pt-3">
        <i class="bi bi-grid me-2 text-primary"></i>Matriks Keputusan (Nilai Mentah X<sub>ij</sub>)
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-sm align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Alternatif</th>
                        @foreach($kriteria as $k)
                            <th class="text-center">{{ $k->kode }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($detail as $pendudukId => $row)
                        <tr>
                            <td class="fw-semibold">{{ $row['nama'] }}<br><small class="text-muted">{{ $row['nik'] }}</small></td>
                            @foreach($kriteria as $k)
                                <td class="text-center">
                                    {{ $row['kriteria'][$k->id] ?? '—' }}
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Penjelasan Rumus --}}
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white fw-bold border-0 pt-3">
        <i class="bi bi-calculator me-2 text-primary"></i>Rumus Normalisasi SAW
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <div class="p-3 bg-success bg-opacity-10 rounded border border-success">
                    <h6 class="text-success fw-bold"><i class="bi bi-arrow-up-circle me-2"></i>Benefit</h6>
                    <p class="mb-1 font-monospace">r_ij = x_ij / max(x_j)</p>
                    <small class="text-muted">Semakin tinggi nilai = semakin baik</small>
                </div>
            </div>
            <div class="col-md-6">
                <div class="p-3 bg-danger bg-opacity-10 rounded border border-danger">
                    <h6 class="text-danger fw-bold"><i class="bi bi-arrow-down-circle me-2"></i>Cost</h6>
                    <p class="mb-1 font-monospace">r_ij = min(x_j) / x_ij</p>
                    <small class="text-muted">Semakin rendah nilai = semakin baik</small>
                </div>
            </div>
            <div class="col-12">
                <div class="p-3 bg-primary bg-opacity-10 rounded border border-primary">
                    <h6 class="text-primary fw-bold"><i class="bi bi-sigma me-2"></i>Skor Akhir</h6>
                    <p class="mb-1 font-monospace">V_i = Σ (w_j × r_ij)</p>
                    <small class="text-muted">w_j = bobot kriteria ke-j (dalam desimal) | r_ij = nilai normalisasi</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
