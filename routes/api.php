<?php

use App\Http\Controllers\ExternalApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

/*
|--------------------------------------------------------------------------
| Projeto 3 - API
|--------------------------------------------------------------------------
| Recebe o body do Projeto 2, valida, gera um arquivo a partir dos dados
| (com CACHE no Redis) e retorna JSON 200.
*/
Route::post('/external', [ExternalApiController::class, 'processData']);
