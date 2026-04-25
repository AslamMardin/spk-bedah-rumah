<?php

namespace App\Http\Controllers;

use App\Models\Penduduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PendudukController extends Controller
{
    public function index(Request $request)
    {
        $penduduk = Penduduk::with(['hasilSaw', 'createdBy'])
            ->when($request->search, fn($q) =>
                $q->where('nama', 'like', "%{$request->search}%")
                  ->orWhere('nik', 'like', "%{$request->search}%")
            )
            ->when($request->status, fn($q) =>
                $q->where('status', $request->status)
            )
            ->latest()
            ->paginate(15);

        return view('penduduk.index', compact('penduduk'));
    }

    public function create()
    {
        return view('penduduk.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nik'                    => 'required|digits:16|unique:penduduk,nik',
            'nama'                   => 'required|string|max:255',
            'alamat'                 => 'required|string',
            'rt'                     => 'nullable|string|max:5',
            'rw'                     => 'nullable|string|max:5',
            'kelurahan'              => 'required|string|max:100',
            'kecamatan'              => 'required|string|max:100',
            'no_hp'                  => 'nullable|string|max:15',
            'jumlah_anggota_keluarga'=> 'required|integer|min:1',
            'foto_rumah'             => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Upload foto kondisi rumah (PRD B)
        if ($request->hasFile('foto_rumah')) {
            $validated['foto_rumah'] = $request->file('foto_rumah')
                ->store('foto-rumah', 'public');
        }

        $validated['created_by'] = auth()->id();
        Penduduk::create($validated);

        return redirect()->route('penduduk.index')
            ->with('success', 'Data penduduk berhasil ditambahkan.');
    }

    public function show(Penduduk $penduduk)
    {
        $penduduk->load(['penilaian.kriteria', 'penilaian.subKriteria', 'hasilSaw']);

        return view('penduduk.show', compact('penduduk'));
    }

    public function edit(Penduduk $penduduk)
    {
        return view('penduduk.edit', compact('penduduk'));
    }

    public function update(Request $request, Penduduk $penduduk)
    {
        $validated = $request->validate([
            'nik'                    => "required|digits:16|unique:penduduk,nik,{$penduduk->id}",
            'nama'                   => 'required|string|max:255',
            'alamat'                 => 'required|string',
            'rt'                     => 'nullable|string|max:5',
            'rw'                     => 'nullable|string|max:5',
            'kelurahan'              => 'required|string|max:100',
            'kecamatan'              => 'required|string|max:100',
            'no_hp'                  => 'nullable|string|max:15',
            'jumlah_anggota_keluarga'=> 'required|integer|min:1',
            'foto_rumah'             => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('foto_rumah')) {
            // Hapus foto lama
            if ($penduduk->foto_rumah) {
                Storage::disk('public')->delete($penduduk->foto_rumah);
            }
            $validated['foto_rumah'] = $request->file('foto_rumah')
                ->store('foto-rumah', 'public');
        }

        $penduduk->update($validated);

        return redirect()->route('penduduk.index')
            ->with('success', 'Data penduduk berhasil diperbarui.');
    }

    public function destroy(Penduduk $penduduk)
    {
        if ($penduduk->foto_rumah) {
            Storage::disk('public')->delete($penduduk->foto_rumah);
        }

        $penduduk->delete();

        return redirect()->route('penduduk.index')
            ->with('success', 'Data penduduk berhasil dihapus.');
    }

    public function updateStatus(Request $request, Penduduk $penduduk)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,proses,diterima,ditolak',
        ]);

        $penduduk->update($validated);

        return back()->with('success', "Status penduduk {$penduduk->nama} berhasil diubah menjadi " . $penduduk->status_label);
    }
}
