<?php

namespace Database\Seeders;

use App\Models\Kriteria;
use App\Models\SubKriteria;
use Illuminate\Database\Seeder;

class KriteriaSeeder extends Seeder
{
    public function run(): void
    {
        // Kriteria sesuai PRD bagian 5
        $kriteriaData = [
            [
                'kode'       => 'C1',
                'nama'       => 'Penghasilan Keluarga',
                'tipe'       => 'cost',      // Cost: makin rendah penghasilan = makin prioritas
                'bobot'      => 25,
                'keterangan' => 'Penghasilan bulanan keluarga calon penerima',
                'sub'        => [
                    ['label' => '> Rp 3.000.000',                 'nilai' => 1],
                    ['label' => 'Rp 2.000.001 – Rp 3.000.000',    'nilai' => 2],
                    ['label' => 'Rp 1.000.001 – Rp 2.000.000',    'nilai' => 3],
                    ['label' => 'Rp 500.001 – Rp 1.000.000',      'nilai' => 4],
                    ['label' => '< Rp 500.000',                    'nilai' => 5],
                ],
            ],
            [
                'kode'       => 'C2',
                'nama'       => 'Kondisi Dinding',
                'tipe'       => 'benefit',   // Benefit: semakin rusak = semakin prioritas
                'bobot'      => 15,
                'keterangan' => 'Kondisi fisik dinding rumah',
                'sub'        => [
                    ['label' => 'Permanen & Baik',      'nilai' => 1],
                    ['label' => 'Permanen & Retak',     'nilai' => 2],
                    ['label' => 'Semi Permanen',        'nilai' => 3],
                    ['label' => 'Kayu/Bambu',           'nilai' => 4],
                    ['label' => 'Rusak Berat/Roboh',    'nilai' => 5],
                ],
            ],
            [
                'kode'       => 'C3',
                'nama'       => 'Kondisi Atap',
                'tipe'       => 'benefit',
                'bobot'      => 15,
                'keterangan' => 'Kondisi fisik atap rumah',
                'sub'        => [
                    ['label' => 'Genteng Baik',         'nilai' => 1],
                    ['label' => 'Genteng Rusak Ringan', 'nilai' => 2],
                    ['label' => 'Asbes',                'nilai' => 3],
                    ['label' => 'Seng Berkarat',        'nilai' => 4],
                    ['label' => 'Daun/Rusak Berat',     'nilai' => 5],
                ],
            ],
            [
                'kode'       => 'C4',
                'nama'       => 'Kondisi Lantai',
                'tipe'       => 'benefit',
                'bobot'      => 10,
                'keterangan' => 'Kondisi fisik lantai rumah',
                'sub'        => [
                    ['label' => 'Keramik/Granit',      'nilai' => 1],
                    ['label' => 'Ubin/Plester',        'nilai' => 2],
                    ['label' => 'Papan',               'nilai' => 3],
                    ['label' => 'Tanah',               'nilai' => 4],
                    ['label' => 'Rusak Berat',         'nilai' => 5],
                ],
            ],
            [
                'kode'       => 'C5',
                'nama'       => 'Sanitasi',
                'tipe'       => 'benefit',
                'bobot'      => 10,
                'keterangan' => 'Kondisi fasilitas MCK dan sanitasi',
                'sub'        => [
                    ['label' => 'MCK Lengkap & Baik',   'nilai' => 1],
                    ['label' => 'MCK Lengkap Rusak',    'nilai' => 2],
                    ['label' => 'MCK Sebagian',         'nilai' => 3],
                    ['label' => 'MCK Sederhana',        'nilai' => 4],
                    ['label' => 'Tidak Ada MCK',        'nilai' => 5],
                ],
            ],
            [
                'kode'       => 'C6',
                'nama'       => 'Jumlah Tanggungan',
                'tipe'       => 'benefit',
                'bobot'      => 10,
                'keterangan' => 'Jumlah anggota keluarga yang ditanggung',
                'sub'        => [
                    ['label' => '1 orang',      'nilai' => 1],
                    ['label' => '2–3 orang',    'nilai' => 2],
                    ['label' => '4–5 orang',    'nilai' => 3],
                    ['label' => '6–7 orang',    'nilai' => 4],
                    ['label' => '> 7 orang',    'nilai' => 5],
                ],
            ],
            [
                'kode'       => 'C7',
                'nama'       => 'Status Kepemilikan',
                'tipe'       => 'benefit',
                'bobot'      => 10,
                'keterangan' => 'Status kepemilikan lahan/rumah',
                'sub'        => [
                    ['label' => 'Kontrak/Sewa',         'nilai' => 1],
                    ['label' => 'Rumah Dinas',          'nilai' => 2],
                    ['label' => 'Milik Keluarga',       'nilai' => 3],
                    ['label' => 'Milik Sendiri (HGB)',  'nilai' => 4],
                    ['label' => 'Milik Sendiri (SHM)',  'nilai' => 5],
                ],
            ],
            [
                'kode'       => 'C8',
                'nama'       => 'Kerentanan',
                'tipe'       => 'benefit',
                'bobot'      => 5,
                'keterangan' => 'Tingkat kerentanan sosial ekonomi keluarga',
                'sub'        => [
                    ['label' => 'Tidak Rentan',     'nilai' => 1],
                    ['label' => 'Kurang Rentan',   'nilai' => 2],
                    ['label' => 'Cukup Rentan',    'nilai' => 3],
                    ['label' => 'Rentan',          'nilai' => 4],
                    ['label' => 'Sangat Rentan',   'nilai' => 5],
                ],
            ],
        ];

        foreach ($kriteriaData as $data) {
            $subData = $data['sub'];
            unset($data['sub']);

            $kriteria = Kriteria::updateOrCreate(['kode' => $data['kode']], $data);

            foreach ($subData as $sub) {
                SubKriteria::updateOrCreate(
                    ['kriteria_id' => $kriteria->id, 'nilai' => $sub['nilai']],
                    ['label' => $sub['label']]
                );
            }
        }
    }
}
