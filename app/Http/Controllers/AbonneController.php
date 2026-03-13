<?php

namespace App\Http\Controllers;

use App\Models\Abonne;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AbonneController extends Controller
{

    public function index()
    {
        try {
            $abonnes = Abonne::all();

            return response()->json([
                'status' => 'success',
                'data' => $abonnes
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de la récupération des abonnés'
            ], 500);
        }
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'telephone' => 'required|string|max:20',
            'adresse' => 'required|string',
            'numero_compteur' => 'required|string|unique:abonnes,numero_compteur',
            'type' => 'required|in:Domestique,Professionnel',
            'ville' => 'required|in:Yaoundé,Douala,Bafoussam,Garoua',
            'date_abonnement' => 'required|date'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            $abonne = Abonne::create($request->all());

            return response()->json([
                'status' => 'success',
                'data' => $abonne
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de la création de l\'abonné'
            ], 500);
        }
    }


    public function show(string $id)
    {
        try {
            $abonne = Abonne::with(['consommations', 'factures'])->findOrFail($id);

            return response()->json([
                'status' => 'success',
                'data' => $abonne
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Abonné non trouvé'
            ], 404);
        }
    }


    public function update(Request $request, string $id)
    {
        try {
            $abonne = Abonne::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'nom' => 'nullable|string|max:255',
                'prenom' => 'nullable|string|max:255',
                'telephone' => 'nullable|string|max:20',
                'adresse' => 'nullable|string',
                'numero_compteur' => 'nullable|string|unique:abonnes,numero_compteur,' . $id,
                'type' => 'nullable|in:Domestique,Professionnel',
                'ville' => 'nullable|in:Yaoundé,Douala,Bafoussam,Garoua',
                'date_abonnement' => 'nullable|date'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Erreur de validation',
                    'errors' => $validator->errors()
                ], 400);
            }

            $abonne->update($request->all());

            return response()->json([
                'status' => 'success',
                'data' => $abonne
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de la mise à jour de l\'abonné'
            ], 500);
        }
    }

    
    public function destroy(string $id)
    {
        try {
            $abonne = Abonne::findOrFail($id);
            $abonne->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Abonné supprimé avec succès'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de la suppression de l\'abonné'
            ], 500);
        }
    }
}
