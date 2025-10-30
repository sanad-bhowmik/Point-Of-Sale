<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Withdraw extends Model
{
    protected $table = 'withdraw';

    protected $fillable = [
        'cash_in_amount',
        'cash_withdraw_amount',
        'description',
        'status',
    ];
}
