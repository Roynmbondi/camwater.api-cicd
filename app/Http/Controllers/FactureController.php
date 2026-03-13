<?php

namespace App\Http\Controllers;

use App\Models\Facture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FactureController extends Controller
{
    
    public function index()
    {
        try {
            $factures = Facture::with(['abonne', 'consommation', 'operateur', 'paiement'])->get();

            return response()->json([
                'status' => 'success',
                'data' => $factures
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de la récupération des factures'
            ], 500);
        }
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'abonne_id' => 'required|exists:abonnes,id',
            'consommation_id' => 'required|exists:consommations,id',
            'montant' => 'required|numeric|min:0',
            'date_generation' => 'required|date',
            'statut' => 'nullable|in:impayee,payee',
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
            $facture = Facture::create([
                'abonne_id' => $request->abonne_id,
                'consommation_id' => $request->consommation_id,
                'montant' => $request->montant,
                'date_generation' => $request->date_generation,
                'statut' => $request->statut ?? 'impayee',
                'operateur_id' => $request->operateur_id
            ]);

            return response()->json([
                'status' => 'success',
                'data' => $facture
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de la création de la facture'
            ], 500);
        }
    }


    public function show(string $id)
    {
        try {
            $facture = Facture::with(['abonne', 'consommation', 'operateur', 'paiement'])->findOrFail($id);

            return response()->json([
                'status' => 'success',
                'data' => $facture
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Facture non trouvée'
            ], 404);
        }
    }


    public function update(Request $request, string $id)
    {
        try {
            $facture = Facture::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'montant' => 'nullable|numeric|min:0',
                'statut' => 'nullable|in:impayee,payee'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Erreur de validation',
                    'errors' => $validator->errors()
                ], 400);
            }

            if ($request->has('montant')) {
                $facture->montant = $request->montant;
            }
            if ($request->has('statut')) {
                $facture->statut = $request->statut;
            }

            $facture->save();

            return response()->json([
                'status' => 'success',
                'data' => $facture
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de la mise à jour de la facture'
            ], 500);
        }
    }


    public function destroy(string $id)
    {
        try {
            $facture = Facture::findOrFail($id);
            $facture->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Facture supprimée avec succès'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de la suppression de la facture'
            ], 500);
        }
    }
}
