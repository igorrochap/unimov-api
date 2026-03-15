<?php

use App\Http\Controllers\Periodo\PeriodoController;
use Illuminate\Support\Facades\Route;

Route::controller(PeriodoController::class)->group(function () {
    Route::get('', 'index')->name('periodos.lista');
    Route::post('', 'store')->name('periodos.cria');
    Route::put('{id}', 'update')->name('periodos.atualiza');
    Route::delete('{id}', 'destroy')->name('periodos.deleta');
});
