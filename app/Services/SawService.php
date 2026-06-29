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

        // ── Step 5: Ranking & Dynamic Threshold ────────────────────────
        arsort($skorAkhir); // Urutkan dari nilai tertinggi
        
        // Hitung rata-rata skor sebagai ambang batas dinamis
        $rataRata = count($skorAkhir) > 0 ? array_sum($skorAkhir) / count($skorAkhir) : 0;

        // ── Step 6: Simpan ke Database ────────────────────────────────
        DB::transaction(function () use ($skorAkhir, $userId, $rataRata) {
            // Hapus hasil lama sebelum menyimpan yang baru
            HasilSaw::whereIn('penduduk_id', array_keys($skorAkhir))->delete();

            $ranking = 1;
            foreach ($skorAkhir as $pendudukId => $skor) {
                HasilSaw::create([
                    'penduduk_id'   => $pendudukId,
                    'nilai_saw'     => $skor,
                    'ranking'       => $ranking,
                    'rekomendasi'   => $skor >= $rataRata ? 'layak' : 'tidak_layak',
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

        $matriks = [];
        foreach ($detail as $pendudukId => $row) {
            if (count($row['kriteria']) === $kriteriaList->count()) {
                foreach ($kriteriaList as $kriteria) {
                    $matriks[$pendudukId][$kriteria->id] = (float) ($row['kriteria'][$kriteria->id] ?? 0);
                }
            }
        }

        $maxPerKriteria = [];
        $minPerKriteria = [];

        foreach ($kriteriaList as $kriteria) {
            $nilaiKolom = array_column(
                array_map(fn($row) => [$kriteria->id => $row[$kriteria->id] ?? null], $matriks),
                $kriteria->id
            );
            $nilaiKolom = array_filter($nilaiKolom, fn($v) => $v !== null);

            if (!empty($nilaiKolom)) {
                $maxPerKriteria[$kriteria->id] = max($nilaiKolom);
                $minPerKriteria[$kriteria->id] = min($nilaiKolom);
            } else {
                $maxPerKriteria[$kriteria->id] = 0;
                $minPerKriteria[$kriteria->id] = 0;
            }
        }

        $normalisasi = [];
        $skorAkhir = [];
        foreach ($matriks as $pendudukId => $row) {
            $normalRow = ['nama' => $detail[$pendudukId]['nama'], 'nik' => $detail[$pendudukId]['nik'], 'kriteria' => []];
            $vi = 0;

            foreach ($kriteriaList as $kriteria) {
                $xij = $row[$kriteria->id];

                if ($kriteria->tipe === 'benefit') {
                    $nilai = $maxPerKriteria[$kriteria->id] > 0
                        ? $xij / $maxPerKriteria[$kriteria->id]
                        : 0;
                } else {
                    $nilai = $xij > 0
                        ? $minPerKriteria[$kriteria->id] / $xij
                        : 0;
                }

                $normalRow['kriteria'][$kriteria->id] = round((float) $nilai, 6);
                $vi += $kriteria->bobot_desimal * $normalRow['kriteria'][$kriteria->id];
            }

            $normalisasi[$pendudukId] = $normalRow;
            $skorAkhir[$pendudukId] = round($vi, 6);
        }

        arsort($skorAkhir);
        $rataRata = count($skorAkhir) > 0 ? array_sum($skorAkhir) / count($skorAkhir) : 0;

        $keputusan = [];
        $ranking = 1;
        foreach ($skorAkhir as $pendudukId => $skor) {
            $keputusan[] = [
                'penduduk_id' => $pendudukId,
                'nama'        => $detail[$pendudukId]['nama'],
                'nik'         => $detail[$pendudukId]['nik'],
                'nilai_saw'   => round($skor, 6),
                'ranking'     => $ranking,
                'rekomendasi' => $skor >= $rataRata ? 'layak' : 'tidak_layak',
            ];
            $ranking++;
        }

        return [
            'kriteria'    => $kriteriaList,
            'detail'      => $detail,
            'normalisasi' => $normalisasi,
            'keputusan'   => $keputusan,
        ];
    }
}
