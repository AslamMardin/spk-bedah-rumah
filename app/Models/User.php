<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'is_active',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // ── Role Helpers ──────────────────────────────────────────
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isEvaluator(): bool
    {
        return $this->role === 'evaluator';
    }

    public function isPimpinan(): bool
    {
        return $this->role === 'pimpinan';
    }

    // ── Relations ─────────────────────────────────────────────
    public function pendudukDibuat(): HasMany
    {
        return $this->hasMany(Penduduk::class, 'created_by');
    }

    public function auditBobot(): HasMany
    {
        return $this->hasMany(AuditTrailBobot::class, 'diubah_oleh');
    }
}
