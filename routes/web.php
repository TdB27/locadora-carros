<?php

use App\Http\Controllers\Servicos\CarrosController;
use App\Http\Controllers\Servicos\MarcasController;
use App\Http\Controllers\Servicos\ModelosController;
use Illuminate\Support\Facades\Auth;
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
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware('auth')->prefix('/')->group(function () {
    Route::get('marcas', [MarcasController::class, 'index'])->name('marcas');
    Route::get('modelos', [ModelosController::class, 'index'])->name('modelos');
    Route::get('carros', [CarrosController::class, 'index'])->name('carros');
});
