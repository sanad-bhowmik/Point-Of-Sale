@extends('layouts.app')

@section('title', 'LC Costing Report')

@section('breadcrumb')
<ol class="breadcrumb border-0 m-0">
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
    <li class="breadcrumb-item active">LC Costing Report</li>
</ol>
@endsection

@section('content')
<div class="container-fluid mb-4">
    <div class="card shadow-sm rounded-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="fw-bold text-uppercase m-0">TAIFA TRADERSE - LC COSTING REPORT</h4>
            <button class="btn btn-success" onclick="exportTableToExcel('LcCostingReport')">
                <i class="bi bi-file-earmark-excel-fill"></i> Export Excel
            </button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="costingTable" class="table table-bordered align-middle text-center mb-0">
                    <thead class="table-warning">
                        <tr>
                            <th>COMPANY NAME</th>
                            <th>LC NUMBER</th>
                            <th>QTY</th>
                            <th>C AMMOUNT</th>
                            <th>TT</th>
                            <th>Container number</th>
                            <th>BANK COST</th>
                            <th>Duty</th>
                            <th>CNF</th>
                            <th>Car Rent</th>
                            <th>Arot / Cost Comm & Storage</th>
                            <th>Others</th>
                            <th>TOTAL COSTING</th>
                            <th>TOTAL SALE/CASH COLLECTED</th>
                            <th>TOTAL DEPOSIT</th>
                            <th>DUE/CASH IN HAND</th>
                            <th>PROFIT & LOSS AMOUNT</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $costings = \App\Models\Costing::with(['lc', 'lc.containers', 'supplier'])->get();
                        @endphp

                        @foreach($costings as $costing)
                        @php
                        $lc = $costing->lc;
                        $container = $lc?->containers?->first();
                        $supplier = $costing->supplier?->name ?? 'N/A';

                        $totalCost = $costing->total_tk
                        + $costing->insurance_tk
                        + $costing->landing_charge_tk
                        + $costing->total_tax
                        + $costing->transport
                        + $costing->arrot
                        + $costing->cns_charge
                        + $costing->others_total;

                        $totalSale = 0; // placeholder for future sales data
                        $totalDeposit = 0;
                        $due = $totalSale - $totalDeposit;
                        $profitLoss = $totalSale - $totalCost;
                        @endphp

                        <tr>
                            <td>{{ $supplier }}</td>
                            <td>{{ $lc?->lc_number ?? '—' }}</td>
                            <td>{{ $costing->qty }}</td>
                            <td>{{ number_format($costing->total_tk, 2) }}</td>
                            <td>{{ number_format($lc?->tt_value ?? 0, 2) }}</td>
                            <td>{{ $container?->number ?? '—' }}</td>
                            <td>{{ number_format($costing->insurance_tk, 2) }}</td>
                            <td>{{ number_format($costing->cd, 2) }}</td>
                            <td>{{ number_format($costing->landing_charge_tk, 2) }}</td>
                            <td>{{ number_format($costing->transport, 2) }}</td>
                            <td>{{ number_format($costing->arrot, 2) }}</td>
                            <td>{{ number_format($costing->others_total, 2) }}</td>
                            <td class="fw-bold text-primary">{{ number_format($totalCost, 2) }}</td>
                            <td>{{ number_format($totalSale, 2) }}</td>
                            <td>{{ number_format($totalDeposit, 2) }}</td>
                            <td>{{ number_format($due, 2) }}</td>
                            <td class="{{ $profitLoss < 0 ? 'text-danger' : 'text-success' }} fw-bold">
                                {{ number_format($profitLoss, 2) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Excel Export -->
<script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>
<script>
    function exportTableToExcel(filename = 'LcCostingReport') {
        var table = document.getElementById("costingTable");
        var wb = XLSX.utils.book_new();
        var ws = XLSX.utils.table_to_sheet(table);
        XLSX.utils.book_append_sheet(wb, ws, "LC Costing");
        XLSX.writeFile(wb, filename + ".xlsx");
    }
</script>

<style>
    .table-warning th {
        background-color: #FFFDE9 !important;
        color: #000;
        text-transform: uppercase;
        font-size: 13px;
    }

    .table td,
    .table th {
        vertical-align: middle !important;
        padding: 8px;
    }

    .text-success {
        color: #2e7d32 !important;
    }

    .text-danger {
        color: #c62828 !important;
    }

    h4 {
        color: #2c3e50;
        font-weight: 700;
    }
</style>
@endsection
