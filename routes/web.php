<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EtatController;
use App\Http\Controllers\SexeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PersonneController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Page Home
Route::get('/', [PageController::class, 'home'])->name('home');

//Page vitrine
Route::get('vitrine', [PageController::class, 'vitrine'])->name('vitrine');

//Formulaire Connexion
Route::get('connexion', [AuthController::class, 'loginForm'])->name('login_form');

//Action Connexion
Route::post('Connexion', [AuthController::class, 'loginAction'])->name('login_action');

//Formulaire Inscription
Route::get('inscription', [AuthController::class, 'registerForm'])->name('register_form');

//Action Inscription
Route::post('Inscription', [AuthController::class, 'registerAction'])->name('register_action');

//Formulaire Mot de passe oublié
Route::get('mot de passe oublié', [AuthController::class, 'forgetPwdForm'])->name('forget_pwd_form');

//Action mot de passe oublié
Route::post('Mot de passe oublié', [AuthController::class, 'forgetPwdAction'])->name('forget_pwd_action');

//Action Deconnexion
Route::get('déconnexion', [AuthController::class, 'logout'])->name('logout');

//Page de formulaire de sexe + etat + paiement
Route::get('configurtion', [PageController::class, 'configForm'])->name('config_form');

Route::get('paiement', [PageController::class, 'paiementForm'])->name('paie_form');

//Route::get('listeEtat', [PageController::class, 'listeEtats'])->name('etat');

//Route::get('listeEtat', [PageController::class, 'listeEtat']);

//Route::get('/modifEtat{id}', [PageController::class, 'showEditEtat']);

//Route::post('/modifEtat{id}', [PageController::class, 'modifEtat']);

//Route::post('/supprimerEtat{id}', [PageController::class, 'deleteEtat']);


//Action sexe + etat

Route::resource("/Configuration", SexeController::class);

Route::resource("/Liste_Etat", EtatController::class);


//Route::post('etatAdd', [PageController::class, 'addEtat'])->name('register_etat_action');


//Middleware pour la protection des pages
Route::middleware(['Connection'])->group(function () {

    //Page Index
    Route::get('ma page', [PageController::class, 'indexUser'])->name('user_index');

    //Page Mon réseau
    Route::get('dashboard', [PageController::class, 'dashboard'])->name('dashboard');

    //Page mon profil
    Route::get('mon profil', [PageController::class, 'monProfil'])->name('profile');

    //Resource personne
    Route::resource('personnes', PersonneController::class)->names([
        // 'create' => 'add_person',
        // 'index'  => 'list_persons',
        'edit'   => 'edit_person',
    ]);

    //Action mise à jour information de connexion
    Route::put('Mise à jour information utilisateur/{id}', [AuthController::class, 'updateUser'])->name('user_update');
});
