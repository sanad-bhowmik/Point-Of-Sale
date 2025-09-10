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
            ->addColumn('action', function ($data) {
                return view('expense::expenses.partials.actions', compact('data'));
            });
    }

    public function query(Expense $model)
    {
        // Load both category and LC relation
        return $model->newQuery()->with(['category', 'lc']);
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
            Column::make('date')->className('text-center align-middle'),

            Column::make('reference')->className('text-center align-middle'),

            Column::make('lc.lc_name')
                ->title('LC Name')
                ->className('text-center align-middle'),

            Column::make('category.category_name')
                ->title('Category')
                ->className('text-center align-middle'),

            Column::computed('amount')->className('text-center align-middle'),

            Column::make('cf_agent_fee')->title('C&F Agent Fee')->className('text-center align-middle'),
            Column::make('bl_verify')->title('B/L Verify')->className('text-center align-middle'),
            Column::make('shipping_charge')->title('Shipping/NOC Charge')->className('text-center align-middle'),
            Column::make('port_bill')->title('Port Bill')->className('text-center align-middle'),
            Column::make('labor_bill')->title('Labor Bill')->className('text-center align-middle'),
            Column::make('transport_bill')->title('Transport/Survey Bill')->className('text-center align-middle'),
            Column::make('other_receipt')->title('Other Receipt')->className('text-center align-middle'),
            Column::make('formalin_test')->title('Formalin Test')->className('text-center align-middle'),
            Column::make('radiation_cert')->title('Radiation Cert')->className('text-center align-middle'),
            Column::make('labor_tips')->title('Labor Tips')->className('text-center align-middle'),
            Column::make('cf_commission')->title('C&F Commission')->className('text-center align-middle'),
            Column::make('ip_absence')->title('IP Absence')->className('text-center align-middle'),
            Column::make('special_delivery')->title('Special Delivery')->className('text-center align-middle'),

            Column::make('details')->className('text-center align-middle'),

            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->className('text-center align-middle'),

            Column::make('created_at')->visible(false),
        ];
    }

    protected function filename(): string
    {
        return 'Expenses_' . date('YmdHis');
    }
}
