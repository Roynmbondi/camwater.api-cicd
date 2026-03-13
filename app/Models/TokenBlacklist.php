<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TokenBlacklist extends Model
{
    protected $table = 'token_blacklist';

    protected $fillable = [
        'token',
        'jti',
        'expires_at',
        'operateur_id',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    /**
     * Relation avec l'opérateur
     */
    public function operateur(): BelongsTo
    {
        return $this->belongsTo(Operateur::class);
    }

    /**
     * Vérifier si un token est blacklisté
     */
    public static function isBlacklisted(string $jti): bool
    {
        return self::where('jti', $jti)
            ->where('expires_at', '>', now())
            ->exists();
    }

    /**
     * Nettoyer les tokens expirés
     */
    public static function cleanExpired(): int
    {
        return self::where('expires_at', '<', now())->delete();
    }
}
