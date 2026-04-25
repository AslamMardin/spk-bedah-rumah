@extends('layouts.app')
@section('title', 'Penilaian — ' . $penduduk->nama)

@section('content')
<div class="mb-4">
    <a href="{{ route('penduduk.index') }}" class="text-decoration-none text-muted small">
        <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar Penduduk
    </a>
    <h4 class="fw-bold mt-2 mb-0">Form Penilaian</h4>
    <small class="text-muted">{{ $penduduk->nama }} — NIK: {{ $penduduk->nik }}</small>
</div>

<div class="row">
    {{-- Info Penduduk --}}
    <div class="col-lg-4 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold border-0 pt-3">
                <i class="bi bi-person-circle me-2 text-primary"></i>Data Penduduk
            </div>
            <div class="card-body">
                @if($penduduk->foto_rumah)
                    <img src="{{ asset('storage/' . $penduduk->foto_rumah) }}"
                         class="img-fluid rounded mb-3" alt="Foto Rumah">
                @endif
                <table class="table table-sm table-borderless mb-0">
                    <tr><td class="text-muted">Alamat</td><td>{{ $penduduk->alamat }}</td></tr>
                    <tr><td class="text-muted">Kelurahan</td><td>{{ $penduduk->kelurahan }}</td></tr>
                    <tr><td class="text-muted">Kecamatan</td><td>{{ $penduduk->kecamatan }}</td></tr>
                    <tr><td class="text-muted">Tanggungan</td><td>{{ $penduduk->jumlah_anggota_keluarga }} orang</td></tr>
                    <tr>
                        <td class="text-muted">Status</td>
                        <td><span class="badge bg-{{ $penduduk->status_color }}">{{ $penduduk->status_label }}</span></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    {{-- Form Penilaian --}}
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold border-0 pt-3">
                <i class="bi bi-clipboard-check me-2 text-primary"></i>Nilai Per Kriteria
            </div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('penilaian.store', $penduduk) }}">
                    @csrf

                    @foreach($kriteria as $k)
                        @php $nilaiTerpilih = $penilaian[$k->id]->sub_kriteria_id ?? null; @endphp
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                <span class="badge bg-secondary me-1">{{ $k->kode }}</span>
                                {{ $k->nama }}
                                <span class="badge bg-{{ $k->tipe_color }} ms-1">{{ $k->tipe_label }}</span>
                                <span class="text-muted fw-normal small ms-1">Bobot: {{ $k->bobot }}%</span>
                            </label>

                            <div class="row g-2">
                                @foreach($k->subKriteria->sortBy('nilai') as $sub)
                                    <div class="col-12">
                                        <label class="d-flex align-items-center gap-2 p-2 border rounded cursor-pointer
                                            {{ $nilaiTerpilih == $sub->id ? 'border-primary bg-primary bg-opacity-10' : '' }}"
                                            style="cursor:pointer">
                                            <input type="radio"
                                                   name="penilaian[{{ $k->id }}]"
                                                   value="{{ $sub->id }}"
                                                   class="form-check-input m-0"
                                                   {{ $nilaiTerpilih == $sub->id ? 'checked' : '' }}>
                                            <span class="badge bg-dark">{{ $sub->nilai }}</span>
                                            <span>{{ $sub->label }}</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>

                            @error("penilaian.{$k->id}")
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    @endforeach

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <i class="bi bi-save me-2"></i>Simpan Penilaian
                        </button>
                        <a href="{{ route('penduduk.show', $penduduk) }}" class="btn btn-outline-secondary">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
