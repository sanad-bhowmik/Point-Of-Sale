<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfficeExpense extends Model
{
    use HasFactory;

    // Specify the table name (optional if it follows Laravel naming convention)
    protected $table = 'office_expenses';

    // Primary key (optional if it's 'id')
    protected $primaryKey = 'id';

    // Laravel handles timestamps by default
    public $timestamps = true;

    // Fillable fields for mass assignment
    protected $fillable = [
        'expense_category',
        'employee_name',
        'amount',
        'date',
        'note',
    ];
}
