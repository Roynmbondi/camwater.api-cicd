<?php

namespace App\Http\Controllers;

use App\Models\LogActivite;
use App\Services\LogService;
use Illuminate\Http\Request;

class LogController extends Controller
{

    public function index(Request $request)
    {
        try {
            $query = LogActivite::with('operateur')->orderBy('created_at', 'desc');

            // Filtrer par action
            if ($request->has('action')) {
                $query->where('action', $request->action);
            }

            // Filtrer par module
            if ($request->has('module')) {
                $query->where('module', $request->module);
            }

            // Filtrer par opérateur
            if ($request->has('operateur_id')) {
                $query->where('operateur_id', $request->operateur_id);
            }

            // Limiter le nombre de résultats
            $limit = $request->get('limit', 50);
            $logs = $query->limit($limit)->get();

            return response()->json([
                'status' => 'success',
                'data' => $logs,
                'count' => $logs->count()
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de la récupération des logs'
            ], 500);
        }
    }


    public function show(string $id)
    {
        try {
            $log = LogActivite::with('operateur')->findOrFail($id);

            return response()->json([
                'status' => 'success',
                'data' => $log
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Log non trouvé'
            ], 404);
        }
    }


    public function entityLogs(string $entityType, string $entityId)
    {
        try {
            $logs = LogService::getEntityLogs($entityId, $entityType);

            return response()->json([
                'status' => 'success',
                'data' => $logs,
                'count' => $logs->count()
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de la récupération des logs'
            ], 500);
        }
    }

    
    public function clean(Request $request)
    {
        try {
            $days = $request->get('days', 90);
            $count = LogService::cleanOldLogs($days);

            return response()->json([
                'status' => 'success',
                'message' => "{$count} log(s) supprimé(s)",
                'days' => $days
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors du nettoyage des logs'
            ], 500);
        }
    }
}
