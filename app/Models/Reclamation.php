<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reclamation extends Model
{
    protected $table = 'reclamations';

    protected $fillable = [
        'abonne_id',
        'facture_id',
        'description',
        'statut',
        'date_creation',
        'date_resolution',
        'operateur_id',
        'commentaire_resolution',
    ];

    protected $casts = [
        'date_creation' => 'date',
        'date_resolution' => 'date',
    ];

    /**
     * Relation avec l'abonné
     */
    public function abonne(): BelongsTo
    {
        return $this->belongsTo(Abonne::class);
    }

    /**
     * Relation avec la facture (optionnelle)
     */
    public function facture(): BelongsTo
    {
        return $this->belongsTo(Facture::class);
    }

    /**
     * Relation avec l'opérateur qui traite la réclamation
     */
    public function operateur(): BelongsTo
    {
        return $this->belongsTo(Operateur::class);
    }

    /**
     * Scope pour filtrer par statut
     */
    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'En attente');
    }

    public function scopeEnCours($query)
    {
        return $query->where('statut', 'En cours');
    }

    public function scopeResolue($query)
    {
        return $query->where('statut', 'Résolue');
    }
}
