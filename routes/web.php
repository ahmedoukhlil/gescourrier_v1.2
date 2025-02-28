<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\CourriersEntrantsController;
use App\Http\Controllers\CourriersSortantsController;
use App\Http\Controllers\DestinataireController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('courriers.index');
});

// Route pour afficher les documents
Route::get('/document/view/{path}', function ($path) {
    if (Storage::disk('public')->exists($path)) {
        return response()->file(storage_path('app/public/' . $path));
    }
    abort(404);
})->where('path', '.*')->name('document.view');

// Routes pour les courriers entrants
Route::prefix('courriers')->group(function () {
    Route::get('/', [CourriersEntrantsController::class, 'index'])->name('courriers.index');
    // Vous pouvez ajouter d'autres routes ici si nécessaire
});