<?php

namespace App\Imports;

use App\Models\CateringLunch;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CateringLunchImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new CateringLunch([
            'date' => \Carbon\Carbon::parse($row['date'])->format('Y-m-d'),
            'quantity' => $row['quantity'] ?? 0,
            'unit_price' => $row['unit_price'] ?? 0,
            'total' => $row['total'] ?? (($row['quantity'] ?? 0) * ($row['unit_price'] ?? 0)),
            'note' => $row['note'] ?? null,
            'status' => $row['status'] ?? 'unpaid',
        ]);
    }
}

