<?php

namespace Database\Seeders;

use App\Models\Kriteria;
use App\Models\Penduduk;
use App\Models\Penilaian;
use App\Models\SubKriteria;
use Illuminate\Database\Seeder;

class PendudukSeeder extends Seeder
{
    public function run(): void
    {
        // Data dummy calon penerima bantuan bedah rumah
        $pendudukData = [
            ['nik' => '7371010101800001', 'nama' => 'Budi Santoso',      'kelurahan' => 'Tamalanrea',  'kecamatan' => 'Tamalanrea',   'jumlah' => 5],
            ['nik' => '7371010101800002', 'nama' => 'Siti Rahayu',       'kelurahan' => 'Panakkukang', 'kecamatan' => 'Panakkukang',  'jumlah' => 3],
            ['nik' => '7371010101800003', 'nama' => 'Ahmad Fauzi',       'kelurahan' => 'Rappocini',   'kecamatan' => 'Rappocini',    'jumlah' => 7],
            ['nik' => '7371010101800004', 'nama' => 'Dewi Lestari',      'kelurahan' => 'Tallo',       'kecamatan' => 'Tallo',        'jumlah' => 4],
            ['nik' => '7371010101800005', 'nama' => 'Hendra Wijaya',     'kelurahan' => 'Bontoala',    'kecamatan' => 'Bontoala',     'jumlah' => 6],
            ['nik' => '7371010101800006', 'nama' => 'Fatimah Azzahra',   'kelurahan' => 'Ujung Pandang','kecamatan' => 'Ujung Pandang','jumlah' => 2],
            ['nik' => '7371010101800007', 'nama' => 'Ridwan Kamil',      'kelurahan' => 'Wajo',        'kecamatan' => 'Wajo',         'jumlah' => 8],
            ['nik' => '7371010101800008', 'nama' => 'Ningsih Susanti',   'kelurahan' => 'Manggala',    'kecamatan' => 'Manggala',     'jumlah' => 3],
        ];

        // Nilai penilaian dummy [C1, C2, C3, C4, C5] (indeks nilai sub_kriteria)
        // Skenario beragam untuk menguji SAW
        $nilaiSkenario = [
            [5, 5, 5, 3, 5],  // Sangat prioritas
            [3, 3, 3, 2, 3],  // Sedang
            [5, 4, 5, 4, 5],  // Sangat prioritas
            [2, 2, 2, 3, 2],  // Kurang prioritas
            [4, 5, 4, 4, 4],  // Prioritas tinggi
            [1, 1, 1, 1, 1],  // Tidak prioritas
            [5, 5, 4, 5, 5],  // Sangat prioritas
            [3, 4, 3, 2, 3],  // Sedang
        ];

        $kriteriaList = Kriteria::with('subKriteria')->orderBy('kode')->get();
        $evaluatorId  = \App\Models\User::where('role', 'evaluator')->first()?->id;

        foreach ($pendudukData as $i => $data) {
            $penduduk = Penduduk::updateOrCreate(
                ['nik' => $data['nik']],
                [
                    'nama'                    => $data['nama'],
                    'alamat'                  => 'Jl. Contoh No. ' . ($i + 1),
                    'kelurahan'               => $data['kelurahan'],
                    'kecamatan'               => $data['kecamatan'],
                    'jumlah_anggota_keluarga' => $data['jumlah'],
                    'status'                  => 'proses',
                    'created_by'              => $evaluatorId,
                ]
            );

            // Input penilaian sesuai skenario
            foreach ($kriteriaList as $j => $kriteria) {
                $targetNilai = $nilaiSkenario[$i][$j] ?? 1;
                $sub = $kriteria->subKriteria->firstWhere('nilai', $targetNilai);

                if ($sub) {
                    Penilaian::updateOrCreate(
                        ['penduduk_id' => $penduduk->id, 'kriteria_id' => $kriteria->id],
                        [
                            'sub_kriteria_id' => $sub->id,
                            'nilai'           => $sub->nilai,
                            'created_by'      => $evaluatorId,
                        ]
                    );
                }
            }
        }
    }
}
