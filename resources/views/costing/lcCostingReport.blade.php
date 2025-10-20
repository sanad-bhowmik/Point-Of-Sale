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
                        <tr>
                            <th>Supplier Name</th>
                            <th>LC Number</th>
                            <th>LC Amount</th>
                            <th>Container Number</th>
                            <th>Bank Cost</th>
                            <th>Duty</th>
                            <th>C&F</th>
                            <th>Car Rent</th>
                            <th>Arrot</th>
                            <th>Other</th>
                            <th>Total Costing</th>
                            <th>Total Sales</th>
                            <th>Total Deposit</th>
                            <th>Due</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $costings = \App\Models\Costing::with(['supplier', 'lc.containers'])->get();
                        @endphp

                        @foreach($costings as $costing)
                            @php
                                $supplierName = optional($costing->supplier)->supplier_name ?? 'N/A';
                                $lcNumber = optional($costing->lc)->lc_number ?? '—';
                                $containers = $costing->lc?->containers ?? collect([]);
                                $totalTk = (int)$costing->total_tk;
                            @endphp

                            @forelse($containers as $container)
                                @php
                                    $lcId = $costing->lc_id;
                                    $containerId = $container->id;

                                    $bankCost = \DB::table('expenses')
                                        ->where('lc_id', $lcId)
                                        ->where('container_id', $containerId)
                                        ->where('category_id', 5)
                                        ->sum('amount');

                                    $duty = \DB::table('expenses')
                                        ->where('lc_id', $lcId)
                                        ->where('container_id', $containerId)
                                        ->where('category_id', 4)
                                        ->sum('amount');

                                    $cnf = \DB::table('expenses')
                                        ->where('lc_id', $lcId)
                                        ->where('container_id', $containerId)
                                        ->where('category_id', 3)
                                        ->sum('amount');

                                    $carRent = \DB::table('expenses')
                                        ->where('lc_id', $lcId)
                                        ->where('container_id', $containerId)
                                        ->where('category_id', 6)
                                        ->where('expense_name_id', 21)
                                        ->sum('amount');

                                    $arrot = \DB::table('expenses')
                                        ->where('lc_id', $lcId)
                                        ->where('container_id', $containerId)
                                        ->where('category_id', 6)
                                        ->where('expense_name_id', 23)
                                        ->sum('amount');

                                    $other = \DB::table('expenses')
                                        ->where('lc_id', $lcId)
                                        ->where('container_id', $containerId)
                                        ->where('category_id', 6)
                                        ->where('expense_name_id', 24)
                                        ->sum('amount');

                                    // Total Costing default 0
                                    $totalCosting = 0;

                                    // Total Sales from sales table
                                    $totalSales = \DB::table('sales')
                                        ->where('lc_id', $lcId)
                                        ->where('container_id', $containerId)
                                        ->sum('total_amount');

                                    // Total Deposit default 0
                                    $totalDeposit = 0;

                                    // Due from sales table
                                    $due = \DB::table('sales')
                                        ->where('lc_id', $lcId)
                                        ->where('container_id', $containerId)
                                        ->sum('due_amount');
                                @endphp
                                <tr>
                                    <td>{{ $supplierName }}</td>
                                    <td>{{ $lcNumber }}</td>
                                    <td class="fw-bold text-primary">{{ $totalTk }}</td>
                                    <td>{{ $container->number ?? '—' }}</td>
                                    <td>{{ (int)$bankCost }}</td>
                                    <td>{{ (int)$duty }}</td>
                                    <td>{{ (int)$cnf }}</td>
                                    <td>{{ (int)$carRent }}</td>
                                    <td>{{ (int)$arrot }}</td>
                                    <td>{{ (int)$other }}</td>
                                    <td>{{ (int)$totalCosting }}</td>
                                    <td>{{ (int)$totalSales }}</td>
                                    <td>{{ (int)$totalDeposit }}</td>
                                    <td>{{ (int)$due }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td>{{ $supplierName }}</td>
                                    <td>{{ $lcNumber }}</td>
                                    <td class="fw-bold text-primary">{{ $totalTk }}</td>
                                    <td>—</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
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
    .table th,
    .table td {
        vertical-align: middle !important;
        padding: 8px;
    }

    .text-primary {
        color: #0d6efd !important;
    }

    h4 {
        color: #2c3e50;
        font-weight: 700;
    }
</style>
@endsection
