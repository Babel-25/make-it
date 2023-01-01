<?php

<<<<<<< HEAD
=======
use App\Http\Controllers\AuthController;
>>>>>>> 2cc119af9e396a4818755a869b0d4ba0a94cf550
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

<<<<<<< HEAD
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
=======

Route::post('register',[AuthController::class,'registerAction']);
Route::post('login',[AuthController::class,'loginAction']);


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
>>>>>>> 2cc119af9e396a4818755a869b0d4ba0a94cf550
