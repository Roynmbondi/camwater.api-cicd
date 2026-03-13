<?php

namespace App\Http\Controllers;

use App\Models\Operateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class OperateurController extends Controller
{
    
    public function index()
    {
        try {
            $operateurs = Operateur::all();

            return response()->json([
                'status' => 'success',
                'data' => $operateurs
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de la récupération des opérateurs'
            ], 500);
        }
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'email' => 'required|email|unique:operateurs,email',
            'mot_de_passe' => 'required|string|min:6',
            'role' => 'nullable|string|in:operateur,admin'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            $operateur = Operateur::create([
                'nom' => $request->nom,
                'email' => $request->email,
                'mot_de_passe' => Hash::make($request->mot_de_passe),
                'role' => $request->role ?? 'operateur'
            ]);

            return response()->json([
                'status' => 'success',
                'data' => $operateur
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de la création de l\'opérateur'
            ], 500);
        }
    }


    public function show(string $id)
    {
        try {
            $operateur = Operateur::findOrFail($id);

            return response()->json([
                'status' => 'success',
                'data' => $operateur
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Opérateur non trouvé'
            ], 404);
        }
    }


    public function update(Request $request, string $id)
    {
        try {
            $operateur = Operateur::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'nom' => 'nullable|string|max:255',
                'email' => 'nullable|email|unique:operateurs,email,' . $id,
                'mot_de_passe' => 'nullable|string|min:6',
                'role' => 'nullable|string|in:operateur,admin'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Erreur de validation',
                    'errors' => $validator->errors()
                ], 400);
            }

            if ($request->has('nom')) {
                $operateur->nom = $request->nom;
            }
            if ($request->has('email')) {
                $operateur->email = $request->email;
            }
            if ($request->has('mot_de_passe')) {
                $operateur->mot_de_passe = Hash::make($request->mot_de_passe);
            }
            if ($request->has('role')) {
                $operateur->role = $request->role;
            }

            $operateur->save();

            return response()->json([
                'status' => 'success',
                'data' => $operateur
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de la mise à jour de l\'opérateur'
            ], 500);
        }
    }


    public function destroy(string $id)
    {
        try {
            $operateur = Operateur::findOrFail($id);
            $operateur->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Opérateur supprimé avec succès'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de la suppression de l\'opérateur'
            ], 500);
        }
    }
}
