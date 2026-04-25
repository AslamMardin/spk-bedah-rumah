<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Models\Penduduk;
use App\Models\Penilaian;
use Illuminate\Http\Request;

class PenilaianController extends Controller
{
    public function index(Penduduk $penduduk)
    {
        $kriteria    = Kriteria::with('subKriteria')->orderBy('kode')->get();
        $penilaian   = $penduduk->penilaian->keyBy('kriteria_id');
        $sudahLengkap = $penduduk->sudahLengkapDinilai();

        return view('penilaian.index', compact('penduduk', 'kriteria', 'penilaian', 'sudahLengkap'));
    }

    public function store(Request $request, Penduduk $penduduk)
    {
        $kriteria = Kriteria::with('subKriteria')->orderBy('kode')->get();

        // Bangun rules validasi dinamis berdasarkan kriteria yang ada
        $rules = [];
        foreach ($kriteria as $k) {
            $rules["penilaian.{$k->id}"] = 'required|exists:sub_kriteria,id';
        }

        $validated = $request->validate($rules);

        foreach ($validated['penilaian'] as $kriteriaId => $subKriteriaId) {
            // Cari nilai dari sub_kriteria
            $subKriteria = \App\Models\SubKriteria::find($subKriteriaId);

            Penilaian::updateOrCreate(
                ['penduduk_id' => $penduduk->id, 'kriteria_id' => $kriteriaId],
                [
                    'sub_kriteria_id' => $subKriteriaId,
                    'nilai'           => $subKriteria->nilai,
                    'created_by'      => auth()->id(),
                ]
            );
        }

        // Update status penduduk menjadi "proses"
        $penduduk->update(['status' => 'proses']);

        return redirect()->route('penduduk.show', $penduduk)
            ->with('success', 'Penilaian berhasil disimpan.');
    }
}
