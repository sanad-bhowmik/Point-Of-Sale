<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfficeExpenseCategory extends Model
{
    protected $fillable = [
        'category_name',
        'category_description',
    ];
}
