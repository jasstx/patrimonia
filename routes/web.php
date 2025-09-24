<?php

use App\Models\Detenteur;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DemandeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GestionnaireController;

// Routes publiques
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Routes pour les patrimoines (publiques)
Route::get('/patrimoines', function () {
    return view('patrimoines.index');
})->name('patrimoines.index');

// Routes pour les détenteurs (publiques)
Route::get('/detenteurs', function () {
    $detenteurs = Detenteur::with(['demandeur', 'patrimoines'])->get();
    return view('detenteurs.index', compact('detenteurs'));
})->name('detenteurs.index');

// Routes pour les demandes (publiques)
Route::prefix('demande')->name('demande.')->group(function () {
    Route::get('/creer', [DemandeController::class, 'create'])->name('create');
    Route::post('/creer', [DemandeController::class, 'store'])->name('store');
    Route::get('/confirmation', [DemandeController::class, 'confirmation'])->name('confirmation');
    Route::get('/{id}', [DemandeController::class, 'show'])->name('show');
});

// ROUTES D'AUTHENTIFICATION BASIQUES
Route::get('/login', function () {
    return 'Page de connexion - À implémenter';
})->name('login');

Route::get('/register', function () {
    return 'Page d\'inscription - À implémenter';
})->name('register');

// Routes authentifiées (pour tous les utilisateurs connectés)
Route::middleware(['auth'])->group(function () { // Retirez 'verified' temporairement
    Route::get('/dashboard', function () {
        $user = Auth::user();

        if ($user->isAdministrateur()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isGestionnaire()) {
            return redirect()->route('gestionnaire.dashboard');
        } else {
            return view('dashboard');
        }
    })->name('dashboard');

    // Routes de profil temporaires
    Route::get('/profile', function () {
        return 'Page de profil - À implémenter';
    })->name('profile.edit');
});

// Routes administrateur
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    // ... autres routes admin
});

// Routes gestionnaire
Route::prefix('gestionnaire')->name('gestionnaire.')->middleware(['auth', 'role:gestionnaire'])->group(function () {
    Route::get('/dashboard', [GestionnaireController::class, 'dashboard'])->name('dashboard');
    // ... autres routes gestionnaire
});

// SUPPRIMEZ CETTE LIGNE :
// require __DIR__.'/auth.php';
