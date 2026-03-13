<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OperateurController;
use App\Http\Controllers\AbonneController;
use App\Http\Controllers\ConsommationController;
use App\Http\Controllers\FactureController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\ReclamationController;
use App\Http\Controllers\StatistiqueController;
use App\Http\Controllers\LogController;

// Routes d'authentification (publiques)
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    
    // Rate limiting sur login : 5 tentatives par minute
    Route::post('login', [AuthController::class, 'login'])
        ->middleware('throttle:5,1');
    
    // Routes protégées par JWT et blacklist
    Route::middleware(['auth:api', 'check.blacklist'])->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::get('me', [AuthController::class, 'me']);
    });
});

// Routes protégées par JWT et blacklist
Route::middleware(['auth:api', 'check.blacklist'])->group(function () {
    // Routes pour les opérateurs
    Route::apiResource('operateurs', OperateurController::class);

    // Routes pour les abonnés
    Route::apiResource('abonnes', AbonneController::class);

    // Routes pour les consommations
    Route::apiResource('consommations', ConsommationController::class);

    // Routes pour les factures
    Route::apiResource('factures', FactureController::class);

    // Routes pour les paiements
    Route::apiResource('paiements', PaiementController::class);

    // Routes pour les réclamations
    Route::apiResource('reclamations', ReclamationController::class);
    Route::post('reclamations/{id}/traiter', [ReclamationController::class, 'traiter']);
    Route::post('reclamations/{id}/resoudre', [ReclamationController::class, 'resoudre']);

    // Routes pour les statistiques
    Route::prefix('statistiques')->group(function () {
        Route::get('factures-par-ville', [StatistiqueController::class, 'facturesParVille']);
        Route::get('total-12-mois', [StatistiqueController::class, 'total12Mois']);
        Route::get('repartition-abonnes', [StatistiqueController::class, 'repartitionAbonnes']);
        Route::get('dashboard', [StatistiqueController::class, 'dashboard']);
        Route::post('clear-cache', [StatistiqueController::class, 'clearCache']);
    });

    // Routes pour les logs
    Route::prefix('logs')->group(function () {
        Route::get('/', [LogController::class, 'index']);
        Route::get('{id}', [LogController::class, 'show']);
        Route::get('entity/{entityType}/{entityId}', [LogController::class, 'entityLogs']);
        Route::post('clean', [LogController::class, 'clean']);
    });
});
