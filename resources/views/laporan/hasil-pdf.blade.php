<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Hasil SPK Bedah Rumah</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #1e293b; }
        .header { text-align: center; border-bottom: 2px solid #1e3a5f; padding-bottom: 10px; margin-bottom: 20px; }
        .header h2 { margin: 0; font-size: 14px; color: #1e3a5f; }
        .header p { margin: 2px 0; color: #64748b; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background: #1e3a5f; color: #fff; padding: 6px 8px; text-align: center; }
        td { padding: 5px 8px; border-bottom: 1px solid #e2e8f0; }
        tr:nth-child(even) { background: #f8fafc; }
        .layak { color: #16a34a; font-weight: bold; }
        .tidak-layak { color: #dc2626; font-weight: bold; }
        .footer { margin-top: 20px; font-size: 9px; color: #94a3b8; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN HASIL SELEKSI BANTUAN BEDAH RUMAH</h2>
        <p>Sistem Pendukung Keputusan — Metode Simple Additive Weighting (SAW)</p>
        <p>Dicetak pada: {{ now()->format('d F Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Ranking</th>
                <th>NIK</th>
                <th>Nama</th>
                <th>Kelurahan</th>
                <th>Kecamatan</th>
                <th>Nilai SAW (Vi)</th>
                <th>Rekomendasi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($hasil as $h)
                <tr>
                    <td style="text-align:center">#{{ $h->ranking }}</td>
                    <td>{{ $h->penduduk->nik }}</td>
                    <td>{{ $h->penduduk->nama }}</td>
                    <td>{{ $h->penduduk->kelurahan }}</td>
                    <td>{{ $h->penduduk->kecamatan }}</td>
                    <td style="text-align:center; font-weight:bold">{{ number_format($h->nilai_saw, 6) }}</td>
                    <td style="text-align:center">
                        <span class="{{ $h->rekomendasi === 'layak' ? 'layak' : 'tidak-layak' }}">
                            {{ $h->rekomendasi_label }}
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Laporan ini digenerate secara otomatis oleh sistem. Total alternatif: {{ $hasil->count() }}
    </div>
</body>
</html>
