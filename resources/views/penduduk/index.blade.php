@extends('layouts.app')
@section('title', 'Data Penduduk')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-0">Data Penduduk</h4>
        <small class="text-muted">Calon penerima bantuan bedah rumah</small>
    </div>
    <a href="{{ route('penduduk.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Tambah Penduduk
    </a>
</div>

{{-- Filter & Search --}}
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control" placeholder="Cari nama atau NIK..."
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="pending"  {{ request('status') === 'pending'  ? 'selected' : '' }}>Menunggu</option>
                    <option value="proses"   {{ request('status') === 'proses'   ? 'selected' : '' }}>Diproses</option>
                    <option value="diterima" {{ request('status') === 'diterima' ? 'selected' : '' }}>Diterima</option>
                    <option value="ditolak"  {{ request('status') === 'ditolak'  ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            <div class="col-md-3">
                <button class="btn btn-secondary w-100"><i class="bi bi-search me-1"></i>Cari</button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>NIK</th>
                        <th>Nama</th>
                        <th>Wilayah</th>
                        <th class="text-center">Penilaian</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Ranking</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penduduk as $p)
                        <tr>
                            <td class="text-muted">{{ $penduduk->firstItem() + $loop->index }}</td>
                            <td><code>{{ $p->nik }}</code></td>
                            <td>
                                <div class="fw-semibold">{{ $p->nama }}</div>
                                <small class="text-muted">{{ $p->jumlah_anggota_keluarga }} anggota keluarga</small>
                            </td>
                            <td>
                                {{ $p->kelurahan }}<br>
                                <small class="text-muted">{{ $p->kecamatan }}</small>
                            </td>
                            <td class="text-center">
                                @if($p->sudahLengkapDinilai())
                                    <span class="badge bg-success"><i class="bi bi-check2"></i> Lengkap</span>
                                @else
                                    <a href="{{ route('penilaian.index', $p) }}"
                                       class="badge bg-warning text-dark text-decoration-none">
                                        <i class="bi bi-pencil-square"></i> Isi Nilai
                                    </a>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge bg-{{ $p->status_color }}">{{ $p->status_label }}</span>
                            </td>
                            <td class="text-center">
                                @if($p->hasilSaw)
                                    <span class="fw-bold text-primary">#{{ $p->hasilSaw->ranking }}</span>
                                    <br><small class="text-muted">{{ number_format($p->hasilSaw->nilai_saw, 4) }}</small>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('penduduk.show', $p) }}" class="btn btn-outline-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('penilaian.index', $p) }}" class="btn btn-outline-primary"
                                       title="Penilaian">
                                        <i class="bi bi-clipboard-check"></i>
                                    </a>
                                    <a href="{{ route('penduduk.edit', $p) }}" class="btn btn-outline-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="{{ route('penduduk.destroy', $p) }}"
                                          onsubmit="return confirm('Hapus data {{ $p->nama }}?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">
                                Belum ada data penduduk.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($penduduk->hasPages())
            <div class="p-3 border-top">
                {{ $penduduk->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
