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
                        <div class="mb-3 mt-3">
                            <button id="downloadExcel" class="btn btn-success">Download Excel</button>
                        </div>

                        <div class="table-responsive">
                            <table id="shipmentStatusTable" class="table table-bordered table-striped">
                                <thead class="bg-success text-white">
                                    <tr style="background-color: #fff; color: #000;">
                                        <th colspan="17" style="font-size: 20px;">Shipment Status Report</th>
                                    </tr>
                                    <tr style="background-color: #fff; color: #000;">
                                        <th colspan="17" style="font-size: 20px;">LC :-{{ $container?->lc?->lc_name }}</th>
                                    </tr>
                                    <tr style="background-color: #fff; color: #000;">
                                        <th colspan="17" style="font-size: 20px;">Container :-{{ $container?->name }}</th>
                                    </tr>
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
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $item->lc->lc_number }}</td>
                                            <td>{{ $container->number }}</td>
                                            <td>{{ $item?->product?->product_name }}</td>
                                            @php
                                                $size = \App\Models\Size::find($item->size)->first();
                                            @endphp
                                            <td>{{ $size->size }}</td>
                                            <td>{{ $item?->supplier?->supplier_name }}</td>
                                            <td>Taifa Traders</td>
                                            <td>{{ round($item->qty) }} Box <br> {{ $item->qty * $item->box_type }} KG
                                            </td>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#lcSelect').select2();
            $('#containerSelect').select2();
        });

        // Excel Download
        // document.getElementById("downloadExcel").addEventListener("click", function() {
        //     var table = document.getElementById("shipmentStatusTable");
        //     var wb = XLSX.utils.table_to_book(table, {
        //         sheet: "Shipment Status"
        //     });

        //     // Increase row height for all rows
        //     var ws = wb.Sheets["Shipment Status"];
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

        //     XLSX.writeFile(wb, "shipment-status-report.xlsx");
        // });

        document.getElementById("downloadExcel").addEventListener("click", function() {
            let table = document.getElementById("shipmentStatusTable");
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
