<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Transaction;

class DashboardController extends Controller
{
    public function index()
    {
        $banks = Bank::withCount('transactions')->orderBy('name')->get();

        $stats = [
            'total_banks' => $banks->count(),
            'total_transactions' => Transaction::count(),
            'month_credits' => Transaction::credit()
                ->whereYear('date', now()->year)
                ->whereMonth('date', now()->month)
                ->sum('amount'),
            'week_cash' => Transaction::cashWithdrawal()
                ->whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()])
                ->sum('amount'),
        ];

        return view('dashboard', compact('banks', 'stats'));
    }
}
