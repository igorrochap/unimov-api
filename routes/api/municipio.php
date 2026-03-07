<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Municipio\MunicipioController;

Route::controller(MunicipioController::class)->group(function () {
    Route::post('', 'store')->name('municipio.cria');
});
