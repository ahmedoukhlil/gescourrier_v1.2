<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\CourriersEntrantsController;
use App\Http\Controllers\CourrierSortantController;
use App\Http\Controllers\DestinataireController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\CourrierAnnotationController;
use App\Http\Controllers\CourrierShareController;
use App\Http\Controllers\LecteurResponseDraftController;
use App\Http\Controllers\ResponseDraftExchangeController;

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
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    
    // Route pour afficher les documents (tous les utilisateurs authentifiés)
    Route::get('/document/view/{path}', function ($path) {
        if (Storage::disk('public')->exists($path)) {
            return response()->file(storage_path('app/public/' . $path));
        }
        abort(404);
    })->where('path', '.*')->name('document.view');
    
    // Routes pour les courriers partagés (accessibles par tous les utilisateurs)
    Route::get('/courriers/shared', [CourrierShareController::class, 'index'])->name('courriers.shared');
    Route::get('/courriers/shared/{share}', [CourrierShareController::class, 'show'])->name('courriers.shared.show');
    Route::post('/courriers/{courrier}/mark-as-read', [CourrierShareController::class, 'markAsRead'])->name('courriers.mark-as-read');
    
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
    });
    
    // Routes pour les annotations et partages (gestionnaires uniquement)
    Route::middleware(['role:admin,gestionnaire'])->group(function () {
        Route::get('/courriers/{courrier}/annotate', [CourrierAnnotationController::class, 'create'])->name('courriers.annotations.create');
        Route::post('/courriers/{courrier}/annotations', [CourrierAnnotationController::class, 'store'])->name('courriers.annotations.store');
        Route::delete('/annotations/{annotation}', [CourrierAnnotationController::class, 'destroy'])->name('courriers.annotations.destroy');
        Route::delete('/shares/{share}', [CourrierShareController::class, 'destroy'])->name('courriers.shares.destroy');
    });
    
    // Routes pour les utilisateurs avec des permissions de suppression
    Route::middleware(['can:delete-courriers'])->group(function () {
        Route::delete('/courriers/{courrier}', [CourriersEntrantsController::class, 'destroy'])->name('courriers.destroy');
        Route::delete('/courriers-sortants/{courrierSortant}', [CourrierSortantController::class, 'destroy'])->name('courriers-sortants.destroy');
        Route::delete('/destinataires/{destinataire}', [DestinataireController::class, 'destroy'])->name('destinataires.destroy');
    });
    
    // Routes pour les administrateurs uniquement
    Route::middleware([\App\Http\Middleware\CheckRole::class.':admin'])->group(function () {
        // Gestion des utilisateurs
        Route::resource('users', UserController::class);
        
        // Gestion des rôles
        Route::resource('roles', RoleController::class);
    });
});
// Route pour afficher les documents (tous les utilisateurs authentifiés)
Route::get('/document/view/{path}', function ($path) {
    if (Storage::disk('public')->exists($path)) {
        return response()->file(storage_path('app/public/' . $path));
    }
    abort(404);
})->where('path', '.*')->name('document.view');

// Route pour télécharger les documents (tous les utilisateurs authentifiés)
Route::get('/document/download/{path}', function ($path) {
    if (Storage::disk('public')->exists($path)) {
        return response()->download(storage_path('app/public/' . $path));
    }
    abort(404);
})->where('path', '.*')->name('document.download');
// Ajouter à routes/web.php dans le groupe middleware 'auth'

// Routes pour les projets de réponse des lecteurs
Route::middleware(['can:view-courriers'])->group(function () {
    // Créer un projet de réponse
    Route::get('/courriers/{courrier}/response-draft/create', [LecteurResponseDraftController::class, 'create'])
        ->name('lecteur-response-drafts.create');
    
    // Stocker un projet de réponse
    Route::post('/courriers/{courrier}/response-draft', [LecteurResponseDraftController::class, 'store'])
        ->name('lecteur-response-drafts.store');
    
    // Voir son propre projet de réponse
    Route::get('/response-draft/{draft}', [LecteurResponseDraftController::class, 'show'])
        ->name('lecteur-response-drafts.show');
    
    // Supprimer son propre projet de réponse
    Route::delete('/response-draft/{draft}', [LecteurResponseDraftController::class, 'destroy'])
        ->name('lecteur-response-drafts.destroy');
});

// Routes pour la gestion des projets de réponse (admin, gestionnaire)
Route::middleware(['can:annotate-courriers'])->group(function () {
    // Voir tous les projets de réponse pour un courrier
    Route::get('/courriers/{courrier}/response-drafts', [LecteurResponseDraftController::class, 'index'])
        ->name('lecteur-response-drafts.index');
    
    // Examiner un projet de réponse
    Route::post('/response-draft/{draft}/review', [LecteurResponseDraftController::class, 'review'])
        ->name('lecteur-response-drafts.review');
});
// Pour les gestionnaires
Route::post('/response-draft/{draft}/review', [LecteurResponseDraftController::class, 'review'])
    ->name('lecteur-response-drafts.review');

// Pour les lecteurs
Route::post('/response-draft/{draft}/revision', [LecteurResponseDraftController::class, 'addRevision'])
    ->name('lecteur-response-drafts.add-revision');
    // À ajouter dans le fichier routes/web.php

// Routes pour les échanges sur les projets de réponse
Route::middleware(['auth'])->group(function () {
    // Formulaire pour créer un nouvel échange
    Route::get('/response-draft/{draft}/exchanges/create', [ResponseDraftExchangeController::class, 'create'])
        ->name('response-draft-exchanges.create');
    
    // Enregistrer un nouvel échange
    Route::post('/response-draft/{draft}/exchanges', [ResponseDraftExchangeController::class, 'store'])
        ->name('response-draft-exchanges.store');
    
    // Historique des échanges
    Route::get('/response-draft/{draft}/exchanges', [ResponseDraftExchangeController::class, 'history'])
        ->name('response-draft-exchanges.history');
});
Route::prefix('notifications')->name('notifications.')->group(function () {
    Route::get('/', [NotificationController::class, 'index'])->name('index');
    Route::get('/settings', [NotificationController::class, 'settings'])->name('settings');
    Route::get('/{id}', [NotificationController::class, 'show'])->name('show');
    Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
    Route::post('/{id}/mark-read', [NotificationController::class, 'markAsRead'])->name('mark-read');
    Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');
    Route::post('/delete-read', [NotificationController::class, 'destroyRead'])->name('destroy-read');
});