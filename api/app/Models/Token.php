<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    use HasFactory;

    protected $table = 'tokens';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'token',
        'token_hash',
        'expires_at',
        'revoked',
    ];

    protected $hidden = [
        'token',
        'token_hash',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'created_at' => 'datetime',
        'expires_at' => 'datetime',
        'revoked' => 'boolean',
    ];

    /**
     * Relación: Un token pertenece a un usuario
     */
    public function user()
    {
        return $this->belongsTo(User::class , 'user_id');
    }

    /**
     * Scope: Tokens no revocados
     */
    public function scopeActive($query)
    {
        return $query->where('revoked', false);
    }

    /**
     * Scope: Tokens no expirados
     */
    public function scopeNotExpired($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')
                ->orWhere('expires_at', '>', now());
        });
    }

    /**
     * Verificar si el token está expirado
     */
    public function isExpired()
    {
        if (!$this->expires_at) {
            return false;
        }

        return $this->expires_at->isPast();
    }

    /**
     * Verificar si el token es válido (no revocado y no expirado)
     */
    public function isValid()
    {
        return !$this->revoked && !$this->isExpired();
    }
}