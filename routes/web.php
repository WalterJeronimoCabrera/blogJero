<?php

use App\Http\Controllers\ComentarioController;
use App\Http\Controllers\MeGustaController;
use App\Http\Controllers\PosteoController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::apiResource('/posteos', PosteoController::class);
Route::apiResource('/users', UserController::class);
Route::apiResource('/meGustas', MeGustaController::class);
Route::apiResource('/comentarios', ComentarioController::class);
Route::get('/user', [App\Http\Controllers\UserController::class, 'loggeado'])->name('user');
Route::get('/miCuenta', function () {
    return view('miCuenta');
});
Route::get('/posteo/{id?}', function () {
    return view('posteo');
});