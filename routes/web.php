<?php

use App\Http\Controllers\BankController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('login'));

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('bank', BankController::class);
    Route::resource('transaction', TransactionController::class);

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/monthly-credit/pdf', [ReportController::class, 'monthlyCreditPdf'])->name('reports.monthly-credit');
    Route::get('/reports/outgoing/pdf', [ReportController::class, 'outgoingPdf'])->name('reports.outgoing');
    Route::get('/reports/bank-summary/pdf', [ReportController::class, 'bankSummaryPdf'])->name('reports.bank-summary');
});
