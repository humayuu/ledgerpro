<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable('bank_id', 'date', 'type', 'party_name', 'amount', 'transfer_to_bank_id', 'notes')]
class Transaction extends Model
{
    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    public function transferToBank()
    {
        return $this->belongsTo(Bank::class, 'transfer_to_bank_id');
    }

    public function scopeCredit($query)
    {
        return $query->where('type', 'credit');
    }

    public function scopeCashWithdrawal($query)
    {
        return $query->where('type', 'cash_withdrawal');
    }

    public function scopeBankTransfer($query)
    {
        return $query->where('type', 'bank_transfer');
    }

    public function scopeForMonth($query, $year, $month)
    {
        return $query->whereYear('date', $year)
            ->whereMonth('date', $month);
    }

    public function scopeForWeek($query, $start, $end)
    {
        return $query->whereBetween('date', [$start, $end]);
    }

    public function typeLabel(): string
    {
        return match ($this->type) {
            'credit' => 'Credit',
            'cash_withdrawal' => 'Cash Withdrawal',
            'bank_transfer' => 'Bank Transfer',
            default => $this->type,
        };
    }
}
