<?php

namespace App\Http\Controllers;

use App\Models\AuditTrailBobot;
use App\Models\Kriteria;
use Illuminate\Http\Request;

class KriteriaController extends Controller
{
    public function index()
    {
        $kriteria = Kriteria::withCount('subKriteria')->orderBy('kode')->get();
        $totalBobot = Kriteria::sum('bobot');

        return view('kriteria.index', compact('kriteria', 'totalBobot'));
    }

    public function create()
    {
        return view('kriteria.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode'       => 'required|string|max:10|unique:kriteria,kode',
            'nama'       => 'required|string|max:255',
            'tipe'       => 'required|in:benefit,cost',
            'bobot'      => 'required|numeric|min:0|max:100',
            'keterangan' => 'nullable|string',
        ]);

        // Validasi total bobot tidak melebihi 100
        $totalSaatIni = Kriteria::sum('bobot');
        if ($totalSaatIni + $validated['bobot'] > 100) {
            return back()->withErrors(['bobot' => 'Total bobot semua kriteria tidak boleh melebihi 100%.'])->withInput();
        }

        Kriteria::create($validated);

        return redirect()->route('kriteria.index')
            ->with('success', 'Kriteria berhasil ditambahkan.');
    }

    public function edit(Kriteria $kriterium)
    {
        return view('kriteria.edit', compact('kriterium'));
    }

    public function update(Request $request, Kriteria $kriterium)
    {
        $validated = $request->validate([
            'nama'       => 'required|string|max:255',
            'tipe'       => 'required|in:benefit,cost',
            'bobot'      => 'required|numeric|min:0|max:100',
            'keterangan' => 'nullable|string',
            'alasan'     => 'nullable|string|max:500',
        ]);

        // ── Audit Trail: catat perubahan bobot ────────────────────────
        if ((float) $kriterium->bobot !== (float) $validated['bobot']) {
            AuditTrailBobot::create([
                'kriteria_id' => $kriterium->id,
                'bobot_lama'  => $kriterium->bobot,
                'bobot_baru'  => $validated['bobot'],
                'diubah_oleh' => auth()->id(),
                'alasan'      => $validated['alasan'] ?? null,
                'diubah_pada' => now(),
            ]);
        }

        // Validasi total bobot (kecuali bobot kriteria ini sendiri)
        $totalLain = Kriteria::where('id', '!=', $kriterium->id)->sum('bobot');
        if ($totalLain + $validated['bobot'] > 100) {
            return back()->withErrors(['bobot' => 'Total bobot semua kriteria tidak boleh melebihi 100%.'])->withInput();
        }

        $kriterium->update($validated);

        return redirect()->route('kriteria.index')
            ->with('success', 'Kriteria berhasil diperbarui.');
    }

    public function destroy(Kriteria $kriterium)
    {
        $kriterium->delete();

        return redirect()->route('kriteria.index')
            ->with('success', 'Kriteria berhasil dihapus.');
    }

    public function auditTrail(Kriteria $kriterium)
    {
        $logs = AuditTrailBobot::with('diubahOleh')
            ->where('kriteria_id', $kriterium->id)
            ->latest('diubah_pada')
            ->paginate(15);

        return view('kriteria.audit', compact('kriterium', 'logs'));
    }
}
