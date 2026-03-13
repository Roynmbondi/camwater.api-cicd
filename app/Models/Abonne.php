<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Abonne extends Model
{

    protected $table = 'abonnes';


    protected $fillable = [
        'nom',
        'prenom',
        'telephone',
        'adresse',
        'numero_compteur',
        'type',
        'ville',
        'date_abonnement',
    ];


    protected $casts = [
        'date_abonnement' => 'date',
    ];


    public function consommations(): HasMany
    {
        return $this->hasMany(Consommation::class);
    }

    
    public function factures(): HasMany
    {
        return $this->hasMany(Facture::class);
    }

    /**
     * Relation avec les réclamations
     */
    public function reclamations(): HasMany
    {
        return $this->hasMany(Reclamation::class);
    }
}
