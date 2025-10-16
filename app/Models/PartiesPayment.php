<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartiesPayment extends Model
{
    use HasFactory;

    // Define the table name (optional if it follows Laravel naming conventions)
    protected $table = 'parties_payment';

    // The attributes that are mass assignable
    protected $fillable = [
        'name',
        'usd_amount',
        'exchange_rate',
        'amount',
        'damarage_amount',
        'description',
        'date',
        'status',
    ];

    public $timestamps = true;
}
