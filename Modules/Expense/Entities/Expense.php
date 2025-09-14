<?php

namespace Modules\Expense\Entities;

use App\Models\Container;
use App\Models\ExpenseName;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;

class Expense extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class, 'category_id', 'id');
    }
    public function lc()
    {
        return $this->belongsTo(\App\Models\Lc::class, 'lc_id');
    }

    public function container()
    {
        return $this->belongsTo(Container::class, 'container_id');
    }

    public function expenseName()
    {
        return $this->belongsTo(ExpenseName::class, 'expense_name_id', 'id');
    }

    public function getDateAttribute($value)
    {
        return Carbon::parse($value)->format('d M, Y');
    }

    public function setAmountAttribute($value)
    {
        $this->attributes['amount'] = ($value * 100);
    }

    public function getAmountAttribute($value)
    {
        return ($value / 100);
    }
}
