<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookReservationController;
use App\Http\Controllers\BookController;


Route::get('/', function () {
    return view('welcome');
});


Route::get('/reserve-book', [BookReservationController::class, 'create']);
Route::post('/reserve-book', [BookReservationController::class, 'store']);

// NEW: Reservation History + Return
Route::get('/reservations', [BookReservationController::class, 'index']);
Route::post('/return-book/{id}', [BookReservationController::class, 'returnBook']);

Route::get('/books', [BookController::class, 'index']);
Route::get('/books/create', [BookController::class, 'create']);
Route::post('/books/store', [BookController::class, 'store']);
Route::post('/books/delete/{id}', [BookController::class, 'delete']);