@extends('layouts.app')
@section('title', 'Manajemen Kriteria')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-0">Kriteria & Bobot</h4>
        <small class="text-muted">Kelola kriteria dan bobot penilaian SAW</small>
    </div>
    <a href="{{ route('kriteria.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Tambah Kriteria
    </a>
</div>

{{-- Validasi total bobot --}}
@php $totalBobot = $kriteria->sum('bobot'); @endphp
<div class="alert alert-{{ abs($totalBobot - 100) < 0.01 ? 'success' : 'warning' }} d-flex align-items-center mb-4">
    <i class="bi bi-{{ abs($totalBobot - 100) < 0.01 ? 'check-circle' : 'exclamation-triangle' }} me-2"></i>
    <div>
        Total bobot saat ini: <strong>{{ number_format($totalBobot, 2) }}%</strong>
        @if(abs($totalBobot - 100) >= 0.01)
            — Bobot harus berjumlah tepat <strong>100%</strong> agar perhitungan SAW akurat.
        @else
            — Bobot sudah valid!
        @endif
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Kode</th>
                        <th>Nama Kriteria</th>
                        <th class="text-center">Tipe</th>
                        <th class="text-center">Bobot (%)</th>
                        <th class="text-center">Sub Kriteria</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kriteria as $k)
                        <tr>
                            <td><span class="badge bg-secondary">{{ $k->kode }}</span></td>
                            <td>
                                <div class="fw-semibold">{{ $k->nama }}</div>
                                @if($k->keterangan)
                                    <small class="text-muted">{{ Str::limit($k->keterangan, 60) }}</small>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge bg-{{ $k->tipe_color }}">{{ $k->tipe_label }}</span>
                            </td>
                            <td class="text-center fw-bold">{{ $k->bobot }}%</td>
                            <td class="text-center">
                                <a href="{{ route('kriteria.sub.index', $k) }}"
                                   class="btn btn-sm btn-outline-info">
                                    <i class="bi bi-list-ul me-1"></i>{{ $k->sub_kriteria_count }}
                                </a>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('kriteria.edit', $k) }}" class="btn btn-outline-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="{{ route('kriteria.audit', $k) }}" class="btn btn-outline-secondary"
                                       title="Audit Trail Bobot">
                                        <i class="bi bi-clock-history"></i>
                                    </a>
                                    <form method="POST" action="{{ route('kriteria.destroy', $k) }}"
                                          onsubmit="return confirm('Hapus kriteria {{ $k->nama }}?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                Belum ada kriteria. <a href="{{ route('kriteria.create') }}">Tambahkan sekarang</a>.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
