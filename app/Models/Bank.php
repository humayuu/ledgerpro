<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable('name', 'account_title', 'account_number', 'monthly_limit', 'weekly_cash_limit')]
class Bank extends Model
{
    protected $casts = [
        'monthly_limit' => 'decimal:2',
        'weekly_cash_limit' => 'decimal:2',
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function currentMonthCredit(?Carbon $date = null): float
    {
        $date = $date ?? now();

        return (float) $this->transactions()
            ->where('type', 'credit')
            ->whereYear('date', $date->year)
            ->whereMonth('date', $date->month)
            ->sum('amount');
    }

    public function currentWeekCashWithdrawal(?Carbon $date = null): float
    {
        $date = $date ?? now();
        $start = $date->copy()->startOfWeek();
        $end = $date->copy()->endOfWeek();

        return (float) $this->transactions()
            ->where('type', 'cash_withdrawal')
            ->whereBetween('date', [$start, $end])
            ->sum('amount');
    }

    public function currentMonthTransfer(?Carbon $date = null): float
    {
        $date = $date ?? now();

        return (float) $this->transactions()
            ->where('type', 'bank_transfer')
            ->whereYear('date', $date->year)
            ->whereMonth('date', $date->month)
            ->sum('amount');
    }

    public function monthlyCreditUsagePercent(?Carbon $date = null): float
    {
        if (! $this->monthly_limit) {
            return 0;
        }

        return min(100, ($this->currentMonthCredit($date) / $this->monthly_limit) * 100);
    }

    public function weeklyCashUsagePercent(?Carbon $date = null): float
    {
        if (! $this->weekly_cash_limit) {
            return 0;
        }

        return min(100, ($this->currentWeekCashWithdrawal($date) / $this->weekly_cash_limit) * 100);
    }
}
