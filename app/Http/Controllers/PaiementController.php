<?php

namespace App\Http\Controllers;

use App\Models\Paiement;
use App\Models\Facture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class PaiementController extends Controller
{
   
    public function index()
    {
        try {
            $paiements = Paiement::with(['facture', 'operateur'])->get();

            return response()->json([
                'status' => 'success',
                'data' => $paiements
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de la récupération des paiements'
            ], 500);
        }
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'facture_id' => 'required|exists:factures,id',
            'montant' => 'required|numeric|min:0',
            'date_paiement' => 'required|date',
            'mode_paiement' => 'required|in:cash,mobile_money,virement',
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
            $facture = Facture::findOrFail($request->facture_id);

            if ($facture->statut === 'payee') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Cette facture a déjà été payée'
                ], 400);
            }

            DB::beginTransaction();

            $paiement = Paiement::create([
                'facture_id' => $request->facture_id,
                'montant' => $request->montant,
                'date_paiement' => $request->date_paiement,
                'mode_paiement' => $request->mode_paiement,
                'operateur_id' => $request->operateur_id
            ]);

            $facture->statut = 'payee';
            $facture->save();

            DB::commit();

            $paiement->load(['facture', 'operateur']);

            return response()->json([
                'status' => 'success',
                'data' => $paiement,
                'message' => 'Paiement enregistré avec succès et facture marquée comme payée'
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de l\'enregistrement du paiement'
            ], 500);
        }
    }


    public function show(string $id)
    {
        try {
            $paiement = Paiement::with(['facture.abonne', 'operateur'])->findOrFail($id);

            return response()->json([
                'status' => 'success',
                'data' => $paiement
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Paiement non trouvé'
            ], 404);
        }
    }


    public function update(Request $request, string $id)
    {
        try {
            $paiement = Paiement::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'montant' => 'nullable|numeric|min:0',
                'date_paiement' => 'nullable|date',
                'mode_paiement' => 'nullable|in:cash,mobile_money,virement'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Erreur de validation',
                    'errors' => $validator->errors()
                ], 400);
            }

            if ($request->has('montant')) {
                $paiement->montant = $request->montant;
            }
            if ($request->has('date_paiement')) {
                $paiement->date_paiement = $request->date_paiement;
            }
            if ($request->has('mode_paiement')) {
                $paiement->mode_paiement = $request->mode_paiement;
            }

            $paiement->save();

            return response()->json([
                'status' => 'success',
                'data' => $paiement
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de la mise à jour du paiement'
            ], 500);
        }
    }


    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();

            $paiement = Paiement::findOrFail($id);

            $facture = $paiement->facture;

            $paiement->delete();

            if ($facture) {
                $facture->statut = 'impayee';
                $facture->save();
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Paiement supprimé avec succès et facture remise en statut impayée'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de la suppression du paiement'
            ], 500);
        }
    }
}
