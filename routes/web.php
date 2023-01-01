<?php

<<<<<<< HEAD
=======
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PageController;
>>>>>>> 2cc119af9e396a4818755a869b0d4ba0a94cf550
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

<<<<<<< HEAD
Route::get('/', function () {
    return view('acceuil');
});
=======
/** PREVOIR UN MIDDLEWARE POUR BLOQUER LES PAGES DONT L'ACCES REQUIERT UNE CONNEXION 😁 😁 😁 */

//Page Home
Route::get('/', [PageController::class, 'accueil'])->name('accueil');

//Page vitrine
Route::get('vitrine', [PageController::class, 'vitrine'])->name('vitrine');

//Page Formulaire  Inscription

//Formulaire Connexion
Route::get('connexion', [AuthController::class, 'loginForm'])->name('login_form');

//Action Connexion
Route::post('Connexion', [AuthController::class, 'loginAction'])->name('login_action');

//Formulaire Mot de passe oublié
Route::get('mot de passe oublié', [AuthController::class, 'forgetPwdForm'])->name('forget_pwd_form');

//------------ Page  utilisateur

//Page Index
Route::get('ma page', [PageController::class, 'indexUser'])->name('user_index');
//Page Mon réseau
Route::get('dashboard', [PageController::class, 'dashboard'])->name('dashboard');
>>>>>>> 2cc119af9e396a4818755a869b0d4ba0a94cf550

Route::get('inscription', function () {
    return view('inscription');
});

<<<<<<< HEAD
Route::get('acceuil', function () {
    return view('acceuil');
});

Route::get('connexion', function () {
    return view('connexion');
});

Route::get('mpOublier', function () {
    return view('mpOublier');
});

Route::get('Monreseau', function () {
    return view('ma_page');
});

Route::get('vitrine', function () {
    return view('vitrine');
});

Route::get('user', function () {
    return view('user');
});

=======
//Action Deconnexion
Route::get('déconnexion', [AuthController::class, 'logout'])->name('logout');

// Route::get('acceuil', function () {
//     return view('acceuil');
// });


// Route::get('Monreseau', function () {
//     return view('ma_page');
// });

// Route::get('user', function () {
//     return view('user');
// });
>>>>>>> 2cc119af9e396a4818755a869b0d4ba0a94cf550

Route::get('profil', function () {
    return view('mon_profil');
});

Route::get('inscrit1', function () {
<<<<<<< HEAD
    return view('inscrit1');
});

Route::get('inscrit2', function () {
    return view('inscrit2');
});

=======
    return view('auth.inscrit1');
});

Route::get('inscrit2', function () {
    return view('auth.inscrit2');
});
>>>>>>> 2cc119af9e396a4818755a869b0d4ba0a94cf550
