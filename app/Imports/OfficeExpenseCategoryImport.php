<?php

namespace App\Imports;

use App\Models\OfficeExpenseCategory;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class OfficeExpenseCategoryImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new OfficeExpenseCategory([
            'category_name' => $row['category_name'],
            'category_description' => $row['category_description'],
        ]);
    }
}
