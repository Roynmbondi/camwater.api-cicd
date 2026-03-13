<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Operateur extends Authenticatable implements JWTSubject
{
    protected $table = 'operateurs';

    protected $fillable = [
        'nom',
        'email',
        'mot_de_passe',
        'role',
    ];

    protected $hidden = [
        'mot_de_passe',
    ];

    // JWT Methods
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    // Override password field for JWT
    public function getAuthPassword()
    {
        return $this->mot_de_passe;
    }

    public function consommations(): HasMany
    {
        return $this->hasMany(Consommation::class);
    }

    public function factures(): HasMany
    {
        return $this->hasMany(Facture::class);
    }

    public function paiements(): HasMany
    {
        return $this->hasMany(Paiement::class);
    }
}
