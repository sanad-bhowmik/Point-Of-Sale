<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CateringLunch extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'quantity',
        'unit_price',
        'total',
        'note',
        'status',
    ];
}
