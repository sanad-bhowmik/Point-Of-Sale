<?php

namespace Modules\Expense\DataTables;

use Modules\Expense\Entities\Expense;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ExpensesDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('amount', function ($data) {
                return format_currency($data->amount);
            })
            ->addColumn('lc_info', function($row) {
                return $row->lc->lc_name . ' (' . $row->lc->lc_number . ')';
            })
            ->addColumn('action', function ($data) {
                return view('expense::expenses.partials.actions', compact('data'));
            });
    }

    public function query(Expense $model)
    {
        // Load both category and LC relation
        return $model->newQuery()->with(['container', 'expenseName', 'lc', 'category']);
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('expenses-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom("<'row'<'col-md-3'l><'col-md-5 mb-2'B><'col-md-4'f>> .
                                'tr' .
                                <'row'<'col-md-5'i><'col-md-7 mt-2'p>>")
            ->orderBy(6)
            ->buttons(
                Button::make('excel')
                    ->text('<i class="bi bi-file-earmark-excel-fill"></i> Excel'),
                Button::make('print')
                    ->text('<i class="bi bi-printer-fill"></i> Print'),
                Button::make('reset')
                    ->text('<i class="bi bi-x-circle"></i> Reset'),
                Button::make('reload')
                    ->text('<i class="bi bi-arrow-repeat"></i> Reload')
            );
    }

    protected function getColumns()
    {
        return [
            Column::make('date')
                ->title('Date')
                ->className('text-center align-middle'),

            Column::make('lc_info')
                ->title('LC Name & Number')
                ->className('text-center align-middle'),

            Column::make('category.category_name')
                ->title('Category')
                ->className('text-center align-middle')
                ->searchable(false),

            Column::make('expense_name.expense_name')
                ->title('Expense Name')
                ->className('text-center align-middle')
                ->searchable(false),

            Column::make('container.name')
                ->title('Container Name')
                ->className('text-center align-middle')
                ->searchable(true),

            Column::make('amount')
                ->title('Amount')
                ->className('text-center align-middle'),

            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->className('text-center align-middle'),

            Column::make('created_at')
                ->visible(false),
        ];
    }


    protected function filename(): string
    {
        return 'Expenses_' . date('YmdHis');
    }
}
