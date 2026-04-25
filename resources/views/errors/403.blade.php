<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 — Akses Ditolak</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: #f0f4f8;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
    </style>
</head>
<body>
    <div class="text-center px-3">
        <i class="bi bi-shield-x display-1 text-danger d-block mb-3"></i>
        <h1 class="fw-bold display-5">403</h1>
        <h5 class="text-muted mb-3">Akses Ditolak</h5>
        <p class="text-muted mb-4">
            {{ $message ?? 'Anda tidak memiliki izin untuk mengakses halaman ini.' }}
        </p>
        <div class="d-flex gap-2 justify-content-center">
            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Kembali
            </a>
            <a href="{{ route('dashboard') }}" class="btn btn-primary">
                <i class="bi bi-house me-1"></i>Dashboard
            </a>
        </div>
    </div>
</body>
</html>
