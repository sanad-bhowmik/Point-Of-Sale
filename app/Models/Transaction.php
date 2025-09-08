<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'bank_id',
        'in_amount',
        'out_amount',
        'purpose',
        'status',
        'date',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }
}
