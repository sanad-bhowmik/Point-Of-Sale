@extends('layouts.app')

@section('title', 'Product Details')

@section('breadcrumb')
<ol class="breadcrumb border-0 m-0">
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></li>
    <li class="breadcrumb-item active">Details</li>
</ol>
@endsection

@section('content')
<div class="container-fluid mb-4">
    <div class="row mb-3">
        <div class="col-md-12">
            {!! \Milon\Barcode\Facades\DNS1DFacade::getBarCodeSVG($product->product_code, $product->product_barcode_symbology, 2, 110) !!}
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card h-100">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped mb-0">
                            <tr>
                                <th>Name</th>
                                <td>{{ $product->product_name }}</td>
                            </tr>
                            <tr>
                                <th>Category</th>
                                <td>{{ $product->category->category_name }}</td>
                            </tr>

                            <tr>
                                <th>Unit</th>
                                <td>{{ $product->product_unit }}</td>
                            </tr>

                        </table>
                    </div>
                </div>
            </div>
        </div>


    </div>
    @php
    use Illuminate\Support\Facades\DB;

    // Get supplier-wise totals for this product
    $supplierTotals = DB::table('costing as c')
    ->select('c.supplier_id', DB::raw('SUM(c.total_tk) as total_tk'))
    ->where('c.product_id', $product->id)
    ->groupBy('c.supplier_id')
    ->get();
    @endphp

    <div class="row mt-4">
        <div class="col-lg-12">
            <div class="card h-100">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped mb-0">
                            <thead class="">
                                <tr>
                                    <th>SL</th>
                                    <th>Supplier</th>
                                    <th>Total TK</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $sl = 1; @endphp
                                @forelse($supplierTotals as $supplier)
                                @php
                                $supplierName = DB::table('suppliers')
                                ->where('id', $supplier->supplier_id)
                                ->value('supplier_name');
                                @endphp
                                <tr>
                                    <td>{{ $sl++ }}</td>
                                    <td>{{ $supplierName ?? '-' }}</td>
                                    <td class="text-end">{{ number_format($supplier->total_tk, 2) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center">No costings found for this product.</td>
                                </tr>
                                @endforelse
                            </tbody>
                            @if($supplierTotals->count() > 0)
                            <tfoot>
                                <tr class="fw-bold">
                                    <td colspan="2" class="text-end">Grand Total</td>
                                    <td class="text-end">{{ number_format($supplierTotals->sum('total_tk'), 2) }}</td>
                                </tr>
                            </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @php

    // Get customer-wise subtotal for this product
    $saleDetails = DB::table('sale_details as sd')
    ->join('sales as s', 'sd.sale_id', '=', 's.id')
    ->select('s.customer_id', 's.customer_name', DB::raw('SUM(sd.sub_total) as subtotal'))
    ->where('sd.product_id', $product->id)
    ->groupBy('s.customer_id', 's.customer_name')
    ->get();
    @endphp

    <div class="row mt-4">
        <div class="col-lg-12">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="mb-3">Customer-wise Sales Subtotal</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th>SL</th>
                                    <th>Customer</th>
                                    <th>Subtotal (TK)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $sl = 1; @endphp
                                @forelse($saleDetails as $sale)
                                <tr>
                                    <td>{{ $sl++ }}</td>
                                    <td>{{ $sale->customer_name ?? '-' }}</td>
                                    <td class="text-end">{{ number_format($sale->subtotal, 2) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center">No sales found for this product.</td>
                                </tr>
                                @endforelse
                            </tbody>
                            @if($saleDetails->count() > 0)
                            <tfoot>
                                <tr class="fw-bold">
                                    <td colspan="2" class="text-end">Grand Total</td>
                                    <td class="text-end">{{ number_format($saleDetails->sum('subtotal'), 2) }}</td>
                                </tr>
                            </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
