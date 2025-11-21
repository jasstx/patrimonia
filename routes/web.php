<?php

use App\Models\Detenteur;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DemandeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GestionnaireController;
use App\Http\Controllers\PieceJointeController;
use App\Http\Controllers\ProfilController;

// Routes publiques
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Route de test pour le formulaire
Route::get('/test-form', function () {
    return view('test-form');
})->name('test.form');

// Routes pour les patrimoines (publiques)
Route::get('/patrimoines', function () {
    return view('patrimoines.index');
})->name('patrimoines.index');

// Routes pour les détenteurs (publiques)
use App\Http\Controllers\DetenteurController;
use App\Http\Controllers\PhotoController;

Route::get('/detenteurs', [DetenteurController::class, 'index'])->name('detenteurs.index');
Route::get('/detenteurs/{id_detenteur}', [DetenteurController::class, 'show'])->name('detenteurs.show');

// API pour récupérer les données du détenteur (pour le popup)
Route::get('/api/detenteurs/{id_detenteur}', [DetenteurController::class, 'apiShow'])->name('api.detenteurs.show');

// Routes pour la gestion des photos des détenteurs
Route::post('/detenteurs/{id_detenteur}/photo', [PhotoController::class, 'upload'])->name('detenteurs.photo.upload');
Route::get('/storage/photos/{filename}', [PhotoController::class, 'show'])->name('detenteurs.photo.show');

// Route de test simple
Route::get('/test-basique', function() {
    return '<h1>Le serveur fonctionne !</h1><p>Timestamp: ' . now() . '</p>';
});

// Route de test pour les photos des détenteurs
Route::get('/test-photos', [\App\Http\Controllers\TestController::class, 'testPhotos'])->name('test.photos');

// Route de débogage pour le stockage
Route::get('/debug/storage', 'App\Http\Controllers\DebugController@checkStorage');

// Routes publiques pour les demandes (liste et consultation)
Route::prefix('demande')->name('demande.')->group(function () {
    Route::get('/liste', [DemandeController::class, 'index'])->name('index');
});


// Pièces jointes
Route::get('/pieces-jointes/{piece}/download', [PieceJointeController::class, 'download'])
    ->name('pieces-jointes.download');

// AUTH
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// INSCRIPTION pour les demandeurs
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// Déconnexion
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('home')->with('success', 'Vous avez été déconnecté.');
})->name('logout');

// Routes authentifiées (pour tous les utilisateurs connectés)
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/statistiques', [AdminController::class, 'statistiques'])->name('admin.statistiques');
});
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

    // Routes de profil
    Route::get('/mon-profil', [ProfilController::class, 'index'])->name('profil.index');
    Route::get('/mon-profil/demandes/{id}/modifier', [ProfilController::class, 'edit'])->name('profil.edit');
    Route::put('/mon-profil/demandes/{id}', [ProfilController::class, 'update'])->name('profil.update');
    Route::delete('/mon-profil/demandes/{id}', [ProfilController::class, 'destroy'])->name('profil.destroy');

    // Routes pour créer une demande (authentifiées) - doivent être AVANT la route générique /{id}
    Route::prefix('demande')->name('demande.')->group(function () {
        Route::get('/creer', [DemandeController::class, 'create'])->name('create');
        Route::post('/creer', [DemandeController::class, 'store'])->name('store');
        Route::get('/confirmation', [DemandeController::class, 'confirmation'])->name('confirmation');
    });

    // Route générique pour voir une demande (doit être après les routes spécifiques)
    Route::get('/demande/{id}', [DemandeController::class, 'show'])->name('demande.show');

    // Routes de profil temporaires
    Route::get('/profile', function () {
        return 'Page de profil - À implémenter';
    })->name('profile.edit');
});

