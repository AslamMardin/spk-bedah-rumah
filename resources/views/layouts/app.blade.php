<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SPK Bedah Rumah') — SAW</title>

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --sidebar-width: 260px;
            --sidebar-bg: #1e3a5f;
            --sidebar-active: #2563eb;
            --header-h: 60px;
        }
        body { background: #f0f4f8; font-family: 'Segoe UI', sans-serif; }

        /* ── Sidebar ── */
        #sidebar {
            width: var(--sidebar-width);
            min-height: 100vh;
            background: var(--sidebar-bg);
            position: fixed;
            top: 0; left: 0;
            overflow-y: auto;
            transition: transform .3s ease;
            z-index: 1040;
        }
        #sidebar .sidebar-brand {
            padding: 1.25rem 1.5rem;
            color: #fff;
            font-size: 1.1rem;
            font-weight: 700;
            border-bottom: 1px solid rgba(255,255,255,.15);
        }
        #sidebar .nav-link {
            color: rgba(255,255,255,.75);
            padding: .65rem 1.5rem;
            display: flex; align-items: center; gap: .6rem;
            border-left: 3px solid transparent;
            transition: all .2s;
        }
        #sidebar .nav-link:hover,
        #sidebar .nav-link.active {
            color: #fff;
            background: rgba(255,255,255,.08);
            border-left-color: var(--sidebar-active);
        }
        #sidebar .nav-section {
            color: rgba(255,255,255,.4);
            font-size: .7rem;
            text-transform: uppercase;
            letter-spacing: .1em;
            padding: 1rem 1.5rem .4rem;
        }

        /* ── Main Content ── */
        #main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }
        #topbar {
            height: var(--header-h);
            background: #fff;
            border-bottom: 1px solid #e5e7eb;
            position: sticky;
            top: 0; z-index: 100;
        }
        .page-body { padding: 1.5rem; }

        /* Responsive */
        @media (max-width: 768px) {
            #sidebar { transform: translateX(-100%); }
            #sidebar.show { transform: translateX(0); }
            #main-content { margin-left: 0; }
        }

        /* ── Pagination Custom ── */
        .pagination {
            --bs-pagination-padding-x: 0.5rem;
            --bs-pagination-padding-y: 0.25rem;
            --bs-pagination-font-size: 0.8125rem;
        }
        .page-link {
            border-radius: 0.25rem;
            margin: 0 1px;
            min-width: 32px;
            padding: 0.25rem 0.5rem;
            line-height: 1.4;
        }
        .page-link:focus {
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
        }
        .page-item .page-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        /* Arrow icons smaller */
        .page-item .page-link i,
        .page-item .page-link svg {
            font-size: 0.75rem;
            line-height: 1;
        }
        .page-item:first-child .page-link,
        .page-item:last-child .page-link {
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }
    </style>

    @stack('styles')
</head>
<body>

{{-- ── Sidebar ── --}}
<nav id="sidebar">
    <div class="sidebar-brand">
        <span class="fw-bold" style="display: inline-block; margin:0; padding:0">Bantuan Bedah Rumah</span> <br>
        <span style="font-size: 9px; color:#fff; background: orangered; padding: .25rem .5rem; border-radius: .25rem">Desa Piriang Kec.Tubbi Taramanu</span>
    </div>

    <ul class="nav flex-column mt-2">
        {{-- Dashboard --}}
        <li class="nav-item">
            <a href="{{ route('dashboard') }}"
               class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
        </li>

        {{-- Admin: Konfigurasi --}}
        @if(auth()->user()->isAdmin())
            <li><span class="nav-section">Konfigurasi</span></li>
            <li class="nav-item">
                <a href="{{ route('kriteria.index') }}"
                   class="nav-link {{ request()->routeIs('kriteria.*') ? 'active' : '' }}">
                    <i class="bi bi-sliders"></i> Kriteria & Bobot
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('users.index') }}"
                   class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i> Manajemen Pengguna
                </a>
            </li>
        @endif

        {{-- Admin & Evaluator: Data & Penilaian --}}
        @if(auth()->user()->isAdmin() || auth()->user()->isEvaluator())
            <li><span class="nav-section">Data & Penilaian</span></li>
            <li class="nav-item">
                <a href="{{ route('penduduk.index') }}"
                   class="nav-link {{ request()->routeIs('penduduk.*') ? 'active' : '' }}">
                    <i class="bi bi-person-vcard"></i> Data Penduduk
                </a>
            </li>
        @endif

        {{-- Semua Role: Hasil --}}
        <li><span class="nav-section">Hasil SPK</span></li>
        <li class="nav-item">
            <a href="{{ route('hasil.index') }}"
               class="nav-link {{ request()->routeIs('hasil.*') ? 'active' : '' }}">
                <i class="bi bi-bar-chart-steps"></i> Hasil Ranking
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('hasil.detail') }}" class="nav-link">
                <i class="bi bi-table"></i> Detail Normalisasi
            </a>
        </li>
    </ul>
</nav>

{{-- ── Main Content ── --}}
<div id="main-content">

    {{-- Topbar --}}
    <header id="topbar" class="d-flex align-items-center px-3 gap-3">
        <button class="btn btn-sm btn-light d-md-none" id="sidebarToggle">
            <i class="bi bi-list fs-5"></i>
        </button>
        <div class="ms-auto d-flex align-items-center gap-3">
            <span class="badge bg-{{ auth()->user()->isAdmin() ? 'danger' : (auth()->user()->isEvaluator() ? 'primary' : 'success') }}">
                {{ ucfirst(auth()->user()->role) }}
            </span>
            <span class="text-muted small">{{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-box-arrow-right"></i> Keluar
                </button>
            </form>
        </div>
    </header>

    {{-- Konten Halaman --}}
    <main class="page-body">
        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Toggle sidebar mobile
    document.getElementById('sidebarToggle')?.addEventListener('click', () => {
        document.getElementById('sidebar').classList.toggle('show');
    });
</script>
@stack('scripts')
</body>
</html>
