<?php

namespace App\Exports;

use App\Models\HasilSaw;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class HasilSawExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    public function collection()
    {
        return HasilSaw::with('penduduk')->orderBy('ranking')->get();
    }

    public function title(): string
    {
        return 'Hasil SPK SAW';
    }

    public function headings(): array
    {
        return [
            'Ranking',
            'NIK',
            'Nama',
            'Alamat',
            'Kelurahan',
            'Kecamatan',
            'Jml. Anggota Keluarga',
            'Nilai SAW (Vi)',
            'Rekomendasi',
            'Tanggal Hitung',
        ];
    }

    public function map($row): array
    {
        return [
            '#' . $row->ranking,
            $row->penduduk->nik,
            $row->penduduk->nama,
            $row->penduduk->alamat,
            $row->penduduk->kelurahan,
            $row->penduduk->kecamatan,
            $row->penduduk->jumlah_anggota_keluarga,
            number_format($row->nilai_saw, 6),
            $row->rekomendasi_label,
            $row->dihitung_pada?->format('d/m/Y H:i'),
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font'      => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill'      => ['fillType' => 'solid', 'startColor' => ['argb' => 'FF1E3A5F']],
                'alignment' => ['horizontal' => 'center'],
            ],
        ];
    }
}
