<?php

use App\Http\Controllers\Usuario\UsuarioController;
use Illuminate\Support\Facades\Route;

Route::controller(UsuarioController::class)->group(function () {
    Route::get('', 'index')->name('usuarios.lista');
    Route::post('', 'store')->name('usuarios.cria');
    Route::put('{uuid}', 'update')->name('usuarios.atualiza');
});
