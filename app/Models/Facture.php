<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Facture extends Model
{

    protected $table = 'factures';


    protected $fillable = [
        'abonne_id',
        'consommation_id',
        'montant',
        'date_generation',
        'statut',
        'operateur_id',
    ];


    protected $casts = [
        'date_generation' => 'date',
        'montant' => 'decimal:2',
    ];


    public function abonne(): BelongsTo
    {
        return $this->belongsTo(Abonne::class);
    }


    public function consommation(): BelongsTo
    {
        return $this->belongsTo(Consommation::class);
    }


    public function operateur(): BelongsTo
    {
        return $this->belongsTo(Operateur::class);
    }


    public function paiement(): HasOne
    {
        return $this->hasOne(Paiement::class);
    }

    /**
     * Relation avec les réclamations
     */
    public function reclamations(): HasMany
    {
        return $this->hasMany(Reclamation::class);
    }
}
