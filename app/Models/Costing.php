<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Costing extends Model
{
    use HasFactory;

    protected $table = 'costing';

    protected $fillable = [
        'supplier_id',
        'product_id',
        'box_type',
        'size',
        'currency',
        'base_value',
        'qty',
        'exchange_rate',
        'total',
        'total_tk',
        'insurance',
        'insurance_tk',
        'landing_charge',
        'landing_charge_tk',
        'cd',
        'rd',
        'sd',
        'vat',
        'ait',
        'at',
        'atv',
        'tt_amount',
        'total_tax',
        'transport',
        'arrot',
        'cns_charge',
        'others_total',
        'total_tariff_lc',
        'tariff_per_ton_lc',
        'tariff_per_kg_lc',
        'actual_cost_per_kg',
        'total_cost_per_kg',
        'total_cost_per_box',
        'updated_attempt'
    ];

    // Relationship with Supplier
    public function supplier()
    {
        return $this->belongsTo(\App\Models\Supplier::class, 'supplier_id');
    }

    // Relationship with Product
    public function product()
    {
        return $this->belongsTo(\App\Models\Product::class, 'product_id');
    }

    public function lc()
    {
        return $this->belongsTo(\App\Models\Lc::class);
    }
}
