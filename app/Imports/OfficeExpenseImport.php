<?php

namespace App\Imports;

use App\Models\OfficeExpense;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class OfficeExpenseImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new OfficeExpense([
            'expense_category_id' => $row['expense_category_id'],
            'employee_name'       => $row['employee_name'] ?? null,
            'amount'              => $row['amount'] ?? 0,
            'date'                => \Carbon\Carbon::parse($row['date'])->format('Y-m-d'),
            'status'              => $row['status'] ?? 'out',
            'quantity'            => $row['quantity'] ?? 0,
            'note'                => $row['note'] ?? null,
        ]);
    }
}
