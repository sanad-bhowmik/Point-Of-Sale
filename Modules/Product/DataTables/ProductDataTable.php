<?php

namespace Modules\Product\DataTables;

use Modules\Product\Entities\Product;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ProductDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('action', function ($data) {
                return view('product::products.partials.actions', compact('data'));
            })
            ->addColumn('product_image', function ($data) {
                $url = $data->getFirstMediaUrl('images', 'thumb');
                return '<img src="' . $url . '" border="0" width="50" class="img-thumbnail" align="center"/>';
            })
            ->editColumn('product_name', function ($data) {
                $url = route('products.show', $data->id);
                return '<a href="' . $url . '">' . $data->product_name . '</a>';
            })
            ->addColumn('product_cost', function ($data) {
                return format_currency($data->product_cost);
            })
            ->addColumn('product_quantity', function ($data) {
                return $data->product_unit;
            })
            ->rawColumns(['product_image', 'product_name', 'action']);
    }

    /**
     * Get the query source of dataTable.
     *
     * @param \Modules\Product\Entities\Product $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Product $model)
    {
        return $model->newQuery()->with('category');
    }

    /**
     * Optional method to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('product-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom("<'row'<'col-md-3'l><'col-md-9'f>> . 'tr' . <'row'<'col-md-5'i><'col-md-7 mt-2'p>>")
            ->orderBy(6); // Adjust orderBy column index if needed
    }

    /**
     * Get columns definition.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::computed('product_image')
                ->title('Image')
                ->className('text-center align-middle')
                ->visible(false),

            Column::make('category.category_name')
                ->title('Category')
                ->className('text-center align-middle'),

            Column::make('product_code')
                ->title('Code')
                ->className('text-center align-middle')
                ->visible(false),

            Column::make('product_name')
                ->title('Name')
                ->className('text-center align-middle'),

            Column::computed('product_cost')
                ->title('Cost')
                ->className('text-center align-middle')
                ->visible(false),

            Column::computed('product_quantity')
                ->title('Unit')
                ->className('text-center align-middle'),

            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->className('text-center align-middle'),

            Column::make('created_at')
                ->visible(false),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Product_' . date('YmdHis');
    }
}
