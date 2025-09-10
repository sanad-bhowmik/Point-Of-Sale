<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;

    protected $fillable = [
        'institution',
        'account_no',
        'bank_name',
        'branch_name',
        'owner',
        'date',
        'opening_balance',
        'last_balance',
        'disclaimer',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'bank_id');
    }
}
