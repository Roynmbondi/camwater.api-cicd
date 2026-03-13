<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogActivite extends Model
{
    protected $table = 'logs_activite';

    protected $fillable = [
        'action',
        'module',
        'description',
        'operateur_id',
        'entity_id',
        'entity_type',
        'data_before',
        'data_after',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'data_before' => 'array',
        'data_after' => 'array',
    ];

    /**
     * Relation avec l'opérateur
     */
    public function operateur(): BelongsTo
    {
        return $this->belongsTo(Operateur::class);
    }

    /**
     * Scope pour filtrer par action
     */
    public function scopeAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope pour filtrer par module
     */
    public function scopeModule($query, string $module)
    {
        return $query->where('module', $module);
    }

    /**
     * Scope pour filtrer par opérateur
     */
    public function scopeByOperateur($query, int $operateurId)
    {
        return $query->where('operateur_id', $operateurId);
    }
}
