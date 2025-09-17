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
                    <form action="{{ route('buying-selling-report.index') }}" method="get">
                        <div class="row">
                            <!-- LC Select -->
                            <div class="col-md-4">
                                <label for="">Select Lc</label>
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
                                <label for="">Select Container</label>
                                <select class="form-control" id="containerSelect" name="container_id">
                                    <option value="">-- Select Container --</option>
                                    {{-- @foreach ($containerList as $container)
                                        <option value="{{ $container->id }}"
                                            {{ request('container_id') == $container->id ? 'selected' : '' }}>
                                            {{ $container->name }} ({{ $container->number }})
                                        </option>
                                    @endforeach --}}
                                </select>
                            </div>

                            <!-- Filter Button -->
                            <div class="col-md-2">
                                <button class="btn btn-primary w-100 mt-4" type="submit">Filter</button>
                            </div>
                            <div class="col-md-2">
                                <a href="{{ route('buying-selling-report.index') }}"
                                    class="btn btn-warning w-100 mt-4">Reset</a>
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
                                        <th colspan="17" style="font-size: 20px;">Buying & Selling Report</th>
                                    </tr>
                                    <tr style="background-color: #fff; color: #000;">
                                        <th colspan="17" style="font-size: 20px;">LC
                                            :-{{ $container?->load('lc')->lc?->lc_name }}</th>
                                    </tr>
                                    <tr style="background-color: #fff; color: #000;">
                                        <th colspan="17" style="font-size: 20px;">Container :-{{ $container?->name }}
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>SL</th>
                                        <th>Lc Number</th>
                                        <th>Container Number</th>
                                        <th>Product Name</th>
                                        <th>Product Description Size</th>
                                        <th>Supplier Name</th>
                                        <th>Our Company</th>
                                        <th>LC Date</th>
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
                                    @if (isset($container))
                                        <tr>
                                            <td>{{ $container->id }}</td>
                                            <td>{{ $container?->lc?->lc_number }}</td>
                                            <td>{{ $container->number }}</td>
                                            <td>{{ $container?->lc?->costing?->product?->product_name }}</td>
                                            @php
                                                $size = \App\Models\Size::find(
                                                    $container?->lc?->costing?->size,
                                                )->first();
                                            @endphp
                                            <td>{{ $size->size }}</td>
                                            <td>{{ $container?->lc?->costing?->supplier?->supplier_name }}</td>
                                            <td>Taifa Traders</td>
                                            <td>{{ $container?->lc_date }}</td>
                                            <td>{{ $container?->tt_date }}</td>
                                            <td>{{ round($container?->qty) }} Box <br>
                                                {{ $container?->qty * $container?->lc?->costing?->box_type }} KG
                                            </td>
                                            <td>{{ $container?->lc_value + $container?->tt_value }}</td>
                                            <td>{{ round(($container?->lc_value * $container?->lc_exchange_rate * $container->qty + $container?->tt_value * $container?->tt_exchange_rate * $container->qty) / $container?->qty) }}
                                            </td>
                                            @php
                                                $total =
                                                    $container?->lc_value *
                                                        $container?->lc_exchange_rate *
                                                        $container->qty +
                                                    $container?->tt_value *
                                                        $container?->tt_exchange_rate *
                                                        $container->qty;

                                                $dates = $sales->pluck('sale.date')->sort();

                                                $first = $dates->first();
                                                $last = $dates->last();

                                                $firstDate = $first
                                                    ? \Carbon\Carbon::parse($first)->format('d-m-y')
                                                    : '';
                                                $lastDate = $last ? \Carbon\Carbon::parse($last)->format('d-m-y') : '';

                                                $dateRange =
                                                    $firstDate && $lastDate ? $firstDate . ' to ' . $lastDate : '';
                                            @endphp
                                            <td>{{ round(($total + $totalAmount) / $container?->qty) }}
                                            </td>
                                            <td>{{ round(($total + $totalCostAmount) / $container?->qty) }}</td>
                                            <td>{{ $container?->lc?->costing?->box_type }}</td>
                                            <td>{{ $dateRange }}</td>
                                            <td>{{ round($totalSale / $container?->qty) }}</td>
                                            <td>{{ isset($totalSale) ? round(($totalSale - ($total + $totalCostAmount)) / $container?->qty) : '' }}
                                            </td>
                                        </tr>
                                    @else
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
                    $.get('/get-containers-by-lc/' + lcId, function(data) {
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

        // Excel Download
        // document.getElementById("downloadExcel").addEventListener("click", function() {
        //     var table = document.getElementById("buyingSellingTable");
        //     var wb = XLSX.utils.table_to_book(table, {
        //         sheet: "Buying Selling"
        //     });

        //     // Increase row height for all rows
        //     var ws = wb.Sheets["Buying Selling"];
        //     var rowCount = table.rows.length;
        //     ws['!rows'] = [];
        //     for (let i = 0; i < rowCount; i++) {
        //         ws['!rows'].push({
        //             hpt: 28
        //         }); // 28 points height
        //     }

        //     // Optional: Increase column width for all columns
        //     var colCount = table.rows[0].cells.length;
        //     ws['!cols'] = [];
        //     for (let i = 0; i < colCount; i++) {
        //         ws['!cols'].push({
        //             wch: 20
        //         }); // 20 characters width
        //     }

        //     XLSX.writeFile(wb, "buying-selling-report.xlsx");
        // });

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
            a.download = "bank_report.xls";
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        });
    </script>
@endpush
