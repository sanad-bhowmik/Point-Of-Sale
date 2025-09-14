<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Expense\Entities\Expense;
use Modules\Expense\Entities\ExpenseCategory;

class ExpenseName extends Model
{
    use HasFactory;

    protected $fillable = [
        'expense_name',
        'expense_category_id',
    ];

    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class, 'expense_category_id');
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class, 'expense_name_id');
    }
}
