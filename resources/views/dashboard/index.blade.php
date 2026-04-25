@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-0">Dashboard</h4>
        <small class="text-muted">Selamat datang, {{ auth()->user()->name }}</small>
    </div>
    <span class="text-muted small">{{ now()->isoFormat('dddd, D MMMM Y') }}</span>
</div>

{{-- ── Kartu Statistik ── --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 p-3 bg-primary bg-opacity-10">
                    <i class="bi bi-people-fill fs-4 text-primary"></i>
                </div>
                <div>
                    <div class="fs-3 fw-bold">{{ $stats['total_penduduk'] }}</div>
                    <div class="text-muted small">Total Penduduk</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 p-3 bg-success bg-opacity-10">
                    <i class="bi bi-check2-circle fs-4 text-success"></i>
                </div>
                <div>
                    <div class="fs-3 fw-bold">{{ $stats['sudah_dinilai'] }}</div>
                    <div class="text-muted small">Sudah Dinilai</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 p-3 bg-warning bg-opacity-10">
                    <i class="bi bi-hourglass-split fs-4 text-warning"></i>
                </div>
                <div>
                    <div class="fs-3 fw-bold">{{ $stats['belum_dinilai'] }}</div>
                    <div class="text-muted small">Belum Dinilai</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 p-3 bg-info bg-opacity-10">
                    <i class="bi bi-sliders fs-4 text-info"></i>
                </div>
                <div>
                    <div class="fs-3 fw-bold">{{ $stats['total_kriteria'] }}</div>
                    <div class="text-muted small">Kriteria Aktif</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    {{-- Top 5 Ranking --}}
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 pb-0 pt-3 px-4">
                <h6 class="fw-bold mb-0"><i class="bi bi-trophy me-2 text-warning"></i>Top 5 Calon Penerima</h6>
            </div>
            <div class="card-body px-4">
                @forelse($topRanking as $h)
                    <div class="d-flex align-items-center py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <div class="fw-bold text-muted me-3" style="width:24px">#{{ $h->ranking }}</div>
                        <div class="flex-grow-1">
                            <div class="fw-semibold">{{ $h->penduduk->nama }}</div>
                            <small class="text-muted">{{ $h->penduduk->kelurahan }}, {{ $h->penduduk->kecamatan }}</small>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold text-primary">{{ number_format($h->nilai_saw, 4) }}</div>
                            <span class="badge bg-{{ $h->rekomendasi_color }}">{{ $h->rekomendasi_label }}</span>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-calculator fs-3 d-block mb-2"></i>
                        Belum ada hasil perhitungan SAW.
                        @if(!auth()->user()->isPimpinan())
                            <br><a href="{{ route('hasil.hitung') }}" class="btn btn-sm btn-primary mt-2"
                                   onclick="return confirm('Jalankan perhitungan SAW?')">
                                Hitung Sekarang
                            </a>
                        @endif
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Ringkasan Rekomendasi --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 pb-0 pt-3 px-4">
                <h6 class="fw-bold mb-0"><i class="bi bi-pie-chart me-2 text-info"></i>Rekomendasi</h6>
            </div>
            <div class="card-body px-4 d-flex flex-column justify-content-center">
                <div class="text-center mb-3">
                    <div class="fs-1 fw-bold text-success">{{ $stats['total_layak'] }}</div>
                    <div class="text-muted">Layak Menerima</div>
                </div>
                <hr>
                <div class="text-center">
                    <div class="fs-1 fw-bold text-danger">{{ $stats['total_tidak'] }}</div>
                    <div class="text-muted">Tidak Layak</div>
                </div>
                @if(!auth()->user()->isPimpinan())
                    <div class="mt-4">
                        <form method="POST" action="{{ route('hasil.hitung') }}">
                            @csrf
                            <button class="btn btn-primary w-100"
                                    onclick="return confirm('Jalankan ulang perhitungan SAW untuk semua data?')">
                                <i class="bi bi-calculator me-2"></i>Hitung Ulang SAW
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
