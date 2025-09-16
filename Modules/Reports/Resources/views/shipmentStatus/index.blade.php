@extends('layouts.app')

@section('title', 'Shipment Status Report')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item active">Shipment Status Report</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        <div>
            <!-- Filter Form -->
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('shipment-status-report.index') }}" method="get">
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
            @if (isset($shipmentStatus))
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
                                        <th>Lc Number</th>
                                        <th>Container Number</th>
                                        <th>Product Name</th>
                                        <th>Product Description Size</th>
                                        <th>Supplier Name</th>
                                        <th>Our Company</th>
                                        <th>Total Qty</th>
                                        <th>LC Date</th>
                                        <th>TT Date</th>
                                        <th>Shipment Date</th>
                                        <th>Arrive date at CTG</th>
                                        <th>DHL</th>
                                        <th>BL NO</th>
                                        <th>Document Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($shipmentStatus as $index => $item)
                                        <tr>
                                            <td>{{ $index+1 }}</td>
                                            <td>{{ $item->lc->lc_number }}</td>
                                            <td>{{ $container->number }}</td>
                                            <td>{{ $item?->product?->product_name }}</td>
                                            @php
                                                $size = \App\Models\Size::find($item->size)->first();
                                            @endphp
                                            <td>{{ $size->size }}</td>
                                            <td>{{ $item?->supplier?->supplier_name }}</td>
                                            <td>Taifa Traders</td>
                                            <td>{{ round($item->qty) }} Box <br> {{ $item->qty * $item->box_type }} KG</td>
                                            <td>{{ $container?->lc_date }}</td>
                                            <td>{{ $container?->tt_date }}</td>
                                            <td>{{ $container->shipping_date }}</td>
                                            <td>{{ $container->arriving_date }}</td>
                                            <td>{{ $container->dhl }}</td>
                                            <td>{{ $container->bl_no }}</td>
                                            <td>{{ $container->document_status }}</td>
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
                {{ $shipmentStatus->links() }}
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