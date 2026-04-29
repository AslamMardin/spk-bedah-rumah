<?php

namespace App\Http\Controllers;

use App\Models\HasilSaw;
use App\Services\SawService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\HasilSawExport;

class HasilController extends Controller
{
    public function __construct(private SawService $sawService) {}

    /**
     * Tampilkan halaman hasil ranking SPK.
     */
    public function index()
    {
        $hasil = HasilSaw::with('penduduk')
            ->orderBy('ranking')
            ->paginate(30);

        $sudahDihitung = HasilSaw::exists();

        return view('hasil.index', compact('hasil', 'sudahDihitung'));
    }

    /**
     * Proses perhitungan SAW (trigger manual oleh admin/evaluator).
     */
    public function hitung()
    {
        $hasil = $this->sawService->hitung(auth()->id());

        if (empty($hasil)) {
            return redirect()->route('hasil.index')
                ->with('error', 'Tidak ada data penilaian yang lengkap untuk dihitung.');
        }

        return redirect()->route('hasil.index')
            ->with('success', 'Perhitungan SAW berhasil! ' . count($hasil) . ' alternatif telah diranking.');
    }

    /**
     * Detail normalisasi matriks (transparansi proses).
     */
    public function detail()
    {
        $detail = $this->sawService->getDetailNormalisasi();

        return view('hasil.detail', $detail);
    }

    /**
     * Export hasil ke PDF (PRD: laporan arsip).
     */
    public function exportPdf()
    {
        $hasil = HasilSaw::with('penduduk')->orderBy('ranking')->get();

        $pdf = Pdf::loadView('laporan.hasil-pdf', compact('hasil'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('hasil-spk-bedah-rumah-' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Export hasil ke Excel (PRD: laporan arsip).
     */
    public function exportExcel()
    {
        return Excel::download(
            new HasilSawExport(),
            'hasil-spk-bedah-rumah-' . now()->format('Y-m-d') . '.xlsx'
        );
    }
}
