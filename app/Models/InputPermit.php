<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InputPermit extends Model
{
    use HasFactory;

    protected $table = 'input_permit'; // specify table name if not default plural

    protected $primaryKey = 'id';

    public $timestamps = true; // Laravel will handle created_at and updated_at

    protected $fillable = [
        'to_text',
        'reference',
        'no',
        'date',
        'importer_name',
        'importer_address',
        'means_of_transport',
        'consignor_name',
        'consignor_address',
        'country_of_origin',
        'country_of_export',
        'point_of_entry',
        'plant_name_and_products',
        'variety_or_category',
        'pack_size',
        'quantity',
        'status',
        'attachment',
    ];
}
