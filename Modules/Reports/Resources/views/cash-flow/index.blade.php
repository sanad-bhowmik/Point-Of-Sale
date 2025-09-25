@extends('layouts.app')

@section('title', 'Details of cash flow')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item active">Details of cash flow</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        <div>
            <!-- Table -->
            @if (isset($containers))
                <div class="card border-0 shadow-sm">
                    <div class="card-body position-relative">
                        <div class="mb-3">
                            <button id="download-pdf" class="btn btn-danger">Download PDF</button>
                            <button id="download-excel" class="btn btn-success">Download Excel</button>
                        </div>
                        <div class="table-responsive">
                            <table id="cashflow-table" class="table table-bordered table-striped">
                                <thead class="bg-success text-white">
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th colspan="2" style="font-size: 20px;">Details of cash flow</th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                    <tr>
                                        <th>SL</th>
                                        <th>Container Name</th>
                                        <th>Profit</th>
                                        <th>Loss</th>
                                        <th>Profit/Loss</th>
                                        <th>Supplier</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalProfit = 0;
                                        $totalLoss = 0;
                                        $pro_loss = $totalProfit - $totalLoss;
                                    @endphp

                                    @forelse ($containers as $index => $container)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $container->name ?? '-' }}</td>
                                            @php
                                                $totalCostAmount = \Modules\Expense\Entities\Expense::where('lc_id',$container->lc_id,)->where('container_id', $container->id)->sum('amount');

                                                $lcCost = $container?->lc_value * $container?->lc_exchange_rate * $container->qty;

                                                $ttCost = $container?->tt_value * $container?->tt_exchange_rate * $container->qty;

                                                $totalSale = \Modules\Sale\Entities\SaleDetails::where('lc_id', $container->lc_id)
                                                        ->where('container_id', $container->id)
                                                        ->sum('sub_total');

                                                $totalCost = $lcCost + $ttCost + $totalCostAmount;

                                                $profit_loss = $totalSale - $totalCost;

                                                if ($profit_loss > 0) {
                                                    $totalProfit += $profit_loss;
                                                } elseif ($profit_loss < 0) {
                                                    $totalLoss += abs($profit_loss);
                                                }
                                            @endphp
                                            <td>{{ $profit_loss > 0 ? round($profit_loss) : '-' }}</td>
                                            <td>{{ $profit_loss < 0 ? abs(round($profit_loss)) : '-' }}</td>
                                            @if ($profit_loss > 0)
                                                <td style="background-color: #9eff86;">Profit</td>
                                            @else
                                                <td style="background-color: #ff8e7a;">Loss</td>
                                            @endif
                                            <td>
                                                {{ $container?->lc?->costing?->supplier?->supplier_name ?? '-' }}
                                                -
                                                {{ $container?->lc?->costing?->product?->sizes->pluck('size')->implode('/') ?? '-' }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6">No data available.</td>
                                        </tr>
                                    @endforelse

                                    @if (count($containers) > 0)
                                        <tr style="background: #f1f1f1;">
                                            <td colspan="2">Total</td>
                                            <td>{{ round($totalProfit) }}</td>
                                            <td>{{ round($totalLoss) }}</td>
                                            <td>{{ round($totalProfit - $totalLoss) }}</td>
                                            @if ($pro_loss > 0)
                                                <td>Till now profit</td>
                                            @else
                                                <td>Till now loss</td>
                                            @endif
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#lcSelect').select2();
            $('#containerSelect').select2();
        });
    </script>

    <script>
        // PDF Download
        document.getElementById("download-pdf").addEventListener("click", () => {
            const {
                jsPDF
            } = window.jspdf;
            const doc = new jsPDF();

            // Title
            doc.setFontSize(16);
            doc.text("Details of Cash Flow", 14, 15);

            // Table
            doc.autoTable({
                html: '#cashflow-table',
                startY: 25,
                theme: 'grid',
                headStyles: {
                    fillColor: [40, 167, 69]
                } // Bootstrap green
            });

            doc.save("cash-flow.pdf");
        });

        // Excel Download
        // document.getElementById("download-excel").addEventListener("click", () => {
        //     let table = document.getElementById("cashflow-table");
        //     let wb = XLSX.utils.table_to_book(table, {
        //         sheet: "Cash Flow"
        //     });

        //     // Increase row height for all rows
        //     let ws = wb.Sheets["Cash Flow"];
        //     let rowCount = table.rows.length;
        //     ws['!rows'] = [];
        //     for (let i = 0; i < rowCount; i++) {
        //         ws['!rows'].push({
        //             hpt: 28
        //         }); 
        //     }

        //     ws['!cols'] = [{
        //             wch: 10
        //         }, // SL
        //         {
        //             wch: 25
        //         }, // Container Name
        //         {
        //             wch: 18
        //         }, // Profit
        //         {
        //             wch: 18
        //         }, // Loss
        //         {
        //             wch: 18
        //         }, // Profit/Loss
        //         {
        //             wch: 30
        //         } // Supplier
        //     ];

        //     XLSX.writeFile(wb, "cash-flow.xlsx");
        // });
        
        document.getElementById("download-excel").addEventListener("click", function() {
            let table = document.getElementById("cashflow-table");
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