// Routes administrateur
if (app()->environment('local')) {
    Route::prefix('admin')->name('admin.')->middleware(['web','auth','role:admin'])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/utilisateurs', [AdminController::class, 'utilisateurs'])->name('utilisateurs');
        Route::post('/utilisateurs', [AdminController::class, 'creerUtilisateur'])->name('utilisateurs.creer');
        Route::delete('/utilisateurs/{user}', [AdminController::class, 'supprimerUtilisateur'])->name('utilisateurs.supprimer');
        Route::post('/utilisateurs/{user}/activer', [AdminController::class, 'activerUtilisateur'])->name('utilisateurs.activer');
        Route::post('/utilisateurs/{user}/desactiver', [AdminController::class, 'desactiverUtilisateur'])->name('utilisateurs.desactiver');
        Route::post('/utilisateurs/{user}/type', [AdminController::class, 'mettreAJourType'])->name('utilisateurs.type');
        Route::post('/utilisateurs/{user}/reset-mdp', [AdminController::class, 'reinitialiserMotDePasse'])->name('utilisateurs.reset-mdp');
        Route::get('/utilisateurs/export/csv', [AdminController::class, 'exportUtilisateurs'])->name('utilisateurs.export');
        Route::post('/utilisateurs/{user}/attribuer-role', [AdminController::class, 'attribuerRole'])->name('utilisateurs.attribuer-role');
        Route::get('/roles', [AdminController::class, 'roles'])->name('roles');
        Route::post('/roles', [AdminController::class, 'creerRole'])->name('roles.creer');
        Route::delete('/roles/{role}', [AdminController::class, 'supprimerRole'])->name('roles.supprimer');
        Route::get('/permissions', [AdminController::class, 'permissions'])->name('permissions');
        Route::post('/permissions', [AdminController::class, 'creerPermission'])->name('permissions.creer');
        Route::delete('/permissions/{permission}', [AdminController::class, 'supprimerPermission'])->name('permissions.supprimer');
        Route::post('/permissions/assigner', [AdminController::class, 'assignerPermission'])->name('permissions.assigner');
        Route::delete('/roles/{role}/permissions/{permission}', [AdminController::class, 'retirerPermission'])->name('permissions.retirer');
        Route::get('/patrimoines', [AdminController::class, 'patrimoines'])->name('patrimoines');
        Route::get('/categories', [AdminController::class, 'categories'])->name('categories');
        Route::post('/categories', [AdminController::class, 'creerCategorie'])->name('categories.creer');
        Route::delete('/categories/{categorie}', [AdminController::class, 'supprimerCategorie'])->name('categories.supprimer');
    });
} else {
    Route::prefix('admin')->name('admin.')->middleware(['web','auth', 'role:admin'])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/utilisateurs', [AdminController::class, 'utilisateurs'])->name('utilisateurs');
        Route::post('/utilisateurs/{user}/attribuer-role', [AdminController::class, 'attribuerRole'])->name('utilisateurs.attribuer-role');
        Route::get('/roles', [AdminController::class, 'roles'])->name('roles');
        Route::post('/roles', [AdminController::class, 'creerRole'])->name('roles.creer');
        Route::delete('/roles/{role}', [AdminController::class, 'supprimerRole'])->name('roles.supprimer');
        Route::get('/patrimoines', [AdminController::class, 'patrimoines'])->name('patrimoines');
        Route::get('/categories', [AdminController::class, 'categories'])->name('categories');
        Route::post('/categories', [AdminController::class, 'creerCategorie'])->name('categories.creer');
        Route::delete('/categories/{categorie}', [AdminController::class, 'supprimerCategorie'])->name('categories.supprimer');
    });
}

// Routes gestionnaire (sans auth en local pour faciliter les tests)
if (app()->environment('local')) {
    Route::prefix('gestionnaire')->name('gestionnaire.')->group(function () {
        Route::get('/dashboard', [GestionnaireController::class, 'dashboard'])->name('dashboard');
        Route::get('/demandes', [GestionnaireController::class, 'demandes'])->name('demandes');
        Route::get('/demandes/{id}', [GestionnaireController::class, 'show'])->name('demandes.show');
        Route::post('/demandes/{id}/valider', [GestionnaireController::class, 'valider'])->name('demandes.valider');
        Route::post('/demandes/{id}/rejeter', [GestionnaireController::class, 'rejeter'])->name('demandes.rejeter');
        Route::get('/detenteurs', function () { return redirect()->route('gestionnaire.demandes'); })->name('detenteurs');
        Route::get('/detenteurs-valides', [GestionnaireController::class, 'detenteursValides'])->name('detenteurs.valides');
        Route::get('/detenteurs-refuses', [GestionnaireController::class, 'detenteursRefuses'])->name('detenteurs.refuses');
    });
} else {
    Route::prefix('gestionnaire')->name('gestionnaire.')->middleware(['auth', 'role:gestionnaire'])->group(function () {
        Route::get('/dashboard', [GestionnaireController::class, 'dashboard'])->name('dashboard');
        Route::get('/demandes', [GestionnaireController::class, 'demandes'])->name('demandes');
        Route::get('/demandes/{id}', [GestionnaireController::class, 'show'])->name('demandes.show');
        Route::post('/demandes/{id}/valider', [GestionnaireController::class, 'valider'])->name('demandes.valider');
        Route::post('/demandes/{id}/rejeter', [GestionnaireController::class, 'rejeter'])->name('demandes.rejeter');
        Route::get('/detenteurs-valides', [GestionnaireController::class, 'detenteursValides'])->name('detenteurs.valides');
        Route::get('/detenteurs-refuses', [GestionnaireController::class, 'detenteursRefuses'])->name('detenteurs.refuses');
    });
}

// SUPPRIMEZ CETTE LIGNE :
// require __DIR__.'/auth.php';
