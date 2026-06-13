<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable('bank_id', 'date', 'type', 'party_name', 'amount', 'transfer_to_bank_id', 'notes')]
class Transaction extends Model
{
    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    public function transferToBank()
    {
        return $this->belongsTo(Bank::class, 'transfer_to_bank_id');
    }
}
