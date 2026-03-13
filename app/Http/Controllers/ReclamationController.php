<?php

namespace App\Http\Controllers;

use App\Models\Reclamation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ReclamationController extends Controller
{

    public function index(Request $request)
    {
        try {
            $query = Reclamation::with(['abonne', 'facture', 'operateur']);

            // Filtrer par statut si fourni
            if ($request->has('statut')) {
                $query->where('statut', $request->statut);
            }

            $reclamations = $query->orderBy('date_creation', 'desc')->get();

            return response()->json([
                'status' => 'success',
                'data' => $reclamations
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de la récupération des réclamations'
            ], 500);
        }
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'abonne_id' => 'required|exists:abonnes,id',
            'facture_id' => 'nullable|exists:factures,id',
            'description' => 'required|string',
            'date_creation' => 'required|date'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            $reclamation = Reclamation::create([
                'abonne_id' => $request->abonne_id,
                'facture_id' => $request->facture_id,
                'description' => $request->description,
                'date_creation' => $request->date_creation,
                'statut' => 'En attente'
            ]);

            $reclamation->load(['abonne', 'facture']);

            return response()->json([
                'status' => 'success',
                'data' => $reclamation,
                'message' => 'Réclamation créée avec succès'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de la création de la réclamation'
            ], 500);
        }
    }


    public function show(string $id)
    {
        try {
            $reclamation = Reclamation::with(['abonne', 'facture', 'operateur'])->findOrFail($id);

            return response()->json([
                'status' => 'success',
                'data' => $reclamation
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Réclamation non trouvée'
            ], 404);
        }
    }


    public function update(Request $request, string $id)
    {
        try {
            $reclamation = Reclamation::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'description' => 'nullable|string',
                'statut' => 'nullable|in:En attente,En cours,Résolue'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Erreur de validation',
                    'errors' => $validator->errors()
                ], 400);
            }

            $reclamation->update($request->only(['description', 'statut']));
            $reclamation->load(['abonne', 'facture', 'operateur']);

            return response()->json([
                'status' => 'success',
                'data' => $reclamation
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de la mise à jour de la réclamation'
            ], 500);
        }
    }


    public function traiter(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'operateur_id' => 'required|exists:operateurs,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            $reclamation = Reclamation::findOrFail($id);

            if ($reclamation->statut !== 'En attente') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Cette réclamation n\'est pas en attente'
                ], 400);
            }

            $reclamation->update([
                'statut' => 'En cours',
                'operateur_id' => $request->operateur_id
            ]);

            $reclamation->load(['abonne', 'facture', 'operateur']);

            return response()->json([
                'status' => 'success',
                'data' => $reclamation,
                'message' => 'Réclamation prise en charge avec succès'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors du traitement de la réclamation'
            ], 500);
        }
    }


    public function resoudre(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'commentaire_resolution' => 'required|string',
            'date_resolution' => 'required|date'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            $reclamation = Reclamation::findOrFail($id);

            if ($reclamation->statut === 'Résolue') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Cette réclamation est déjà résolue'
                ], 400);
            }

            $reclamation->update([
                'statut' => 'Résolue',
                'commentaire_resolution' => $request->commentaire_resolution,
                'date_resolution' => $request->date_resolution
            ]);

            $reclamation->load(['abonne', 'facture', 'operateur']);

            return response()->json([
                'status' => 'success',
                'data' => $reclamation,
                'message' => 'Réclamation résolue avec succès'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de la résolution de la réclamation'
            ], 500);
        }
    }

   
    public function destroy(string $id)
    {
        try {
            $reclamation = Reclamation::findOrFail($id);
            $reclamation->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Réclamation supprimée avec succès'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de la suppression de la réclamation'
            ], 500);
        }
    }
}
