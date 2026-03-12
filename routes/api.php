<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

Route::controller(AuthController::class)->prefix('auth')->group(function () {
    Route::post('login', 'login')->name('auth.login');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', 'logout')->name('auth.logout');
        Route::get('me', 'me')->name('auth.me');
    });
});

Route::get('', fn () => response()->json(['Hello World']));
