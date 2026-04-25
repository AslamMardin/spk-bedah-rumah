<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Penduduk extends Model
{
    protected $table = 'penduduk';

    protected $fillable = [
        'nik', 'nama', 'alamat', 'rt', 'rw', 'kelurahan',
        'kecamatan', 'no_hp', 'jumlah_anggota_keluarga',
        'foto_rumah', 'status', 'created_by',
    ];

    // ── Relations ─────────────────────────────────────────────
    public function penilaian(): HasMany
    {
        return $this->hasMany(Penilaian::class);
    }

    public function hasilSaw(): HasOne
    {
        return $this->hasOne(HasilSaw::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ── Accessors ─────────────────────────────────────────────
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending'  => 'Menunggu',
            'proses'   => 'Diproses',
            'diterima' => 'Diterima',
            'ditolak'  => 'Ditolak',
            default    => '-',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending'  => 'warning',
            'proses'   => 'info',
            'diterima' => 'success',
            'ditolak'  => 'danger',
            default    => 'secondary',
        };
    }

    // ── Business Logic ────────────────────────────────────────
    public function sudahLengkapDinilai(): bool
    {
        $jumlahKriteria = Kriteria::count();
        return $this->penilaian()->count() >= $jumlahKriteria;
    }
}
