<?php

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

Route::get('/', function () {
    return view('acceuil');
});

Route::get('inscription', function () {
    return view('inscription');
});

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


Route::get('profil', function () {
    return view('mon_profil');
});

