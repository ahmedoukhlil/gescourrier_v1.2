<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\CourriersEntrantsController;
use App\Http\Controllers\CourrierSortantController;
use App\Http\Controllers\DestinataireController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Routes publiques
Route::get('/', function () {
    return redirect()->route('login');
});

// Routes d'authentification générées par Breeze
require __DIR__.'/auth.php';

// Routes protégées par l'authentification
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    // Route pour afficher les documents (tous les utilisateurs authentifiés)
    Route::get('/document/view/{path}', function ($path) {
        if (Storage::disk('public')->exists($path)) {
            return response()->file(storage_path('app/public/' . $path));
        }
        abort(404);
    })->where('path', '.*')->name('document.view');
    
    // Routes pour les utilisateurs avec des permissions de lecture sur les courriers
    Route::middleware(['can:view-courriers'])->group(function () {
        // Routes pour les courriers entrants (lecture seule)
        Route::get('/courriers', [CourriersEntrantsController::class, 'index'])->name('courriers.index');
        Route::get('/courriers/{courrier}', [CourriersEntrantsController::class, 'show'])->name('courriers.show');
        
        // Routes pour les courriers sortants (lecture seule)
        Route::get('/courriers-sortants', [CourrierSortantController::class, 'index'])->name('courriers-sortants.index');
        Route::get('/courriers-sortants/{courrierSortant}', [CourrierSortantController::class, 'show'])->name('courriers-sortants.show');
    });
    
    // Routes pour les utilisateurs avec des permissions de gestion sur les courriers
    Route::middleware(['can:manage-courriers'])->group(function () {
        // Routes pour les courriers entrants (CRUD complet sauf suppression)
        Route::get('/courriers/create', [CourriersEntrantsController::class, 'create'])->name('courriers.create');
        Route::post('/courriers', [CourriersEntrantsController::class, 'store'])->name('courriers.store');
        Route::get('/courriers/{courrier}/edit', [CourriersEntrantsController::class, 'edit'])->name('courriers.edit');
        Route::put('/courriers/{courrier}', [CourriersEntrantsController::class, 'update'])->name('courriers.update');
        
        // Routes pour les courriers sortants (CRUD complet sauf suppression)
        Route::get('/courriers-sortants/{courrierSortant}/edit', [CourrierSortantController::class, 'edit'])->name('courriers-sortants.edit');
        Route::put('/courriers-sortants/{courrierSortant}', [CourrierSortantController::class, 'update'])->name('courriers-sortants.update');
        Route::patch('/courriers-sortants/{courrierSortant}/decharge', [CourrierSortantController::class, 'updateDecharge'])->name('courriers-sortants.updateDecharge');
        
        // Routes pour les destinataires
        Route::resource('destinataires', DestinataireController::class)->except(['destroy']);
    });
    
    // Routes pour les utilisateurs avec des permissions de suppression
    Route::middleware(['can:delete-courriers'])->group(function () {
        Route::delete('/courriers/{courrier}', [CourriersEntrantsController::class, 'destroy'])->name('courriers.destroy');
        Route::delete('/courriers-sortants/{courrierSortant}', [CourrierSortantController::class, 'destroy'])->name('courriers-sortants.destroy');
        Route::delete('/destinataires/{destinataire}', [DestinataireController::class, 'destroy'])->name('destinataires.destroy');
    });
    
    // Routes pour les administrateurs uniquement
    // Routes pour les administrateurs uniquement
Route::middleware([\App\Http\Middleware\CheckRole::class.':admin'])->group(function () {
    // Gestion des utilisateurs
    Route::resource('users', UserController::class);
    
    // Gestion des rôles
    Route::resource('roles', RoleController::class);
});
});