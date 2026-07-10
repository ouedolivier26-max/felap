<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CalendrierController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ColieController;
use App\Http\Controllers\CommandesController;
use App\Http\Controllers\LivraisonController;
use App\Http\Controllers\LivreurController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\PaiementsController;
use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Authentification
|--------------------------------------------------------------------------
| Bug corrigé : les routes /login et /register appelaient les méthodes
| inversées (POST /register -> AuthController::login, POST /login ->
| AuthController::register). Un throttle a aussi été ajouté pour limiter
| le brute-force.
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginPage'])->name('login.page');
    Route::get('/register', [AuthController::class, 'showRegisterPage'])->name('register.page');

    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:6,1')
        ->name('login');

    Route::post('/register', [AuthController::class, 'register'])
        ->middleware('throttle:6,1')
        ->name('register');

    Route::get('/auth/google/redirect', [AuthController::class, 'redirectToGoogle'])->name('google.redirect');
    Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('google.callback');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

/*
|--------------------------------------------------------------------------
| Espace Admin
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'IsAdmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    Route::get('/commandes', [CommandesController::class, 'viewCommandesAdminPage'])->name('commandes');
    Route::post('/commandes', [CommandesController::class, 'ajouteCommande'])->name('commandes.store');
    Route::post('/commandes/{commande}/assigner-livreur', [CommandesController::class, 'assignerLivreur'])->name('commandes.assigner-livreur');

    Route::get('/livraison', [LivraisonController::class, 'viewLivraisonPage'])->name('livraison');
    Route::post('/livraison', [LivraisonController::class, 'ajouterLivreur'])->name('livraison.store');
    Route::delete('/livraison/delete/{livreur}', [LivraisonController::class, 'deleteLivreur'])->name('livraison.delete');

    Route::get('/clients', [AdminController::class, 'viewClientPage'])->name('clients');

    Route::get('/paiements', [PaiementsController::class, 'viewPaiementAdminPage'])->name('paiements');

    Route::get('/notifications', [NotificationsController::class, 'index'])->name('notifications');
    Route::post('/notifications/{notification}/lu', [NotificationsController::class, 'lireNotification'])->name('notifications.lu');
    Route::post('/notifications/all-lu', [NotificationsController::class, 'lireToutesNotifications'])->name('notifications.all-lu');
});

/*
|--------------------------------------------------------------------------
| Espace Livreur
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'IsLivreur'])->prefix('livreur')->name('livreur.')->group(function () {
    Route::get('/dashboard', [LivreurController::class, 'index'])->name('dashboard');

    Route::get('/commandes', [CommandesController::class, 'viewCommandesLivreurPage'])->name('commandes');
    Route::post('/commandes/{commande}/accepter', [CommandesController::class, 'accepterLivraison'])->name('commandes.accepter');
    Route::post('/commandes/{commande}/refuser', [CommandesController::class, 'refuserLivraison'])->name('commandes.refuser');

    Route::get('/colis', [ColieController::class, 'viewColisPage'])->name('colis');
    Route::post('/colis/ajouter', [ColieController::class, 'ajouterColis'])->name('colis.store');
    Route::put('/colis/{colis}/update-status', [ColieController::class, 'updateColisStatus'])->name('colis.update-status');

    Route::get('/calendrier', [CalendrierController::class, 'viewCalendrierPage'])->name('calendrier');
    Route::post('/calendrier/rendez-vous', [CalendrierController::class, 'ajouterRendezVous'])->name('rendez-vous.store');

    Route::get('/paiements', [PaiementsController::class, 'viewPaiementLivreurPage'])->name('paiements');
    Route::post('/paiements/ajouter', [PaiementsController::class, 'ajoutePaiement'])->name('paiements.store');

    Route::get('/notifications', [NotificationsController::class, 'index'])->name('notifications');
    Route::post('/notifications/{notification}/lu', [NotificationsController::class, 'lireNotification'])->name('notifications.lu');
    Route::post('/notifications/all-lu', [NotificationsController::class, 'lireToutesNotifications'])->name('notifications.all-lu');

    Route::get('/profile', [ProfileController::class, 'viewProfileLivreurPage'])->name('profile');
    Route::put('/profile/update-info', [ProfileController::class, 'updateLivreurProfile'])->name('profile.update');
    Route::put('/profile/update-pass', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::put('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo');
});

/*
|--------------------------------------------------------------------------
| Espace Client
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'IsClient'])->prefix('client')->name('client.')->group(function () {
    Route::get('/dashboard.client.profile', [ClientController::class, 'index'])->name('dashboard');

    Route::get('/notifications', [NotificationsController::class, 'index'])->name('notifications');
    Route::post('/notifications/{notification}/lu', [NotificationsController::class, 'lireNotification'])->name('notifications.lu');
    Route::post('/notifications/all-lu', [NotificationsController::class, 'lireToutesNotifications'])->name('notifications.all-lu');

    Route::get('/profile', [ProfileController::class, 'viewProfileClientPage'])->name('profile');
    Route::put('/profile/update-info', [ProfileController::class, 'updateClientProfile'])->name('profile.update');
    Route::put('/profile/update-pass', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::put('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo');

    Route::get('/ColieRechercher', [ClientController::class, 'rechercherColis'])->name('colis.recherche');
    Route::get('/colis/{colis}/pdf', [ClientController::class, 'downloadColisPdf'])->name('colis.pdf');
});

/*
|--------------------------------------------------------------------------
| Réinitialisation de mot de passe
|--------------------------------------------------------------------------
| Bug corrigé : noms de vues avec un espace ('auth.password request' /
| 'auth.password reset') remplacés par des noms valides. Ajustez le nom
| exact si vos fichiers Blade portent un autre nom.
| Ajout : middleware 'guest' (absent auparavant) + throttling.
*/
Route::middleware('guest')->group(function () {
    Route::get('forgot-password', function () {
        return view('auth.password request');
    })->name('password.request');

    Route::post('forgot-password', function (Request $request) {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    })->middleware('throttle:6,1')->name('password.email');

    Route::get('reset-password/{token}', function (string $token) {
        return view('auth.password reset', [
            'token' => $token,
            'email' => request('email'),
        ]);
    })->name('password.reset');

    Route::post('reset-password', function (Request $request) {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    // Hash::make() plutôt que bcrypt() pour rester cohérent
                    // avec le reste de l'application et respecter le driver
                    // de hashing configuré dans config/hashing.php.
                    'password' => Hash::make($password),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login.page')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    })->middleware('throttle:6,1')->name('password.update');
}); 
