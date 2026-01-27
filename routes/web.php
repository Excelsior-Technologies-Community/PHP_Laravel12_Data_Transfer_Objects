<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookReservationController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/reserve-book', [BookReservationController::class, 'create']);
Route::post('/reserve-book', [BookReservationController::class, 'store']);
