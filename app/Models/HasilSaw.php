<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HasilSaw extends Model
{
    protected $table = 'hasil_saw';

    protected $fillable = [
        'penduduk_id', 'nilai_saw', 'ranking', 'rekomendasi',
        'dihitung_pada', 'dihitung_oleh',
    ];

    protected $casts = [
        'nilai_saw'     => 'decimal:6',
        'dihitung_pada' => 'datetime',
    ];

    public function penduduk(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class);
    }

    public function dihitungOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dihitung_oleh');
    }

    public function getRekomendasiLabelAttribute(): string
    {
        return $this->rekomendasi === 'layak' ? 'Layak' : 'Tidak Layak';
    }

    public function getRekomendasiColorAttribute(): string
    {
        return $this->rekomendasi === 'layak' ? 'success' : 'danger';
    }
}
