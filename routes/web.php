<?php

use Illuminate\Support\Facades\Route;

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Auth routes
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// Protected routes (require authentication via JS)
Route::middleware('web')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    // Abonnés
    Route::get('/abonnes', function () {
        return view('abonnes.index');
    })->name('web.abonnes.index');
    
    Route::get('/abonnes/create', function () {
        return view('abonnes.create');
    })->name('web.abonnes.create');
    
    Route::get('/abonnes/{id}', function ($id) {
        return view('abonnes.show', ['id' => $id]);
    })->name('web.abonnes.show');
    
    Route::get('/abonnes/{id}/edit', function ($id) {
        return view('abonnes.edit', ['id' => $id]);
    })->name('web.abonnes.edit');
    
    // Factures
    Route::get('/factures', function () {
        return view('factures.index');
    })->name('web.factures.index');
    
    // Réclamations
    Route::get('/reclamations', function () {
        return view('reclamations.index');
    })->name('web.reclamations.index');
    
    // Statistiques
    Route::get('/statistiques', function () {
        return view('statistiques');
    })->name('web.statistiques');
});

