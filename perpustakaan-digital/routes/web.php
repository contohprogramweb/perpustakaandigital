<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\UserController;

// Home route
Route::get('/', function () {
    return view('welcome');
});

// Resource routes (require authentication in real app)
Route::resource('books', BookController::class);
Route::resource('categories', CategoryController::class);
Route::resource('users', UserController::class);
Route::resource('loans', LoanController::class);

// Additional loan routes
Route::post('loans/{loan}/return', [LoanController::class, 'returnBook'])->name('loans.return');
Route::get('loans/overdue', [LoanController::class, 'overdue'])->name('loans.overdue');
