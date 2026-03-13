<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Paiement extends Model
{

    protected $table = 'paiements';


    protected $fillable = [
        'facture_id',
        'montant',
        'date_paiement',
        'mode_paiement',
        'operateur_id',
    ];


    protected $casts = [
        'date_paiement' => 'date',
        'montant' => 'decimal:2',
    ];


    public function facture(): BelongsTo
    {
        return $this->belongsTo(Facture::class);
    }

    
    public function operateur(): BelongsTo
    {
        return $this->belongsTo(Operateur::class);
    }
}
