<?php

use App\Http\Controllers\Usuario\UsuarioController;
use Illuminate\Support\Facades\Route;

Route::controller(UsuarioController::class)->group(function () {
    Route::post('', 'store')->name('usuarios.cria');
});
