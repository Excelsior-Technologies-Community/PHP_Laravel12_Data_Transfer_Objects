<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookReservationController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BookReturnController;
use App\Http\Controllers\BookSearchController;

Route::get('/', function () {
    return view('welcome');
});

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index']);

// Category Management
Route::get('/categories', [CategoryController::class, 'index']);
Route::post('/categories', [CategoryController::class, 'store']);
Route::post('/categories/delete/{id}', [CategoryController::class, 'delete']);

// Book Management
Route::get('/books', [BookController::class, 'index']);
Route::get('/books/create', [BookController::class, 'create']);
Route::post('/books/store', [BookController::class, 'store']);
Route::post('/books/delete/{id}', [BookController::class, 'delete']);
Route::get('/books/search', [BookSearchController::class, 'search']);

// Reservation Management
Route::get('/reserve-book', [BookReservationController::class, 'create']);
Route::post('/reserve-book', [BookReservationController::class, 'store']);
Route::get('/reservations', [BookReservationController::class, 'index']);
Route::post('/return-book/{id}', [BookReturnController::class, 'returnBook']);
Route::get('/return-book/{id}', [BookReturnController::class, 'showReturnForm']);