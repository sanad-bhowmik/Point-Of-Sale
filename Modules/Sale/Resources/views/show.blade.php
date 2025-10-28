@extends('layouts.app')

@section('title', 'Sales Details')

@section('breadcrumb')
<ol class="breadcrumb border-0 m-0">
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('sales.index') }}">Sales</a></li>
    <li class="breadcrumb-item active">Details</li>
</ol>
@endsection
<style>
    #invoiceContent {
        border: none !important;
    }
</style>
@section('content')
<div class="container-fluid">

    <!-- Download PDF Button -->
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-end">
            <button id="downloadInvoice" class="btn btn-info">
                <i class="bi bi-file-earmark-pdf"></i> Save PDF
            </button>
        </div>
    </div>

    <!-- Invoice Content -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card" id="invoiceContent">
                <div class="card-body" id="dd">

                    <!-- Top Reference Header -->
                    <div class="row mb-4">
                        <div class="col-12 text-left">
                            <h4>Reference: <strong>{{ $sale->reference }}</strong></h4>
                        </div>
                    </div>

                    <!-- Company / Customer / Invoice Info -->
                    <div class="row mb-4">
                        <div class="col-sm-4 mb-3 mb-md-0">
                            <h5 class="mb-2 border-bottom pb-2">Company Info:</h5>
                            <div><strong>{{ settings()->company_name }}</strong></div>
                            <div>{{ settings()->company_address }}</div>
                            <div>Email: {{ settings()->company_email }}</div>
                            <div>Phone: {{ settings()->company_phone }}</div>
                        </div>

                        <div class="col-sm-4 mb-3 mb-md-0">
                            <h5 class="mb-2 border-bottom pb-2">Customer Info:</h5>
                            <div><strong>{{ $customer->customer_name }}</strong></div>
                            <div>{{ $customer->address }}</div>
                            <div>Email: {{ $customer->customer_email }}</div>
                            <div>Phone: {{ $customer->customer_phone }}</div>
                        </div>

                        <div class="col-sm-4 mb-3 mb-md-0">
                            <h5 class="mb-2 border-bottom pb-2">Invoice Info:</h5>
                            <div>Date: {{ \Carbon\Carbon::parse($sale->date)->format('d M, Y') }}</div>
                            <div>Status: <strong>{{ $sale->status }}</strong></div>
                            <div>Payment Status: <strong>{{ $sale->payment_status }}</strong></div>
                        </div>
                    </div>

                    <!-- Product Table -->
                    <div class="table-responsive-sm">
                        <table class="table ">
                            <thead>
                                <tr>
                                    <th class="align-middle">Product</th>
                                    <th class="align-middle">Net Unit Price</th>
                                    <th class="align-middle">Quantity</th>
                                    <th class="align-middle">Discount</th>
                                    <th class="align-middle">Tax</th>
                                    <th class="align-middle">Sub Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sale->saleDetails as $item)
                                <tr>
                                    <td class="align-middle">
                                        {{ $item->product_name }} <br>
                                    </td>
                                    <td class="align-middle">{{ format_currency($item->unit_price) }}</td>
                                    <td class="align-middle">{{ $item->quantity }}</td>
                                    <td class="align-middle">{{ format_currency($item->product_discount_amount) }}</td>
                                    <td class="align-middle">{{ format_currency($item->product_tax_amount) }}</td>
                                    <td class="align-middle">{{ format_currency($item->sub_total) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Summary -->
                    <div class="row">
                        <div class="col-lg-4 col-sm-5 ml-md-auto">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td class="left"><strong>Discount ({{ $sale->discount_percentage }}%)</strong></td>
                                        <td class="right">{{ format_currency($sale->discount_amount) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="left"><strong>Tax ({{ $sale->tax_percentage }}%)</strong></td>
                                        <td class="right">{{ format_currency($sale->tax_amount) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="left"><strong>Shipping</strong></td>
                                        <td class="right">{{ format_currency($sale->shipping_amount) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="left"><strong>Grand Total</strong></td>
                                        <td class="right"><strong>{{ format_currency($sale->total_amount) }}</strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div> <!-- card-body -->
            </div> <!-- card -->
        </div> <!-- col -->
    </div> <!-- row -->
</div> <!-- container-fluid -->
@endsection

@push('page_scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
    document.getElementById('downloadInvoice').addEventListener('click', function() {
        const element = document.getElementById('invoiceContent');
        const options = {
            margin: 0.5,
            filename: 'Invoice_{{ $sale->reference }}.pdf',
            image: {
                type: 'jpeg',
                quality: 0.98
            },
            html2canvas: {
                scale: 2
            },
            jsPDF: {
                unit: 'in',
                format: 'letter',
                orientation: 'portrait'
            }
        };
        html2pdf().set(options).from(element).save();
    });
</script>
@endpush
