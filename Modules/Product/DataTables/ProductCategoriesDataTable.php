<?php

namespace Modules\Product\DataTables;

use Modules\Product\Entities\Category;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ProductCategoriesDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('action', function ($data) {
                return view('product::categories.partials.actions', compact('data'));
            });
    }

    public function query(Category $model)
    {
        // Removed withCount('products') since we no longer need products_count
        return $model->newQuery();
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('product_categories-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom("<'row'<'col-md-3'l><'col-md-5 mb-2'B><'col-md-4'f>> .
                                'tr' .
                                <'row'<'col-md-5'i><'col-md-7 mt-2'p>>")
            ->orderBy(3)
            ->buttons(
                Button::make('excel')
                    ->text('<i class="bi bi-file-earmark-excel-fill"></i> Excel'),
                Button::make('print')
                    ->text('<i class="bi bi-printer-fill"></i> Print')
                    ->action('
                          var printWindow = window.open("", "_blank");
                          printWindow.document.write("<html><head><title>Print Table</title>");
                          printWindow.document.write("<style>table{border-collapse:collapse;} table, th, td{border:1px solid                #000;padding:5px;} th{text-align:center;}</style>");
                          printWindow.document.write("</head><body>");
                          printWindow.document.write(document.getElementById("product_categories-table").outerHTML);
                          printWindow.document.write("</body></html>");
                          printWindow.document.close();
                          printWindow.print();
                      '),
                Button::make('reset')
                    ->text('<i class="bi bi-x-circle"></i> Reset'),
                Button::make('reload')
                    ->text('<i class="bi bi-arrow-repeat"></i> Reload')
            );
    }

    protected function getColumns()
    {
        return [
            Column::make('category_code')
                ->addClass('text-center'),

            Column::make('category_name')
                ->addClass('text-center'),

            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-center'),

            Column::make('created_at')
                ->visible(false)
        ];
    }

    protected function filename(): string
    {
        return 'ProductCategories_' . date('YmdHis');
    }
}
