<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lc extends Model
{
    use HasFactory;

    protected $table = 'lc';

    protected $fillable = [
        'costing_id',
        'lc_name',
        'lc_date',
        'lc_number',
        'shipment_date',
        'arriving_date',
        'dhl_number',
        'bl_number',
        'doc_status',
        'bill_of_entry_amount',
    ];

    public $timestamps = true;
}
