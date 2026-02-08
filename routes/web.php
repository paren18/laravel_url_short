<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LinkController;

Route::post('/shorten', [LinkController::class, 'store']);


Route::get('/{code}', [LinkController::class, 'redirect']);
