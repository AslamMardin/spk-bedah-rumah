<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Models\SubKriteria;
use Illuminate\Http\Request;

class SubKriteriaController extends Controller
{
    public function index(Kriteria $kriterium)
    {
        $subKriteria = $kriterium->subKriteria()->orderBy('nilai')->get();

        return view('sub_kriteria.index', compact('kriterium', 'subKriteria'));
    }

    public function store(Request $request, Kriteria $kriterium)
    {
        $validated = $request->validate([
            'label'       => 'required|string|max:255',
            'nilai'       => 'required|integer|min:1|max:10',
            'keterangan'  => 'nullable|string',
        ]);

        $kriterium->subKriteria()->create($validated);

        return redirect()->route('kriteria.sub.index', $kriterium)
            ->with('success', 'Sub kriteria berhasil ditambahkan.');
    }

    public function update(Request $request, Kriteria $kriterium, SubKriteria $sub)
    {
        $validated = $request->validate([
            'label'      => 'required|string|max:255',
            'nilai'      => 'required|integer|min:1|max:10',
            'keterangan' => 'nullable|string',
        ]);

        $sub->update($validated);

        return redirect()->route('kriteria.sub.index', $kriterium)
            ->with('success', 'Sub kriteria berhasil diperbarui.');
    }

    public function destroy(Kriteria $kriterium, SubKriteria $sub)
    {
        $sub->delete();

        return redirect()->route('kriteria.sub.index', $kriterium)
            ->with('success', 'Sub kriteria berhasil dihapus.');
    }
}
