<?php

namespace App\Http\Controllers;

use App\Models\Operateur;
use App\Models\TokenBlacklist;
use App\Services\LogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{

    public function register(Request $request)
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
                'message' => 'Opérateur créé avec succès',
                'data' => $operateur
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de la création de l\'opérateur'
            ], 500);
        }
    }


    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'mot_de_passe' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 400);
        }

        $credentials = [
            'email' => $request->email,
            'password' => $request->mot_de_passe
        ];

        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email ou mot de passe incorrect'
            ], 401);
        }

        // Logger la connexion
        $user = auth('api')->user();
        LogService::logLogin($user->id, $user->email);

        return $this->respondWithToken($token);
    }


    public function me()
    {
        return response()->json([
            'status' => 'success',
            'data' => auth('api')->user()
        ]);
    }



    public function logout()
    {
        try {
            // Logger la déconnexion avant d'invalider le token
            LogService::logLogout();

            // Récupérer le token actuel
            $token = JWTAuth::getToken();
            $payload = JWTAuth::getPayload($token);

            // Ajouter le token à la blacklist
            TokenBlacklist::create([
                'token' => $token->get(),
                'jti' => $payload->get('jti'),
                'expires_at' => now()->addMinutes(auth('api')->factory()->getTTL()),
                'operateur_id' => auth('api')->id()
            ]);

            // Invalider le token
            auth('api')->logout();

            return response()->json([
                'status' => 'success',
                'message' => 'Déconnexion réussie. Token révoqué.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de la déconnexion'
            ], 500);
        }
    }

   
    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'status' => 'success',
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'operateur' => auth('api')->user()
        ]);
    }
}
