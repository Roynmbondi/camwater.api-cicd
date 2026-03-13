<?php

namespace App\Services;

use App\Models\LogActivite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LogService
{
    /**
     * Enregistrer une activité
     */
    public static function log(
        string $action,
        string $module,
        string $description,
        ?int $entityId = null,
        ?string $entityType = null,
        ?array $dataBefore = null,
        ?array $dataAfter = null
    ): LogActivite {
        $request = request();
        
        return LogActivite::create([
            'action' => $action,
            'module' => $module,
            'description' => $description,
            'operateur_id' => Auth::guard('api')->id(),
            'entity_id' => $entityId,
            'entity_type' => $entityType,
            'data_before' => $dataBefore,
            'data_after' => $dataAfter,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
    }

    /**
     * Log de création
     */
    public static function logCreate(string $module, $entity, string $description = null): LogActivite
    {
        return self::log(
            'create',
            $module,
            $description ?? "Création d'un(e) {$module}",
            $entity->id ?? null,
            get_class($entity),
            null,
            $entity->toArray()
        );
    }

    /**
     * Log de mise à jour
     */
    public static function logUpdate(string $module, $entity, array $dataBefore, string $description = null): LogActivite
    {
        return self::log(
            'update',
            $module,
            $description ?? "Mise à jour d'un(e) {$module}",
            $entity->id ?? null,
            get_class($entity),
            $dataBefore,
            $entity->toArray()
        );
    }

    /**
     * Log de suppression
     */
    public static function logDelete(string $module, $entity, string $description = null): LogActivite
    {
        return self::log(
            'delete',
            $module,
            $description ?? "Suppression d'un(e) {$module}",
            $entity->id ?? null,
            get_class($entity),
            $entity->toArray(),
            null
        );
    }

    /**
     * Log de connexion
     */
    public static function logLogin(int $operateurId, string $email): LogActivite
    {
        $request = request();
        
        return LogActivite::create([
            'action' => 'login',
            'module' => 'auth',
            'description' => "Connexion de l'opérateur {$email}",
            'operateur_id' => $operateurId,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
    }

    /**
     * Log de déconnexion
     */
    public static function logLogout(): LogActivite
    {
        $user = Auth::guard('api')->user();
        
        return self::log(
            'logout',
            'auth',
            "Déconnexion de l'opérateur {$user->email}"
        );
    }

    /**
     * Log personnalisé
     */
    public static function logCustom(
        string $action,
        string $module,
        string $description,
        ?array $data = null
    ): LogActivite {
        return self::log(
            $action,
            $module,
            $description,
            null,
            null,
            null,
            $data
        );
    }

    /**
     * Récupérer les logs d'une entité
     */
    public static function getEntityLogs(int $entityId, string $entityType)
    {
        return LogActivite::where('entity_id', $entityId)
            ->where('entity_type', $entityType)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Récupérer les logs d'un opérateur
     */
    public static function getOperateurLogs(int $operateurId, ?int $limit = null)
    {
        $query = LogActivite::where('operateur_id', $operateurId)
            ->orderBy('created_at', 'desc');
        
        if ($limit) {
            $query->limit($limit);
        }
        
        return $query->get();
    }

    /**
     * Récupérer les logs récents
     */
    public static function getRecentLogs(int $limit = 50)
    {
        return LogActivite::with('operateur')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Nettoyer les vieux logs (plus de X jours)
     */
    public static function cleanOldLogs(int $days = 90): int
    {
        return LogActivite::where('created_at', '<', now()->subDays($days))->delete();
    }
}
