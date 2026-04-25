<?php

namespace App\Services;

use App\Models\HasilSaw;
use App\Models\Kriteria;
use App\Models\Penduduk;
use Illuminate\Support\Facades\DB;

/**
 * SAW (Simple Additive Weighting) Service
 *
 * Langkah algoritma:
 *  1. Bangun matriks keputusan X[alternatif][kriteria]
 *  2. Normalisasi:
 *       - Benefit : r_ij = x_ij / max(x_j)
 *       - Cost    : r_ij = min(x_j) / x_ij
 *  3. Hitung skor akhir: V_i = Σ (w_j * r_ij)
 *  4. Ranking dari nilai tertinggi ke terendah
 */
class SawService
{
    /**
     * Jalankan seluruh proses SAW dan simpan hasilnya.
     *
     * @param  int|null  $userId  ID user yang memicu perhitungan
     * @return array             Array hasil ranking
     */
    public function hitung(?int $userId = null): array
    {
        $kriteriaList = Kriteria::with('penilaian')->orderBy('kode')->get();
        $pendudukList = Penduduk::with(['penilaian.kriteria'])->get();

        if ($kriteriaList->isEmpty() || $pendudukList->isEmpty()) {
            return [];
        }

        // ── Step 1: Bangun Matriks Keputusan ──────────────────────────
        // $matriks[penduduk_id][kriteria_id] = nilai mentah
        $matriks = [];
        foreach ($pendudukList as $penduduk) {
            foreach ($penduduk->penilaian as $p) {
                $matriks[$penduduk->id][$p->kriteria_id] = (float) $p->nilai;
            }
        }

        // Hanya proses penduduk yang sudah dinilai semua kriteria
        $jumlahKriteria = $kriteriaList->count();
        $matriks = array_filter(
            $matriks,
            fn($row) => count($row) === $jumlahKriteria
        );

        if (empty($matriks)) {
            return [];
        }

        // ── Step 2: Cari Max & Min per Kriteria ───────────────────────
        $maxPerKriteria = [];
        $minPerKriteria = [];

        foreach ($kriteriaList as $kriteria) {
            $nilaiKolom = array_column(
                array_map(fn($row) => [$kriteria->id => $row[$kriteria->id] ?? null], $matriks),
                $kriteria->id
            );
            $nilaiKolom = array_filter($nilaiKolom, fn($v) => $v !== null);

            $maxPerKriteria[$kriteria->id] = max($nilaiKolom);
            $minPerKriteria[$kriteria->id] = min($nilaiKolom);
        }

        // ── Step 3: Normalisasi Matriks ───────────────────────────────
        $normal = [];
        foreach ($matriks as $pendudukId => $row) {
            foreach ($kriteriaList as $kriteria) {
                $xij = $row[$kriteria->id];

                if ($kriteria->tipe === 'benefit') {
                    // r_ij = x_ij / max(x_j)
                    $normal[$pendudukId][$kriteria->id] = $maxPerKriteria[$kriteria->id] > 0
                        ? $xij / $maxPerKriteria[$kriteria->id]
                        : 0;
                } else {
                    // r_ij = min(x_j) / x_ij
                    $normal[$pendudukId][$kriteria->id] = $xij > 0
                        ? $minPerKriteria[$kriteria->id] / $xij
                        : 0;
                }
            }
        }

        // ── Step 4: Hitung Skor Akhir Vi ──────────────────────────────
        $skorAkhir = [];
        foreach ($normal as $pendudukId => $row) {
            $vi = 0;
            foreach ($kriteriaList as $kriteria) {
                $wj  = $kriteria->bobot_desimal;    // bobot dalam 0–1
                $rij = $row[$kriteria->id];
                $vi += $wj * $rij;
            }
            $skorAkhir[$pendudukId] = round($vi, 6);
        }

        // ── Step 5: Ranking ────────────────────────────────────────────
        arsort($skorAkhir); // Urutkan dari nilai tertinggi

        // ── Step 6: Simpan ke Database ────────────────────────────────
        DB::transaction(function () use ($skorAkhir, $userId) {
            // Hapus hasil lama sebelum menyimpan yang baru
            HasilSaw::whereIn('penduduk_id', array_keys($skorAkhir))->delete();

            $ranking = 1;
            foreach ($skorAkhir as $pendudukId => $skor) {
                HasilSaw::create([
                    'penduduk_id'   => $pendudukId,
                    'nilai_saw'     => $skor,
                    'ranking'       => $ranking,
                    'rekomendasi'   => $skor >= 0.5 ? 'layak' : 'tidak_layak',
                    'dihitung_pada' => now(),
                    'dihitung_oleh' => $userId,
                ]);
                $ranking++;
            }
        });

        // ── Return: Data hasil lengkap ─────────────────────────────────
        return HasilSaw::with('penduduk')
            ->orderBy('ranking')
            ->get()
            ->toArray();
    }

    /**
     * Ambil detail normalisasi untuk ditampilkan di view (transparansi).
     */
    public function getDetailNormalisasi(): array
    {
        $kriteriaList = Kriteria::orderBy('kode')->get();
        $pendudukList = Penduduk::with(['penilaian', 'hasilSaw'])->get();

        $detail = [];
        foreach ($pendudukList as $penduduk) {
            $row = ['nama' => $penduduk->nama, 'nik' => $penduduk->nik, 'kriteria' => []];
            foreach ($penduduk->penilaian as $p) {
                $row['kriteria'][$p->kriteria_id] = $p->nilai;
            }
            $detail[$penduduk->id] = $row;
        }

        return [
            'kriteria' => $kriteriaList,
            'detail'   => $detail,
        ];
    }
}
