<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditTrailBobot extends Model
{
    protected $table = 'audit_trail_bobot';

    protected $fillable = [
        'kriteria_id', 'bobot_lama', 'bobot_baru',
        'diubah_oleh', 'alasan', 'diubah_pada',
    ];

    protected $casts = [
        'diubah_pada' => 'datetime',
        'bobot_lama'  => 'decimal:2',
        'bobot_baru'  => 'decimal:2',
    ];

    public function kriteria(): BelongsTo
    {
        return $this->belongsTo(Kriteria::class);
    }

    public function diubahOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diubah_oleh');
    }
}
