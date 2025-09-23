<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Sale\Entities\SaleDetails;

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
        'current_qty',
        'lc_date',
        'tt_date',
    ];

    public function lc()
    {
        return $this->belongsTo(Lc::class, 'lc_id');
    }

    public function saleDetails()
    {
        return $this->hasMany(SaleDetails::class);
    }
}
