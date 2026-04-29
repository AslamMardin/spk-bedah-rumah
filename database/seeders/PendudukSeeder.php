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
        // Data dummy calon penerima bantuan bedah rumah (40+ data)
        $pendudukData = [
            ['nik' => '7371010101800001', 'nama' => 'Budi Santoso',      'kelurahan' => 'Tamalanrea',  'kecamatan' => 'Tamalanrea',   'jumlah' => 5],
            ['nik' => '7371010101800002', 'nama' => 'Siti Rahayu',       'kelurahan' => 'Panakkukang', 'kecamatan' => 'Panakkukang',  'jumlah' => 3],
            ['nik' => '7371010101800003', 'nama' => 'Ahmad Fauzi',       'kelurahan' => 'Rappocini',   'kecamatan' => 'Rappocini',    'jumlah' => 7],
            ['nik' => '7371010101800004', 'nama' => 'Dewi Lestari',      'kelurahan' => 'Tallo',       'kecamatan' => 'Tallo',        'jumlah' => 4],
            ['nik' => '7371010101800005', 'nama' => 'Hendra Wijaya',     'kelurahan' => 'Bontoala',    'kecamatan' => 'Bontoala',     'jumlah' => 6],
            ['nik' => '7371010101800006', 'nama' => 'Fatimah Azzahra',   'kelurahan' => 'Ujung Pandang','kecamatan' => 'Ujung Pandang','jumlah' => 2],
            ['nik' => '7371010101800007', 'nama' => 'Ridwan Kamil',      'kelurahan' => 'Wajo',        'kecamatan' => 'Wajo',         'jumlah' => 8],
            ['nik' => '7371010101800008', 'nama' => 'Ningsih Susanti',   'kelurahan' => 'Manggala',    'kecamatan' => 'Manggala',     'jumlah' => 3],
            ['nik' => '7371010101800009', 'nama' => 'Muhammad Iqbal',    'kelurahan' => 'Biringkanaya','kecamatan' => 'Manggala',     'jumlah' => 4],
            ['nik' => '7371010101800010', 'nama' => 'Kartika Dewi',      'kelurahan' => 'Parang',      'kecamatan' => 'Tamalanrea',  'jumlah' => 5],
            ['nik' => '7371010101800011', 'nama' => 'Andi Surya',       'kelurahan' => 'Makassar',    'kecamatan' => 'Ujung Pandang','jumlah' => 6],
            ['nik' => '7371010101800012', 'nama' => 'Rina Amelia',      'kelurahan' => 'Maradekaya',  'kecamatan' => 'Panakkukang',  'jumlah' => 3],
            ['nik' => '7371010101800013', 'nama' => 'Jusuf Abdullah',   'kelurahan' => 'Kodingareng', 'kecamatan' => 'Bontoala',     'jumlah' => 7],
            ['nik' => '7371010101800014', 'nama' => 'Sari Wahyuni',     'kelurahan' => 'Mamajang',    'kecamatan' => 'Tallo',        'jumlah' => 4],
            ['nik' => '7371010101800015', 'nama' => 'Bahrun Amin',      'kelurahan' => 'Ballaparang', 'kecamatan' => 'Rappocini',    'jumlah' => 5],
            ['nik' => '7371010101800016', 'nama' => 'Nurhaliza',       'kelurahan' => 'Buntu',       'kecamatan' => 'Tamalanrea',  'jumlah' => 2],
            ['nik' => '7371010101800017', 'nama' => 'Hamka Hasan',     'kelurahan' => 'Panaikang',    'kecamatan' => 'Panakkukang',  'jumlah' => 6],
            ['nik' => '7371010101800018', 'nama' => 'Yanti Kartika',    'kelurahan' => 'Tamarunang',  'kecamatan' => 'Gowa',        'jumlah' => 4],
            ['nik' => '7371010101800019', 'nama' => 'Faisal Rahman',   'kelurahan' => 'Sudiang',     'kecamatan' => 'Biringkanaya','jumlah' => 5],
            ['nik' => '7371010101800020', 'nama' => 'Siti Nurhaliza',   'kelurahan' => 'Daya',        'kecamatan' => 'Bontoala',     'jumlah' => 3],
            ['nik' => '7371010101800021', 'nama' => 'Abdul Rahim',     'kelurahan' => 'Bontomarannu','kecamatan' => 'Tamalanrea',  'jumlah' => 8],
            ['nik' => '7371010101800022', 'nama' => 'Maya Sari',       'kelurahan' => 'Jaya',        'kecamatan' => 'Gowa',         'jumlah' => 4],
            ['nik' => '7371010101800023', 'nama' => 'Irwan Setiawan',   'kelurahan' => 'Katangka',    'kecamatan' => 'Rappocini',    'jumlah' => 5],
            ['nik' => '7371010101800024', 'nama' => 'Hapsah',           'kelurahan' => 'Bontolalu',   'kecamatan' => 'Bontoala',     'jumlah' => 6],
            ['nik' => '7371010101800025', 'nama' => 'Zainal Abidin',   'kelurahan' => 'Mappasunggu', 'kecamatan' => 'Panakkukang',  'jumlah' => 3],
            ['nik' => '7371010101800026', 'nama' => 'Rahmawati',       'kelurahan' => 'Samo',        'kecamatan' => 'Tallo',        'jumlah' => 4],
            ['nik' => '7371010101800027', 'nama' => 'Herman',          'kelurahan' => 'Bontokkai',   'kecamatan' => 'Bontoala',     'jumlah' => 7],
            ['nik' => '7371010101800028', 'nama' => 'Siti Aisyah',     'kelurahan' => 'Pettarani',   'kecamatan' => 'Rappocini',    'jumlah' => 2],
            ['nik' => '7371010101800029', 'nama' => 'Basyir',          'kelurahan' => 'Makkasau',    'kecamatan' => 'Wajo',         'jumlah' => 5],
            ['nik' => '7371010101800030', 'nama' => 'Nurul Hidayah',   'kelurahan' => 'Wara',        'kecamatan' => 'Wajo',         'jumlah' => 4],
            ['nik' => '7371010101800031', 'nama' => 'Mukhtar',         'kelurahan' => 'Lariangbangi','kecamatan' => 'Ujung Pandang','jumlah' => 6],
            ['nik' => '7371010101800032', 'nama' => 'Sariati',         'kelurahan' => 'Barombong',   'kecamatan' => 'Tamalanrea',  'jumlah' => 3],
            ['nik' => '7371010101800033', 'nama' => 'Saiful',          'kelurahan' => 'Tabah',       'kecamatan' => 'Manggala',     'jumlah' => 5],
            ['nik' => '7371010101800034', 'nama' => 'Hasnah',          'kelurahan' => 'Borong',      'kecamatan' => 'Panakkukang',  'jumlah' => 4],
            ['nik' => '7371010101800035', 'nama' => 'Mahfud',          'kelurahan' => 'Kaluku',      'kecamatan' => 'Bontoala',     'jumlah' => 7],
            ['nik' => '7371010101800036', 'nama' => 'Siti Maryam',    'kelurahan' => "Pa'baeng-baeng",'kecamatan' => 'Tallo',       'jumlah' => 2],
            ['nik' => '7371010101800037', 'nama' => 'Tahir',          'kelurahan' => 'Bontomanai',  'kecamatan' => 'Gowa',         'jumlah' => 6],
            ['nik' => '7371010101800038', 'nama' => 'Nurmiati',       'kelurahan' => 'Kampungsnogu','kecamatan' => 'Rappocini',    'jumlah' => 4],
            ['nik' => '7371010101800039', 'nama' => 'Ahmad Yani',     'kelurahan' => 'Bontonyanyi', 'kecamatan' => 'Tamalanrea',  'jumlah' => 5],
            ['nik' => '7371010101800040', 'nama' => 'Siti Aminah',    'kelurahan' => 'Tino',        'kecamatan' => 'Gowa',         'jumlah' => 3],
            ['nik' => '7371010101800041', 'nama' => 'Hasyim',          'kelurahan' => 'Bontoloe',    'kecamatan' => 'Bontoala',     'jumlah' => 8],
            ['nik' => '7371010101800042', 'nama' => 'Halijah',        'kelurahan' => 'Jompong',     'kecamatan' => 'Manggala',     'jumlah' => 4],
            ['nik' => '7371010101800043', 'nama' => 'Burhanuddin',    'kelurahan' => 'Makkasau',    'kecamatan' => 'Wajo',         'jumlah' => 5],
            ['nik' => '7371010101800044', 'nama' => 'Siti Fatimah',   'kelurahan' => 'Lagong',      'kecamatan' => 'Panakkukang',  'jumlah' => 6],
            ['nik' => '7371010101800045', 'nama' => 'Abdul Gani',     'kelurahan' => 'Bontotio',    'kecamatan' => 'Bontoala',     'jumlah' => 3],
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
            [4, 4, 5, 3, 4],  // Prioritas tinggi
            [2, 3, 2, 2, 2],  // Kurang prioritas
            [5, 5, 4, 4, 5],  // Sangat prioritas
            [3, 2, 3, 2, 3],  // Sedang
            [4, 4, 4, 3, 4],  // Prioritas tinggi
            [2, 2, 1, 3, 2],  // Kurang prioritas
            [5, 4, 5, 5, 5],  // Sangat prioritas
            [1, 2, 1, 1, 1],  // Tidak prioritas
            [4, 5, 4, 4, 4],  // Prioritas tinggi
            [3, 3, 2, 2, 3],  // Sedang
            [5, 5, 5, 4, 5],  // Sangat prioritas
            [2, 2, 3, 2, 2],  // Kurang prioritas
            [4, 4, 3, 3, 4],  // Prioritas tinggi
            [3, 3, 4, 2, 3],  // Sedang
            [5, 4, 4, 5, 5],  // Sangat prioritas
            [2, 1, 2, 1, 2],  // Tidak prioritas
            [4, 5, 5, 4, 4],  // Prioritas tinggi
            [3, 2, 3, 3, 3],  // Sedang
            [5, 5, 4, 5, 4],  // Sangat prioritas
            [1, 1, 2, 1, 1],  // Tidak prioritas
            [4, 4, 5, 3, 4],  // Prioritas tinggi
            [2, 3, 2, 2, 2],  // Kurang prioritas
            [5, 4, 4, 4, 5],  // Sangat prioritas
            [3, 2, 2, 3, 3],  // Sedang
            [4, 5, 4, 3, 4],  // Prioritas tinggi
            [2, 2, 1, 2, 2],  // Kurang prioritas
            [5, 5, 5, 5, 5],  // Sangat prioritas
            [1, 2, 1, 1, 1],  // Tidak prioritas
            [4, 4, 4, 4, 4],  // Prioritas tinggi
            [3, 3, 3, 2, 3],  // Sedang
            [5, 4, 5, 4, 5],  // Sangat prioritas
            [2, 2, 2, 3, 2],  // Kurang prioritas
            [4, 5, 3, 4, 4],  // Prioritas tinggi
            [3, 2, 3, 2, 3],  // Sedang
            [5, 5, 4, 5, 5],  // Sangat prioritas
            [1, 1, 1, 2, 1],  // Tidak prioritas
            [4, 4, 5, 3, 4],  // Prioritas tinggi
            [2, 3, 2, 2, 2],  // Kurang prioritas
            [5, 4, 4, 4, 5],  // Sangat prioritas
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
