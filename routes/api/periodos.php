<?php

use App\Http\Controllers\PeriodoController;
use Illuminate\Support\Facades\Route;

// Usar o prefixo 'periodos' ajuda a organizar a URL e evitar conflitos com outras entidades como 'usuarios'
Route::prefix('periodos')->name('periodos.')->controller(PeriodoController::class)->group(function () {

    Route::get('', 'index')->name('lista');
    Route::post('', 'store')->name('cria');

    // Escopo de Inscrição: /api/periodos/inscricao
    Route::post('inscricao', 'storePeriodoInscricao')->name('inscricao.cria');
    Route::put('inscricao/{id}', 'updatePeriodoInscricao')->name('inscricao.atualiza');

    // Escopo Letivo (Vigência): /api/periodos/letivo
    Route::post('letivo', 'storePeriodoLetivo')->name('letivo.cria');
    Route::put('letivo/{id}', 'updatePeriodoLetivo')->name('letivo.atualiza');

    Route::put('{id}', 'update')->name('atualiza');
    Route::delete('{id}', 'destroy')->name('deleta');
});
