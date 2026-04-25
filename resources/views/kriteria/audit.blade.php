@extends('layouts.app')
@section('title', 'Audit Trail — ' . $kriterium->nama)

@section('content')
<div class="mb-4">
    <a href="{{ route('kriteria.index') }}" class="text-decoration-none text-muted small">
        <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar Kriteria
    </a>
    <h4 class="fw-bold mt-2 mb-0">Audit Trail Bobot</h4>
    <small class="text-muted">
        Riwayat perubahan bobot untuk kriteria
        <span class="badge bg-secondary">{{ $kriterium->kode }}</span>
        {{ $kriterium->nama }}
    </small>
</div>

{{-- Info Kriteria Saat Ini --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body d-flex align-items-center gap-4 flex-wrap">
        <div>
            <div class="text-muted small">Bobot Saat Ini</div>
            <div class="fs-4 fw-bold text-primary">{{ $kriterium->bobot }}%</div>
        </div>
        <div>
            <div class="text-muted small">Tipe</div>
            <span class="badge bg-{{ $kriterium->tipe_color }} fs-6">{{ $kriterium->tipe_label }}</span>
        </div>
        <div>
            <div class="text-muted small">Total Perubahan</div>
            <div class="fs-4 fw-bold">{{ $logs->total() }}</div>
        </div>
        <div class="ms-auto">
            <a href="{{ route('kriteria.edit', $kriterium) }}" class="btn btn-warning btn-sm">
                <i class="bi bi-pencil me-1"></i>Edit Kriteria
            </a>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 pt-3 px-4">
        <h6 class="fw-bold mb-0">
            <i class="bi bi-clock-history me-2 text-primary"></i>Riwayat Perubahan Bobot
        </h6>
    </div>
    <div class="card-body p-0">
        @if($logs->isEmpty())
            <div class="text-center text-muted py-5">
                <i class="bi bi-shield-check fs-2 d-block mb-2 text-success"></i>
                Belum ada perubahan bobot tercatat untuk kriteria ini.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Waktu Perubahan</th>
                            <th class="text-center">Bobot Lama</th>
                            <th class="text-center"></th>
                            <th class="text-center">Bobot Baru</th>
                            <th class="text-center">Selisih</th>
                            <th>Diubah Oleh</th>
                            <th>Alasan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logs as $log)
                            @php $selisih = $log->bobot_baru - $log->bobot_lama; @endphp
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $log->diubah_pada->format('d/m/Y') }}</div>
                                    <small class="text-muted">{{ $log->diubah_pada->format('H:i:s') }}</small>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-secondary">{{ $log->bobot_lama }}%</span>
                                </td>
                                <td class="text-center text-muted">
                                    <i class="bi bi-arrow-right"></i>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-primary">{{ $log->bobot_baru }}%</span>
                                </td>
                                <td class="text-center">
                                    <span class="fw-semibold text-{{ $selisih > 0 ? 'success' : 'danger' }}">
                                        {{ $selisih > 0 ? '+' : '' }}{{ number_format($selisih, 2) }}%
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $log->diubahOleh?->name ?? 'Sistem' }}</div>
                                    <small class="text-muted">{{ $log->diubahOleh?->role }}</small>
                                </td>
                                <td>
                                    <span class="text-muted small">
                                        {{ $log->alasan ?? '—' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-3 border-top">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
