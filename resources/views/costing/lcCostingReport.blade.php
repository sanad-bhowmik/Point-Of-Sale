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
                    <thead class="table-dark">
                        <thead class="table-warning">
                            <tr>
                                <th>Supplier Name</th>
                                <th>LC Number</th>
                                <th>Product ID</th>
                                <th>Box Type</th>
                                <th>Size</th>
                                <th>Currency</th>
                                <th>Base Value</th>
                                <th>Quantity</th>
                                <th>Exchange Rate</th>
                                <th>Total (Foreign)</th>
                                <th>Total (BDT)</th>
                                <th>Insurance</th>
                                <th>Insurance (Tk)</th>
                                <th>Landing Charge</th>
                                <th>Landing Charge (Tk)</th>
                                <th>CD</th>
                                <th>RD</th>
                                <th>SD</th>
                                <th>VAT</th>
                                <th>AIT</th>
                                <th>AT</th>
                                <th>ATV</th>
                                <th>TT Amount</th>
                                <th>Total Tax</th>
                                <th>Transport</th>
                                <th>Arot</th>
                                <th>C&F Charge</th>
                                <th>Others Total</th>
                                <th>Total Tariff (LC)</th>
                                <th>Tariff per Ton (LC)</th>
                                <th>Tariff per Kg (LC)</th>
                                <th>Actual Cost per Kg</th>
                                <th>Total Cost per Kg</th>
                                <th>Total Cost per Box</th>
                                <th>Container Number</th>
                                <th>Total Costing</th>
                                <th>Profit / Loss</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                            </tr>
                        </thead>

                    </thead>
                    <tbody>
                        @php
                        $costings = \App\Models\Costing::with(['lc.containers', 'supplier'])->get();
                        @endphp

                        @foreach($costings as $costing)
                        @php
                        $lc = $costing->lc;
                        $supplierName = optional($costing->supplier)->supplier_name ?? 'N/A';
                        $containers = $lc?->containers ?? collect([]);

                        $totalCost =
                        $costing->total_tk +
                        $costing->insurance_tk +
                        $costing->landing_charge_tk +
                        $costing->total_tax +
                        $costing->transport +
                        $costing->arrot +
                        $costing->cns_charge +
                        $costing->others_total;

                        $profitLoss = -$totalCost; // adjust when you have sales/deposit data
                        @endphp

                        @forelse($containers as $container)
                        <tr>
                            <td>{{ $supplierName }}</td>
                            <td>{{ $lc?->lc_number ?? '—' }}</td>
                            <td>{{ $costing->product_id }}</td>
                            <td>{{ $costing->box_type }}</td>
                            <td>{{ $costing->size }}</td>
                            <td>{{ $costing->currency }}</td>
                            <td>{{ number_format($costing->base_value, 2) }}</td>
                            <td>{{ $costing->qty }}</td>
                            <td>{{ number_format($costing->exchange_rate, 2) }}</td>
                            <td>{{ number_format($costing->total, 2) }}</td>
                            <td>{{ number_format($costing->total_tk, 2) }}</td>
                            <td>{{ number_format($costing->insurance, 2) }}</td>
                            <td>{{ number_format($costing->insurance_tk, 2) }}</td>
                            <td>{{ number_format($costing->landing_charge, 2) }}</td>
                            <td>{{ number_format($costing->landing_charge_tk, 2) }}</td>
                            <td>{{ number_format($costing->cd, 2) }}</td>
                            <td>{{ number_format($costing->rd, 2) }}</td>
                            <td>{{ number_format($costing->sd, 2) }}</td>
                            <td>{{ number_format($costing->vat, 2) }}</td>
                            <td>{{ number_format($costing->ait, 2) }}</td>
                            <td>{{ number_format($costing->at, 2) }}</td>
                            <td>{{ number_format($costing->atv, 2) }}</td>
                            <td>{{ number_format($costing->tt_amount, 2) }}</td>
                            <td>{{ number_format($costing->total_tax, 2) }}</td>
                            <td>{{ number_format($costing->transport, 2) }}</td>
                            <td>{{ number_format($costing->arrot, 2) }}</td>
                            <td>{{ number_format($costing->cns_charge, 2) }}</td>
                            <td>{{ number_format($costing->others_total, 2) }}</td>
                            <td>{{ number_format($costing->total_tariff_lc, 2) }}</td>
                            <td>{{ number_format($costing->tariff_per_ton_lc, 2) }}</td>
                            <td>{{ number_format($costing->tariff_per_kg_lc, 2) }}</td>
                            <td>{{ number_format($costing->actual_cost_per_kg, 2) }}</td>
                            <td>{{ number_format($costing->total_cost_per_kg, 2) }}</td>
                            <td>{{ number_format($costing->total_cost_per_box, 2) }}</td>
                            <td>{{ $container->number ?? '—' }}</td>
                            <td class="fw-bold text-primary">{{ number_format($totalCost, 2) }}</td>
                            <td class="{{ $profitLoss < 0 ? 'text-danger' : 'text-success' }} fw-bold">
                                {{ number_format($profitLoss, 2) }}
                            </td>
                            <td>{{ $costing->created_at?->format('d-M-Y') }}</td>
                            <td>{{ $costing->updated_at?->format('d-M-Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td>{{ $supplierName }}</td>
                            <td>{{ $lc?->lc_number ?? '—' }}</td>
                            <td>{{ $costing->product_id }}</td>
                            <td>{{ $costing->box_type }}</td>
                            <td>{{ $costing->size }}</td>
                            <td>{{ $costing->currency }}</td>
                            <td>{{ number_format($costing->base_value, 2) }}</td>
                            <td>{{ $costing->qty }}</td>
                            <td>{{ number_format($costing->exchange_rate, 2) }}</td>
                            <td>{{ number_format($costing->total, 2) }}</td>
                            <td>{{ number_format($costing->total_tk, 2) }}</td>
                            <td>{{ number_format($costing->insurance, 2) }}</td>
                            <td>{{ number_format($costing->insurance_tk, 2) }}</td>
                            <td>{{ number_format($costing->landing_charge, 2) }}</td>
                            <td>{{ number_format($costing->landing_charge_tk, 2) }}</td>
                            <td>{{ number_format($costing->cd, 2) }}</td>
                            <td>{{ number_format($costing->rd, 2) }}</td>
                            <td>{{ number_format($costing->sd, 2) }}</td>
                            <td>{{ number_format($costing->vat, 2) }}</td>
                            <td>{{ number_format($costing->ait, 2) }}</td>
                            <td>{{ number_format($costing->at, 2) }}</td>
                            <td>{{ number_format($costing->atv, 2) }}</td>
                            <td>{{ number_format($costing->tt_amount, 2) }}</td>
                            <td>{{ number_format($costing->total_tax, 2) }}</td>
                            <td>{{ number_format($costing->transport, 2) }}</td>
                            <td>{{ number_format($costing->arrot, 2) }}</td>
                            <td>{{ number_format($costing->cns_charge, 2) }}</td>
                            <td>{{ number_format($costing->others_total, 2) }}</td>
                            <td>{{ number_format($costing->total_tariff_lc, 2) }}</td>
                            <td>{{ number_format($costing->tariff_per_ton_lc, 2) }}</td>
                            <td>{{ number_format($costing->tariff_per_kg_lc, 2) }}</td>
                            <td>{{ number_format($costing->actual_cost_per_kg, 2) }}</td>
                            <td>{{ number_format($costing->total_cost_per_kg, 2) }}</td>
                            <td>{{ number_format($costing->total_cost_per_box, 2) }}</td>
                            <td>—</td>
                            <td class="fw-bold text-primary">{{ number_format($totalCost, 2) }}</td>
                            <td class="{{ $profitLoss < 0 ? 'text-danger' : 'text-success' }} fw-bold">
                                {{ number_format($profitLoss, 2) }}
                            </td>
                            <td>{{ $costing->created_at?->format('d-M-Y') }}</td>
                            <td>{{ $costing->updated_at?->format('d-M-Y') }}</td>
                        </tr>
                        @endforelse
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Excel Export Script -->
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
        font-size: 12px;
    }

    .table td,
    .table th {
        vertical-align: middle !important;
        padding: 6px;
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
