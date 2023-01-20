<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\ClienteController;
use App\Http\Controllers\Api\CarroController;
use App\Http\Controllers\Api\LocacaoController;
use App\Http\Controllers\Api\MarcaController;
use App\Http\Controllers\Api\ModeloController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::apiResource('cliente', 'App\Http\Controllers\ClienteController');
Route::middleware('jwt.auth')->prefix('/v1')->group(function () {
    Route::post('me', [AuthController::class, 'me']);
    Route::post('logout', [AuthController::class, 'logout']);

    Route::apiResource('cliente', ClienteController::class);
    Route::apiResource('carro', CarroController::class);
    Route::apiResource('locacao', LocacaoController::class);
    Route::apiResource('marca', MarcaController::class);
    Route::apiResource('modelo', ModeloController::class);
});

Route::post('login', [AuthController::class, 'login']);
Route::post('refresh', [AuthController::class, 'refresh']);
