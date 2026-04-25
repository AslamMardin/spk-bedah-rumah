<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kriteria extends Model
{
    protected $table = 'kriteria';
    protected $fillable = ['kode', 'nama', 'tipe', 'bobot', 'keterangan'];

    protected $casts = ['bobot' => 'decimal:2'];

    // ── Relations ─────────────────────────────────────────────
    public function subKriteria(): HasMany
    {
        return $this->hasMany(SubKriteria::class);
    }

    public function penilaian(): HasMany
    {
        return $this->hasMany(Penilaian::class);
    }

    public function auditBobot(): HasMany
    {
        return $this->hasMany(AuditTrailBobot::class);
    }

    // ── Accessors ─────────────────────────────────────────────
    public function getTipeLabelAttribute(): string
    {
        return $this->tipe === 'benefit' ? 'Benefit' : 'Cost';
    }

    public function getTipeColorAttribute(): string
    {
        return $this->tipe === 'benefit' ? 'success' : 'danger';
    }

    // ── Bobot dalam desimal (0–1) untuk kalkulasi SAW ─────────
    public function getBobotDesimalAttribute(): float
    {
        return (float) $this->bobot / 100;
    }
}
