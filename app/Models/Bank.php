<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable('name', 'account_title', 'account_number', 'monthly_limit', 'weekly_cash_limit')]
class Bank extends Model
{
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    // Current month total credit
    public function monthlyCreditTotal($month = null, $year = null)
    {
        $month = $month ?? now()->month;
        $year = $year ?? now()->year;

        return $this->transactions()
            ->where('type', 'credit')
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->sum('amount');
    }

    // Current week cash withdrawal total
    public function weeklyCashWithdrawalTotal($date = null)
    {
        $date = $date ? Carbon::parse($date) : now();
        $startOfWeek = $date->copy()->startOfWeek();
        $endOfWeek = $date->copy()->endOfWeek();

        return $this->transactions()
            ->where('type', 'cash_withdrawal')
            ->whereBetween('date', [$startOfWeek, $endOfWeek])
            ->sum('amount');
    }
}
