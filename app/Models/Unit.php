<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * (Optional: Laravel will automatically use "units" based on class name,
     * but you can explicitly define it if needed.)
     */
    protected $table = 'units';

    /**
     * The attributes that are mass assignable.
     *
     * This allows you to use Unit::create([...]) safely.
     */
    protected $fillable = [
        'name',
        'short_name',
        'operator',
        'operation_value',
    ];
}
