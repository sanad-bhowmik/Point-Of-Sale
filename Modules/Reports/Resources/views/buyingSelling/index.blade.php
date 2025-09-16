@extends('layouts.app')

@section('title', 'Buying Selling Report')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item active">Buying Selling Report</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        <div>
            <!-- Filter Form -->
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('buying-selling-report.filter') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <!-- LC Select -->
                            <div class="col-md-4">
                                <select class="form-control" id="lcSelect" name="lc_id">
                                    <option value="">-- Select LC --</option>
                                    @foreach ($lcList as $lc)
                                        <option value="{{ $lc->id }}"
                                            {{ request('lc_id') == $lc->id ? 'selected' : '' }}>
                                            {{ $lc->lc_name }} ({{ $lc->lc_number }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Container Select -->
                            <div class="col-md-4">
                                <select class="form-control" id="containerSelect" name="container_id">
                                    <option value="">-- Select Container --</option>
                                    @foreach ($containerList as $container)
                                        <option value="{{ $container->id }}"
                                            {{ request('container_id') == $container->id ? 'selected' : '' }}>
                                            {{ $container->name }} ({{ $container->number }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Filter Button -->
                            <div class="col-md-4">
                                <button class="btn btn-primary w-100" type="submit">Filter</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Table -->
            @if (isset($buyingSelling))
                <div class="card border-0 shadow-sm">
                    <div class="card-body position-relative">
                        <div wire:loading.flex class="position-absolute justify-content-center align-items-center"
                            style="top:0;right:0;left:0;bottom:0;background-color: rgba(255,255,255,0.5);z-index: 99;">
                            <div class="spinner-border text-primary" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="bg-success text-white">
                                    <tr>
                                        <th>SL</th>
                                        <th>Product Name</th>
                                        <th>Product Description Size</th>
                                        <th>Supplier Name</th>
                                        <th>Our Company</th>
                                        <th>Lc Number</th>
                                        <th>Container Number</th>
                                        <th>Buying Date/LC</th>
                                        <th>TT Date</th>
                                        <th>Total Qty</th>
                                        <th>USD Price</th>
                                        <th>CNF Price</th>
                                        <th>CTG Price</th>
                                        <th>Buying Price</th>
                                        <th>KG/Box</th>
                                        <th>Selling Date</th>
                                        <th>Selling Price</th>
                                        <th>Profit/Loss</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($buyingSelling as $index => $item)
                                        <tr>
                                            <td>{{ $index+1 }}</td>
                                            <td>{{ $item?->product?->product_name }}</td>
                                            @php
                                                $size = \App\Models\Size::find($item->size)->first();
                                            @endphp
                                            <td>{{ $size->size }}</td>
                                            <td>{{ $item?->supplier?->supplier_name }}</td>
                                            <td>Taifa Traders</td>
                                            <td>{{ $item->lc->lc_number }}</td>
                                            <td>{{ $container->number }}</td>
                                            <td>{{ $container?->lc_date }}</td>
                                            <td>{{ $container?->tt_date }}</td>
                                            <td>{{ round($item->qty) }} Box <br> {{ $item->qty * $item->box_type }} KG</td>
                                            <td>{{ $container?->lc_value + $container?->tt_value }}</td>
                                            <td>{{ round(($item->total_tk + $container?->tt_value * $container?->tt_exchange_rate * $container->qty) / $item->qty) }}
                                            </td>
                                            @php
                                                $total = $item->total_tk + $container?->tt_value * $container?->tt_exchange_rate * $container->qty;

                                                $dates = $sales->pluck('sale.date')->sort();
                                                $firstDate = \Carbon\Carbon::parse($dates->first())->format('d-m-y');
                                                $lastDate = \Carbon\Carbon::parse($dates->last())->format('d-m-y');
                                                $dateRange = $firstDate . ' to ' . $lastDate;
                                            @endphp
                                            <td>{{ round(($total + $totalAmount) / $item->qty) }}
                                            </td>
                                            <td>{{ round(($total + $totalCostAmoun) / $item->qty) }}</td>
                                            <td>{{ $item->box_type }}</td>
                                            <td>{{ $dateRange }}</td>
                                            <td>{{ round($totalSale / $item->qty) }}</td>
                                            <td>{{ round(($totalSale - ($total + $totalCostAmoun)) / $item->qty) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="20">No data found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        {{-- <div class="mt-3">
                {{ $buyingSelling->links() }}
            </div> --}}
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection


@push('page_css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        table th,
        table td {
            width: 200px;
            white-space: nowrap;
            text-align: center;
            vertical-align: middle;
        }
    </style>
@endpush

@push('page_scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#lcSelect').select2();
            $('#containerSelect').select2();
        });
    </script>
@endpush