<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Container extends Model
{
    use HasFactory;

    // Specify the correct table name
    protected $table = 'container';

    // Optional: define fillable fields
    protected $fillable = [
        'lc_id',
        'name',
        'number',
        'shipping_date',
        'arriving_date',
        'status',
        'lc_value',
        'lc_exchange_rate',
        'tt_value',
        'tt_exchange_rate',
        'qty',
        'lc_date',
        'tt_date',
        'dhl',
        'bl_no',
        'document_status',
    ];

     public function lc()
    {
        return $this->belongsTo(Lc::class, 'lc_id');
    }
}
