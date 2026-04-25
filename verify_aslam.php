<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Penduduk;
use App\Models\Kriteria;
use App\Models\Penilaian;

$aslam = Penduduk::where('nama', 'like', '%aslam%')->first();
if (!$aslam) {
    echo "Aslam not found\n";
    exit;
}

$kriteria = Kriteria::all();
$penilaianAslam = $aslam->penilaian->keyBy('kriteria_id');

$totalBobot = $kriteria->sum('bobot');
echo "Total Bobot: $totalBobot\n\n";

$finalScore = 0;

foreach ($kriteria as $k) {
    $maxVal = Penilaian::where('kriteria_id', $k->id)->max('nilai');
    $minVal = Penilaian::where('kriteria_id', $k->id)->min('nilai');
    $nilaiAslam = $penilaianAslam[$k->id]->nilai ?? 0;
    
    // Normalisasi
    if ($k->tipe === 'benefit') {
        $normalization = $maxVal > 0 ? $nilaiAslam / $maxVal : 0;
    } else {
        $normalization = $nilaiAslam > 0 ? $minVal / $nilaiAslam : 0;
    }
    
    // Weighted score (bobot dlm desimal, misal 30% = 0.3)
    $weightDecimal = $k->bobot / 100;
    $weighted = $normalization * $weightDecimal;
    $finalScore += $weighted;
    
    echo "Kriteria: {$k->nama} ({$k->kode})\n";
    echo "  Tipe: {$k->tipe}, Bobot: {$k->bobot}%\n";
    echo "  Max: $maxVal, Min: $minVal, Nilai Aslam: $nilaiAslam\n";
    echo "  Normalisasi: " . round($normalization, 4) . "\n";
    echo "  Hasil Bobot: " . round($weighted, 4) . "\n\n";
}

echo "TOTAL SKOR AKHIR: " . round($finalScore, 4) . "\n";
echo "SKOR DI DB: " . round($aslam->hasilSaw->nilai_saw ?? 0, 4) . "\n";
