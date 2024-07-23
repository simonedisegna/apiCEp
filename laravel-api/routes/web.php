<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CepController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/search/local/{ceps?}', [CepController::class, 'search']);
