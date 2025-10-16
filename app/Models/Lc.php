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
        'eta_date',
        'etd_date',
        'lc_value',
        'lc_exchange_rate',
        'lc_total_amount',
        'tt_value',
        'tt_exchange_rate',
        'tt_total_amount',
        'tt_date',
    ];

    public $timestamps = true;

    public function containers()
    {
        return $this->hasMany(Container::class, 'lc_id');
    }

    public function costing()
    {
        return $this->belongsTo(Costing::class, 'costing_id');
    }
}
