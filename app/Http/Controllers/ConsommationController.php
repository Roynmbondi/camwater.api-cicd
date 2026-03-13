<?php

namespace App\Http\Controllers;

use App\Models\Consommation;
use App\Models\Facture;
use App\Models\Abonne;
use App\Services\TarificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ConsommationController extends Controller
{
    protected $tarificationService;

    public function __construct(TarificationService $tarificationService)
    {
        $this->tarificationService = $tarificationService;
    }


    public function index()
    {
        try {
            $consommations = Consommation::with(['abonne', 'operateur', 'facture'])->get();

            return response()->json([
                'status' => 'success',
                'data' => $consommations
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de la récupération des consommations'
            ], 500);
        }
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'abonne_id' => 'required|exists:abonnes,id',
            'index_precedent' => 'required|integer|min:0',
            'index_actuel' => 'required|integer|min:0',
            'date_releve' => 'required|date',
            'operateur_id' => 'required|exists:operateurs,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 400);
        }

        if ($request->index_actuel < $request->index_precedent) {
            return response()->json([
                'status' => 'error',
                'message' => 'L\'index actuel doit être supérieur ou égal à l\'index précédent'
            ], 400);
        }

        try {
            DB::beginTransaction();

            $consommation_calculee = $request->index_actuel - $request->index_precedent;

            $consommation = Consommation::create([
                'abonne_id' => $request->abonne_id,
                'index_precedent' => $request->index_precedent,
                'index_actuel' => $request->index_actuel,
                'consommation' => $consommation_calculee,
                'date_releve' => $request->date_releve,
                'operateur_id' => $request->operateur_id
            ]);

            // Récupérer l'abonné pour calculer le montant selon son type
            $abonne = Abonne::findOrFail($request->abonne_id);
            $montant_facture = $this->tarificationService->calculerMontant($abonne, $consommation_calculee);

            $facture = Facture::create([
                'abonne_id' => $request->abonne_id,
                'consommation_id' => $consommation->id,
                'montant' => $montant_facture,
                'date_generation' => now(),
                'statut' => 'impayee',
                'operateur_id' => $request->operateur_id
            ]);

            DB::commit();

            $consommation->load(['abonne', 'operateur', 'facture']);

            // Ajouter le détail du calcul dans la réponse
            $detailCalcul = $this->tarificationService->getDetailCalcul($abonne, $consommation_calculee);

            return response()->json([
                'status' => 'success',
                'data' => [
                    'consommation' => $consommation,
                    'facture' => $facture,
                    'detail_calcul' => $detailCalcul
                ],
                'message' => 'Consommation enregistrée et facture générée avec succès'
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de la création de la consommation'
            ], 500);
        }
    }


    public function show(string $id)
    {
        try {
            $consommation = Consommation::with(['abonne', 'operateur', 'facture'])->findOrFail($id);

            return response()->json([
                'status' => 'success',
                'data' => $consommation
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Consommation non trouvée'
            ], 404);
        }
    }


    public function update(Request $request, string $id)
    {
        try {
            $consommation = Consommation::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'index_precedent' => 'nullable|integer|min:0',
                'index_actuel' => 'nullable|integer|min:0',
                'date_releve' => 'nullable|date'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Erreur de validation',
                    'errors' => $validator->errors()
                ], 400);
            }

            DB::beginTransaction();

            if ($request->has('index_precedent')) {
                $consommation->index_precedent = $request->index_precedent;
            }
            if ($request->has('index_actuel')) {
                $consommation->index_actuel = $request->index_actuel;
            }
            if ($request->has('date_releve')) {
                $consommation->date_releve = $request->date_releve;
            }

            $consommation->consommation = $consommation->index_actuel - $consommation->index_precedent;
            $consommation->save();

            // Recalculer le montant de la facture avec la tarification progressive
            if ($consommation->facture) {
                $abonne = Abonne::findOrFail($consommation->abonne_id);
                $nouveau_montant = $this->tarificationService->calculerMontant($abonne, $consommation->consommation);
                $consommation->facture->montant = $nouveau_montant;
                $consommation->facture->save();
            }

            DB::commit();

            $consommation->load(['abonne', 'operateur', 'facture']);

            return response()->json([
                'status' => 'success',
                'data' => $consommation
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de la mise à jour de la consommation'
            ], 500);
        }
    }

    
    public function destroy(string $id)
    {
        try {
            $consommation = Consommation::findOrFail($id);
            $consommation->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Consommation supprimée avec succès'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de la suppression de la consommation'
            ], 500);
        }
    }
}
