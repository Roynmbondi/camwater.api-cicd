<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\TokenBlacklist;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class CheckTokenBlacklist
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // Récupérer le token
            $token = JWTAuth::getToken();
            
            if (!$token) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Token non fourni'
                ], 401);
            }

            // Décoder le token pour obtenir le JTI
            $payload = JWTAuth::getPayload($token);
            $jti = $payload->get('jti');

            // Si le JTI n'existe pas, on laisse passer (token valide mais sans JTI)
            if (!$jti) {
                \Log::warning('Token sans JTI détecté', [
                    'payload' => $payload->toArray()
                ]);
                return $next($request);
            }

            // Vérifier si le token est blacklisté
            if (TokenBlacklist::isBlacklisted($jti)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Token révoqué. Veuillez vous reconnecter.'
                ], 401);
            }

        } catch (\PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Token expiré'
            ], 401);
        } catch (\PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Token invalide'
            ], 401);
        } catch (\PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException $e) {
            \Log::error('Erreur JWT dans CheckTokenBlacklist', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de la vérification du token: ' . $e->getMessage()
            ], 401);
        } catch (\Exception $e) {
            \Log::error('Erreur inattendue dans CheckTokenBlacklist', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de la vérification du token'
            ], 500);
        }

        return $next($request);
    }
}
