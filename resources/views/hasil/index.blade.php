@extends('layouts.app')
@section('title', 'Hasil Ranking SAW')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-0">Hasil Ranking SAW</h4>
        <small class="text-muted">Perankingan calon penerima bantuan bedah rumah</small>
    </div>

    <div class="d-flex gap-2 flex-wrap">
        {{-- Hitung SAW (admin & evaluator) --}}
        @if(!auth()->user()->isPimpinan())
            <form method="POST" action="{{ route('hasil.hitung') }}">
                @csrf
                <button class="btn btn-primary"
                        onclick="return confirm('Jalankan ulang perhitungan SAW? Data ranking lama akan diperbarui.')">
                    <i class="bi bi-calculator me-1"></i>Hitung Ulang SAW
                </button>
            </form>
        @endif

        {{-- Export (semua role) --}}
        @if($sudahDihitung)
            <a href="{{ route('hasil.export.pdf') }}" class="btn btn-outline-danger">
                <i class="bi bi-file-pdf me-1"></i>PDF
            </a>
            <a href="{{ route('hasil.export.excel') }}" class="btn btn-outline-success">
                <i class="bi bi-file-excel me-1"></i>Excel
            </a>
        @endif
    </div>
</div>

@if($hasil->isEmpty())
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
            <i class="bi bi-calculator fs-1 text-muted d-block mb-3"></i>
            <h5 class="text-muted">Belum ada hasil perhitungan</h5>
            <p class="text-muted mb-4">Pastikan semua penduduk sudah dinilai, lalu klik tombol "Hitung SAW".</p>
            @if(!auth()->user()->isPimpinan())
                <form method="POST" action="{{ route('hasil.hitung') }}">
                    @csrf
                    <button class="btn btn-primary">
                        <i class="bi bi-play-circle me-2"></i>Mulai Perhitungan SAW
                    </button>
                </form>
            @endif
        </div>
    </div>
@else
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center">Ranking</th>
                            <th>Nama Penduduk</th>
                            <th>NIK</th>
                            <th>Wilayah</th>
                            <th class="text-center">Nilai SAW (Vi)</th>
                            <th class="text-center">Rekomendasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($hasil as $h)
                            <tr class="{{ $h->rekomendasi === 'layak' ? 'table-success bg-opacity-25' : '' }}">
                                <td class="text-center">
                                   
                                    <span class="fw-bold">#{{ $h->ranking }}</span>
                                </td>
                                <td class="fw-semibold">{{ $h->penduduk->nama }}</td>
                                <td><code class="small">{{ $h->penduduk->nik }}</code></td>
                                <td>
                                    {{ $h->penduduk->kelurahan }},
                                    <span class="text-muted">{{ $h->penduduk->kecamatan }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="fw-bold fs-6 text-primary">
                                        {{ number_format($h->nilai_saw, 4) }}
                                    </span>
                                </td>
                               <td class="text-center">
    <div class="d-flex justify-content-center align-items-center gap-1">
        <span class="badge bg-{{ $h->rekomendasi_color }} fs-6 px-3 py-2">
            {{ $h->rekomendasi_label }}
        </span>

        <a href="{{ route('penduduk.show', $h->penduduk->id) }}" 
           class="btn btn-info btn-sm">
            <i class="bi bi-eye"></i>
        </a>
    </div>
</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($hasil->hasPages())
                <div class="p-3 border-top">
                        {{ $hasil->links() }}
                </div>
            @endif
        </div>
    </div>

    <div class="mt-3 d-flex justify-content-end">
        <a href="{{ route('hasil.detail') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-table me-1"></i>Lihat Detail Matriks Normalisasi
        </a>
    </div>
@endif
@endsection
