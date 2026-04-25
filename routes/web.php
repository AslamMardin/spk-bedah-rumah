<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HasilController;
use App\Http\Controllers\KriteriaController;
use App\Http\Controllers\PendudukController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\SubKriteriaController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// ── Guest Routes ──────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// ── Authenticated Routes ──────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard — semua role dapat akses
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // ── Admin Only ─────────────────────────────────────────────────────────────
    Route::middleware('role:admin')->group(function () {

        // Manajemen Kriteria & Bobot
        Route::resource('kriteria', KriteriaController::class)->except(['show']);
        Route::get('kriteria/{kriterium}/audit', [KriteriaController::class, 'auditTrail'])
            ->name('kriteria.audit');

        // Sub Kriteria (nested)
        Route::prefix('kriteria/{kriterium}/sub')->name('kriteria.sub.')->group(function () {
            Route::get('/',       [SubKriteriaController::class, 'index'])->name('index');
            Route::post('/',      [SubKriteriaController::class, 'store'])->name('store');
            Route::put('/{sub}',  [SubKriteriaController::class, 'update'])->name('update');
            Route::delete('/{sub}', [SubKriteriaController::class, 'destroy'])->name('destroy');
        });

        // Manajemen User
        Route::resource('users', UserController::class)->except(['show']);
    });

    // ── Admin & Evaluator ──────────────────────────────────────────────────────
    Route::middleware('role:admin,evaluator')->group(function () {

        // Manajemen Data Penduduk (Alternatif)
        Route::resource('penduduk', PendudukController::class);

        // Penilaian per Penduduk
        Route::get('penduduk/{penduduk}/penilaian',  [PenilaianController::class, 'index'])
            ->name('penilaian.index');
        Route::post('penduduk/{penduduk}/penilaian', [PenilaianController::class, 'store'])
            ->name('penilaian.store');

        // Proses Hitung SAW
        Route::post('hasil/hitung', [HasilController::class, 'hitung'])->name('hasil.hitung');
    });

    // ── Admin & Pimpinan (Keputusan Status) ────────────────────────────────────
    Route::middleware('role:admin,pimpinan')->group(function () {
        Route::patch('penduduk/{penduduk}/status', [PendudukController::class, 'updateStatus'])
            ->name('penduduk.status');
    });

    // ── Semua Role Authenticated (termasuk pimpinan: hanya bisa lihat) ─────────
    Route::prefix('hasil')->name('hasil.')->group(function () {
        Route::get('/',        [HasilController::class, 'index'])->name('index');
        Route::get('/detail',  [HasilController::class, 'detail'])->name('detail');
        Route::get('/pdf',     [HasilController::class, 'exportPdf'])->name('export.pdf');
        Route::get('/excel',   [HasilController::class, 'exportExcel'])->name('export.excel');
    });
});
