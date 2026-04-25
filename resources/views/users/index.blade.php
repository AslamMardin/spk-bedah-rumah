@extends('layouts.app')
@section('title', 'Manajemen Pengguna')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-0">Manajemen Pengguna</h4>
        <small class="text-muted">Kelola akun admin, evaluator, dan pimpinan</small>
    </div>
    <a href="{{ route('users.create') }}" class="btn btn-primary">
        <i class="bi bi-person-plus me-1"></i>Tambah Pengguna
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th class="text-center">Role</th>
                        <th class="text-center">Status</th>
                        <th>Dibuat</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr class="{{ $user->id === auth()->id() ? 'table-primary bg-opacity-10' : '' }}">
                            <td class="text-muted">{{ $users->firstItem() + $loop->index }}</td>
                            <td>
                                <div class="fw-semibold">
                                    {{ $user->name }}
                                    @if($user->id === auth()->id())
                                        <span class="badge bg-info ms-1 small">Saya</span>
                                    @endif
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td class="text-center">
                                @php
                                    $roleColor = match($user->role) {
                                        'admin'     => 'danger',
                                        'evaluator' => 'primary',
                                        'pimpinan'  => 'success',
                                        default     => 'secondary',
                                    };
                                @endphp
                                <span class="badge bg-{{ $roleColor }}">{{ ucfirst($user->role) }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-{{ $user->is_active ? 'success' : 'secondary' }}">
                                    {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td>
                                <small class="text-muted">{{ $user->created_at->format('d/m/Y') }}</small>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('users.edit', $user) }}" class="btn btn-outline-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @if($user->id !== auth()->id())
                                        <form method="POST" action="{{ route('users.destroy', $user) }}"
                                              onsubmit="return confirm('Hapus akun {{ $user->name }}?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">Belum ada pengguna.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
            <div class="p-3 border-top">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>

{{-- Info Role --}}
<div class="row g-3 mt-1">
    <div class="col-md-4">
        <div class="card border-danger border-opacity-25 bg-danger bg-opacity-5">
            <div class="card-body py-2">
                <span class="badge bg-danger me-2">Admin</span>
                <small class="text-muted">Kelola kriteria, bobot, akun pengguna, dan hitung SAW.</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-primary border-opacity-25 bg-primary bg-opacity-5">
            <div class="card-body py-2">
                <span class="badge bg-primary me-2">Evaluator</span>
                <small class="text-muted">Input data penduduk, penilaian, dan hitung SAW.</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-success border-opacity-25 bg-success bg-opacity-5">
            <div class="card-body py-2">
                <span class="badge bg-success me-2">Pimpinan</span>
                <small class="text-muted">Lihat hasil ranking dan export laporan saja.</small>
            </div>
        </div>
    </div>
</div>
@endsection
