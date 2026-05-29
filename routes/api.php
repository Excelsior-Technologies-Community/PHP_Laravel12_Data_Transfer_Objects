<?php

use App\Http\Controllers\Api\BookApiController;
use Illuminate\Support\Facades\Route;

Route::get('/books/available', [BookApiController::class, 'getAvailableBooks']);
Route::post('/books/reserve', [BookApiController::class, 'reserveBook']);
Route::get('/reservations', [BookApiController::class, 'getReservations']);