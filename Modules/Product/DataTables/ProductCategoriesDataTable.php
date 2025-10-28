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
        return $model->newQuery()->select(['id', 'category_code', 'category_name', 'created_at']);
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('product-categories-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom("<'row'<'col-md-3'l><'col-md-5 mb-2'><'col-md-4'f>>rt<'row'<'col-md-5'i><'col-md-7'p>>")
            ->orderBy(0, 'asc');
    }

    protected function getColumns()
    {
        return [
            Column::make('id')
                ->title('ID')
                ->visible(false)
                ->exportable(false)
                ->printable(false),

            Column::make('category_code')
                ->title('Category Code')
                ->addClass('text-center')
                ->exportable(true)
                ->printable(true),

            Column::make('category_name')
                ->title('Category Name')
                ->addClass('text-center')
                ->exportable(true)
                ->printable(true),

            Column::make('created_at')
                ->title('Created At')
                ->visible(false)
                ->exportable(false)
                ->printable(false),

            Column::computed('action')
                ->title('Actions')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'ProductCategories_' . date('YmdHis');
    }
}
