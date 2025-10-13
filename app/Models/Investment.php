<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Investment extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'investment';

    // Fillable columns (mass assignable)
    protected $fillable = [
        'lc_number',
        'date',
        'description',
        'investment',
        'amount',
        'status',
    ];
}
