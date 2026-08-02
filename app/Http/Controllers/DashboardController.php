<?php

namespace App\Http\Controllers;

use App\Models\HasilSaw;
use App\Models\Kriteria;
use App\Models\Penduduk;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_penduduk'  => Penduduk::count(),
            'sudah_dinilai'   => Penduduk::whereHas('penilaian')->count(),
            'belum_dinilai'   => Penduduk::doesntHave('penilaian')->count(),
            'total_kriteria'  => Kriteria::count(),
            'total_layak'     => HasilSaw::where('rekomendasi', 'layak')->count(),
            'total_tidak'     => HasilSaw::where('rekomendasi', 'tidak_layak')->count(),
        ];

        // Statistik per-role
        if (auth()->user()->isAdmin()) {
            $stats['total_users'] = User::count();
        }

        $topRanking = HasilSaw::with('penduduk')
            ->where('rekomendasi', 'layak')
            ->orderBy('ranking')
            ->get();

        $statusCounts = [
            'pending'  => Penduduk::where('status', 'pending')->count(),
            'proses'   => Penduduk::where('status', 'proses')->count(),
            'diterima' => Penduduk::where('status', 'diterima')->count(),
            'ditolak'  => Penduduk::where('status', 'ditolak')->count(),
        ];

        return view('dashboard.index', compact('stats', 'topRanking', 'statusCounts'));
    }
}
