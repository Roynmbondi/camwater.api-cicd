<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;


class Consommation extends Model
{

    protected $table = 'consommations';


    protected $fillable = [
        'abonne_id',
        'index_precedent',
        'index_actuel',
        'consommation',
        'date_releve',
        'operateur_id',
    ];


    protected $casts = [
        'date_releve' => 'date',
    ];


    public function abonne(): BelongsTo
    {
        return $this->belongsTo(Abonne::class);
    }


    public function operateur(): BelongsTo
    {
        return $this->belongsTo(Operateur::class);
    }

    
    public function facture(): HasOne
    {
        return $this->hasOne(Facture::class);
    }
}
