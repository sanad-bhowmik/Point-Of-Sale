@extends('layouts.app')

@section('title', 'Stock Report')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item active">Stock Report</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        <div>
            <!-- Filter Form -->
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('stock-report.index') }}" method="get">
                        <div class="row">
                            <!-- LC Select -->
                            {{-- <div class="col-md-4">
                                <label for="">Select Lc</label>
                                <select class="form-control" id="lcSelect" name="lc_id">
                                    <option value="">-- Select LC --</option>
                                    @foreach ($lcList as $lc)
                                        <option value="{{ $lc->id }}">
                                            {{ $lc->lc_name }} ({{ $lc->lc_number }})
                                        </option>
                                    @endforeach
                                </select>
                            </div> --}}

                            <!-- Container Select -->
                            <div class="col-md-4">
                                <label for="">Select Container</label>
                                <select class="form-control" id="containerSelect" name="container_id">
                                    <option value="">-- Select Container --</option>
                                    @foreach ($containerList as $list_container)
                                        <option value="{{ $list_container->id }}"
                                            {{ request('container_id') == $list_container->id ? 'selected' : '' }}>
                                            {{ $list_container->name }} ({{ $list_container->number }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Filter Button -->
                            <div class="col-md-2">
                                <button class="btn btn-primary w-100 mt-4" type="submit">Filter</button>
                            </div>
                            <div class="col-md-2">
                                <a href="{{ route('stock-report.index') }}" class="btn btn-warning w-100 mt-4">Reset</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Table -->
            <div class="card border-0 shadow-sm">
                <div class="card-body position-relative">
                    <div class="mb-3 mt-3">
                        <button id="downloadExcel" class="btn btn-success">Download Excel</button>
                    </div>
                    <div class="table-responsive">
                        <table id="buyingSellingTable" class="table table-bordered table-striped">
                            <thead class="bg-success text-white">
                                <tr style="background-color: #fff; color: #000;">
                                    <th colspan="9" style="font-size: 20px;">Stock Reports</th>
                                </tr>
                                <tr>
                                    <th>SL</th>
                                    <th>Lc Number</th>
                                    <th>Container Number</th>
                                    <th>Product Name</th>
                                    <th>Product Description Size</th>
                                    <th>KG/Box</th>
                                    <th>Total Qty</th>
                                    <th>Sale Qty</th>
                                    <th>Available Qty</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($containerList as $container)
                                    <tr>
                                        <td>{{ $container->id }}</td>
                                        <td>{{ $container?->lc?->lc_number }}</td>
                                        <td>{{ $container->number }}</td>
                                        <td>{{ $container?->lc?->costing?->product?->product_name }}</td>
                                        @php
                                            $size = \App\Models\Size::where(
                                                'id',
                                                $container?->lc?->costing?->size,
                                            )->first();

                                            $sales = $container->saleDetails ?? collect();
                                            $sale_qty = $sales->sum('quantity');
                                            $available_qty = $container?->qty - $sale_qty;
                                        @endphp
                                        <td>{{ $size->size }}</td>
                                        <td>{{ $container?->lc?->costing?->box_type }}</td>
                                        <td>{{ round($container?->qty) }} Box<br>
                                            {{ $container?->qty * $container?->lc?->costing?->box_type }} KG
                                        </td>
                                        <td>{{ $sale_qty }} Box <br> {{ $sale_qty * $container?->lc?->costing?->box_type }} KG</td>
                                        <td>{{ $available_qty }} Box <br> {{ $available_qty * $container?->lc?->costing?->box_type }} KG</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="20">No data found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#lcSelect').select2();
            $('#containerSelect').select2();

            $('#lcSelect').on('change', function() {
                var lcId = $(this).val();
                var $containerSelect = $('#containerSelect');
                $containerSelect.html('<option value="">Loading...</option>');
                if (lcId) {
                    $.get('/stock-get-containers-by-lc/' + lcId, function(data) {
                        var options = '<option value="">-- Select Container --</option>';
                        data.forEach(function(container) {
                            options +=
                                `<option value="${container.id}">${container.name} (${container.number})</option>`;
                        });
                        $containerSelect.html(options).trigger('change');
                    });
                } else {
                    $containerSelect.html('<option value="">-- Select Container --</option>').trigger(
                        'change');
                }
            });
        });

        document.getElementById("downloadExcel").addEventListener("click", function() {
            let table = document.getElementById("buyingSellingTable");
            if (!table) {
                alert("Table not found!");
                return;
            }

            // Excel styling
            let style = `
        <style>
            * {
                font-family: Roboto, Arial, sans-serif;
            }
            table, th, td {
                border: 1px solid #000;
                border-collapse: collapse;
                text-align: center;
            }
            th, td {
                padding: 10px;
                height: 35px; /* row height */
                vertical-align: middle;
            }
            th {
                font-weight: bold;
            }
        </style>
    `;

            let tableHTML = style + table.outerHTML;

            let blob = new Blob(
                ['\ufeff' + tableHTML], {
                    type: "application/vnd.ms-excel"
                }
            );

            let url = URL.createObjectURL(blob);
            let a = document.createElement("a");
            a.href = url;
            a.download = "stock_report.xls";
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        });
    </script>
@endpush
