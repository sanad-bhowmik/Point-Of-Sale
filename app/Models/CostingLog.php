<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CostingLog extends Model
{
    use HasFactory;

    // Table name (if not default 'costing_logs')
    protected $table = 'costing_log';

    // Fillable fields
    protected $fillable = [
        'costing_id',
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
        'updated_attempt',
    ];

    // Optional: you can cast numeric fields if needed
    protected $casts = [
        'base_value' => 'float',
        'qty' => 'float',
        'exchange_rate' => 'float',
        'total' => 'float',
        'total_tk' => 'float',
        'insurance' => 'float',
        'insurance_tk' => 'float',
        'landing_charge' => 'float',
        'landing_charge_tk' => 'float',
        'cd' => 'float',
        'rd' => 'float',
        'sd' => 'float',
        'vat' => 'float',
        'ait' => 'float',
        'at' => 'float',
        'atv' => 'float',
        'total_tax' => 'float',
        'transport' => 'float',
        'arrot' => 'float',
        'cns_charge' => 'float',
        'others_total' => 'float',
        'total_tariff_lc' => 'float',
        'tariff_per_ton_lc' => 'float',
        'tariff_per_kg_lc' => 'float',
        'actual_cost_per_kg' => 'float',
        'total_cost_per_kg' => 'float',
        'total_cost_per_box' => 'float',
        'updated_attempt' => 'integer',
    ];

    /**
     * Relationship with Costing
     */
    public function costing()
    {
        return $this->belongsTo(Costing::class, 'costing_id');
    }
}
