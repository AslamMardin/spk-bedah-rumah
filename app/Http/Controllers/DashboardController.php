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
            ->orderBy('ranking')
            ->limit(5)
            ->get();

        return view('dashboard.index', compact('stats', 'topRanking'));
    }
}
