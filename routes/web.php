<?php

use App\Http\Controllers\BankController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', fn() => view('dashboard'));
Route::get('/', fn() => view('auth/login'));

Route::resource('bank', BankController::class);
Route::resource('transaction', TransactionController::class);
