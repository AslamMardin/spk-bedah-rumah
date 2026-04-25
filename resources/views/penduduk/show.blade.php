@extends('layouts.app')
@section('title', 'Detail — ' . $penduduk->nama)

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <a href="{{ route('penduduk.index') }}" class="text-decoration-none text-muted small">
                <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar Penduduk
            </a>
            <div class="d-flex align-items-center gap-2 mt-2">
                <h4 class="fw-bold mb-0">{{ $penduduk->nama }}</h4>
                <span class="badge bg-{{ $penduduk->status_color }}">{{ $penduduk->status_label }}</span>
            </div>
            <small class="text-muted">NIK: {{ $penduduk->nik }}</small>
        </div>
        @if (!auth()->user()->isPimpinan())
            <div class="d-flex gap-2">
                <a href="{{ route('penilaian.index', $penduduk) }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-clipboard-check me-1"></i>
                    {{ $penduduk->penilaian->count() > 0 ? 'Edit Penilaian' : 'Isi Penilaian' }}
                </a>
                <a href="{{ route('penduduk.edit', $penduduk) }}" class="btn btn-warning btn-sm text-dark">
                    <i class="bi bi-pencil me-1"></i>Edit
                </a>
            </div>
        @endif

        @if (auth()->user()->isAdmin() || auth()->user()->isPimpinan())
            <div class="d-flex gap-2">
                @if ($penduduk->status !== 'diterima')
                    <form action="{{ route('penduduk.status', $penduduk) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="diterima">
                        <button type="submit" class="btn btn-success btn-sm"
                            onclick="return confirm('Tandai sebagai Diterima?')">
                            <i class="bi bi-check-circle me-1"></i>Terima
                        </button>
                    </form>
                @endif

                @if ($penduduk->status !== 'ditolak')
                    <form action="{{ route('penduduk.status', $penduduk) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="ditolak">
                        <button type="submit" class="btn btn-danger btn-sm"
                            onclick="return confirm('Tandai sebagai Ditolak?')">
                            <i class="bi bi-x-circle me-1"></i>Tolak
                        </button>
                    </form>
                @endif
            </div>
        @endif
    </div>

    <div class="row g-4">
        {{-- Kartu Info --}}
        <div class="col-lg-4">
            {{-- Foto Rumah --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body text-center p-3">
                    @if ($penduduk->foto_rumah)
                        <img src="{{ asset('storage/' . $penduduk->foto_rumah) }}" class="img-fluid rounded"
                            alt="Foto Rumah" style="max-height: 220px; object-fit: cover;">
                        <div class="text-muted small mt-2">Foto kondisi rumah</div>
                    @else
                        <div class="py-4 text-muted">
                            <i class="bi bi-house fs-1 d-block mb-2 text-secondary"></i>
                            Belum ada foto rumah
                        </div>
                    @endif
                </div>
            </div>

            {{-- Data Pribadi --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 pt-3 fw-bold">
                    <i class="bi bi-person me-2 text-primary"></i>Data Pribadi
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td class="text-muted" style="width: 40%">Alamat</td>
                            <td>{{ $penduduk->alamat }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">RT/RW</td>
                            <td>{{ $penduduk->rt ?? '-' }} / {{ $penduduk->rw ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Kelurahan</td>
                            <td>{{ $penduduk->kelurahan }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Kecamatan</td>
                            <td>{{ $penduduk->kecamatan }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">No. HP</td>
                            <td>{{ $penduduk->no_hp ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Jml. Keluarga</td>
                            <td>{{ $penduduk->jumlah_anggota_keluarga }} orang</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Diinput oleh</td>
                            <td>{{ $penduduk->createdBy?->name ?? 'Sistem' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            {{-- Hasil SAW --}}
            @if ($penduduk->hasilSaw)
                <div
                    class="card border-0 shadow-sm bg-{{ $penduduk->hasilSaw->rekomendasi_color }} bg-opacity-10 border-{{ $penduduk->hasilSaw->rekomendasi_color }}">
                    <div class="card-body text-center py-4">
                        <div class="text-muted small mb-1">Hasil SAW</div>
                        <div class="display-6 fw-bold text-{{ $penduduk->hasilSaw->rekomendasi_color }}">
                            #{{ $penduduk->hasilSaw->ranking }}
                        </div>
                        <div class="fw-bold fs-5 mb-2">{{ number_format($penduduk->hasilSaw->nilai_saw, 4) }}</div>
                        <span class="badge bg-{{ $penduduk->hasilSaw->rekomendasi_color }} fs-6 px-4 py-2">
                            {{ $penduduk->hasilSaw->rekomendasi_label }}
                        </span>
                        <div class="text-muted small mt-2">
                            Dihitung: {{ $penduduk->hasilSaw->dihitung_pada?->format('d/m/Y H:i') }}
                        </div>
                    </div>
                </div>
            @else
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center text-muted py-4">
                        <i class="bi bi-calculator fs-2 d-block mb-2"></i>
                        Belum ada hasil perhitungan SAW.
                    </div>
                </div>
            @endif
        </div>

        {{-- Rekap Penilaian --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-3 d-flex align-items-center justify-content-between">
                    <h6 class="fw-bold mb-0">
                        <i class="bi bi-clipboard-data me-2 text-primary"></i>Rekap Penilaian Kriteria
                    </h6>
                    <span class="badge bg-{{ $penduduk->sudahLengkapDinilai() ? 'success' : 'warning' }}">
                        {{ $penduduk->penilaian->count() }} / {{ \App\Models\Kriteria::count() }} kriteria
                    </span>
                </div>
                <div class="card-body p-0">
                    @if ($penduduk->penilaian->isEmpty())
                        <div class="text-center text-muted py-5">
                            <i class="bi bi-clipboard-x fs-2 d-block mb-2"></i>
                            Belum ada penilaian untuk penduduk ini.
                            @if (!auth()->user()->isPimpinan())
                                <br>
                                <a href="{{ route('penilaian.index', $penduduk) }}" class="btn btn-primary btn-sm mt-3">
                                    <i class="bi bi-clipboard-check me-1"></i>Mulai Penilaian
                                </a>
                            @endif
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Kriteria</th>
                                        <th class="text-center">Tipe</th>
                                        <th class="text-center">Bobot</th>
                                        <th>Jawaban</th>
                                        <th class="text-center">Nilai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($penduduk->penilaian->sortBy('kriteria.kode') as $p)
                                        <tr>
                                            <td>
                                                <span class="badge bg-secondary me-1">{{ $p->kriteria->kode }}</span>
                                                {{ $p->kriteria->nama }}
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-{{ $p->kriteria->tipe_color }}">
                                                    {{ $p->kriteria->tipe_label }}
                                                </span>
                                            </td>
                                            <td class="text-center fw-semibold">{{ $p->kriteria->bobot }}%</td>
                                            <td>{{ $p->subKriteria->label }}</td>
                                            <td class="text-center">
                                                <span class="badge bg-dark fs-6 px-2">{{ $p->nilai }}</span>
                                            </td>
                                        </tr>
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
