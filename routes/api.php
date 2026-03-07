<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('', fn () => response()->json(['Hello World']));

// Rota de Períodos
Route::prefix('periodos')->group(base_path('routes/api/periodos.php'));
