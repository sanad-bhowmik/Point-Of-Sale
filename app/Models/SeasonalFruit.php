<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeasonalFruit extends Model
{
    use HasFactory;

    // Table name (optional if it matches plural of model name)
    protected $table = 'seasonal_fruit';

    // Columns that are mass assignable
    protected $fillable = [
        'name',
        'from_month',
        'to_month',
        'img',
        'remarks',
    ];

}
