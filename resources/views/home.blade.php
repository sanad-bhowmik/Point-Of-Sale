@extends('layouts.app')

@section('title', 'Home')

@section('breadcrumb')
<ol class="breadcrumb border-0 m-0">
    <li class="breadcrumb-item active">Home</li>
</ol>
@endsection

@section('content')
<div class="container-fluid">
    @can('show_total_stats')
    <div class="row">
        <div class="col-md-6 col-lg-4">
            <div class="card border-0">
                <div class="card-body p-0 d-flex align-items-center shadow-sm">
                    <div class="bg-gradient-primary p-4 mfe-3 rounded-left">
                        <i class="bi bi-bar-chart font-2xl"></i>
                    </div>
                    <div>
                        <div class="text-value text-primary">{{ format_currency($revenue) }}</div>
                        <div class="text-muted text-uppercase font-weight-bold small">Revenue</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="card border-0">
                <div class="card-body p-0 d-flex align-items-center shadow-sm">
                    <div class="bg-gradient-warning p-4 mfe-3 rounded-left">
                        <i class="bi bi-arrow-return-left font-2xl"></i>
                    </div>
                    <div>
                        <div class="text-value text-warning">{{ format_currency($sales) }}</div>
                        <div class="text-muted text-uppercase font-weight-bold small">Total Sales</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3 d-none">
            <div class="card border-0">
                <div class="card-body p-0 d-flex align-items-center shadow-sm">
                    <div class="bg-gradient-success p-4 mfe-3 rounded-left">
                        <i class="bi bi-arrow-return-right font-2xl"></i>
                    </div>
                    <div>
                        <div class="text-value text-success">{{ format_currency($purchase_returns) }}</div>
                        <div class="text-muted text-uppercase font-weight-bold small">Purchases Return</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="card border-0">
                <div class="card-body p-0 d-flex align-items-center shadow-sm">
                    <div class="bg-gradient-info p-4 mfe-3 rounded-left">
                        <i class="bi bi-trophy font-2xl"></i>
                    </div>
                    <div>
                        <div class="text-value text-info">{{ format_currency($profit) }}</div>
                        <div class="text-muted text-uppercase font-weight-bold small">Profit</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endcan

    <!-- New Section: Bank Balance and Status Counts -->
    <div class="row mb-4">
        <!-- Left Panel: Status-wise Counts -->
        <div class="col-lg-6">
            <div class="card modern-card border-0 h-100">
                <div class="card-header bg-gradient-info text-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 d-flex align-items-center fw-bold">
                        <i class="bi bi-box me-2 fs-5 mr-2"></i>
                        Container Status Overview

                    </h6>
                     <a href="container/view" class="btn btn-outline-light btn-sm">
                        <i class="bi bi-plus-circle me-1"></i> Add Container
                    </a>
                </div>

                <div class="card-body">
                    @php
                    use Illuminate\Support\Facades\DB;

                    $pending = DB::table('container')->where('status', 0)->count();
                    $shipped = DB::table('container')->where('status', 1)->count();
                    $arrived = DB::table('container')->where('status', 2)->count();
                    $customDone = DB::table('container')->where('status', 3)->count();

                    $totalContainers = $pending + $shipped + $arrived + $customDone;

                    // Calculate percentages
                    $pendingPercent = $totalContainers > 0 ? round(($pending / $totalContainers) * 100) : 0;
                    $shippedPercent = $totalContainers > 0 ? round(($shipped / $totalContainers) * 100) : 0;
                    $arrivedPercent = $totalContainers > 0 ? round(($arrived / $totalContainers) * 100) : 0;
                    $customDonePercent = $totalContainers > 0 ? round(($customDone / $totalContainers) * 100) : 0;

                    // Get yesterday's counts for comparison
                    $yesterdayPending = DB::table('container')->where('status', 0)->whereDate('created_at', today()->subDay())->count();
                    $yesterdayShipped = DB::table('container')->where('status', 1)->whereDate('created_at', today()->subDay())->count();
                    $yesterdayArrived = DB::table('container')->where('status', 2)->whereDate('created_at', today()->subDay())->count();
                    $yesterdayCustomDone = DB::table('container')->where('status', 3)->whereDate('created_at', today()->subDay())->count();

                    // Calculate changes
                    $pendingChange = $pending - $yesterdayPending;
                    $shippedChange = $shipped - $yesterdayShipped;
                    $arrivedChange = $arrived - $yesterdayArrived;
                    $customDoneChange = $customDone - $yesterdayCustomDone;
                    @endphp

                    <div class="row g-3">
                        <!-- Pending -->
                        <div class="col-md-6">
                            <div class="modern-status-card p-3 rounded position-relative h-100">
                                <div class="d-flex align-items-start mb-2">
                                    <div class="modern-icon-container bg-blue-soft p-2 rounded me-3">
                                        <i class="bi bi-hourglass-split text-blue fs-5"></i>
                                    </div>
                                    <div class="flex-grow-1 ml-2">
                                        <div class="text-dark-600 small mb-1">Pending</div>
                                        <div class="fw-bold fs-3 text-dark">{{ $pending }}</div>
                                    </div>
                                    <div class="text-blue fs-6 fw-semibold">{{ $pendingPercent }}%</div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <div class="text-dark-600 small">Waiting for shipment</div>
                                    @if($pendingChange > 0)
                                    <span class="badge bg-blue-soft text-blue">
                                        <i class="bi bi-arrow-up"></i> {{ $pendingChange }}
                                    </span>
                                    @elseif($pendingChange < 0)
                                        <span class="badge bg-red-soft text-red">
                                        <i class="bi bi-arrow-down"></i> {{ abs($pendingChange) }}
                                        </span>
                                        @else
                                        <span class="badge bg-gray-200 text-dark-600">
                                            <i class="bi bi-dash"></i> 0
                                        </span>
                                        @endif
                                </div>
                                <div class="progress modern-progress mt-2">
                                    <div class="progress-bar bg-blue" role="progressbar" style="width: {{ $pendingPercent }}%" aria-valuenow="{{ $pendingPercent }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Shipped -->
                        <div class="col-md-6">
                            <div class="modern-status-card p-3 rounded position-relative h-100">
                                <div class="d-flex align-items-start mb-2">
                                    <div class="modern-icon-container bg-green-soft p-2 rounded me-3">
                                        <i class="bi bi-truck text-green fs-5"></i>
                                    </div>
                                    <div class="flex-grow-1 ml-2">
                                        <div class="text-dark-600 small mb-1">Shipped</div>
                                        <div class="fw-bold fs-3 text-dark">{{ $shipped }}</div>
                                    </div>
                                    <div class="text-green fs-6 fw-semibold">{{ $shippedPercent }}%</div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <div class="text-dark-600 small">On the way</div>
                                    @if($shippedChange > 0)
                                    <span class="badge bg-green-soft text-green">
                                        <i class="bi bi-arrow-up"></i> {{ $shippedChange }}
                                    </span>
                                    @elseif($shippedChange < 0)
                                        <span class="badge bg-red-soft text-red">
                                        <i class="bi bi-arrow-down"></i> {{ abs($shippedChange) }}
                                        </span>
                                        @else
                                        <span class="badge bg-gray-200 text-dark-600">
                                            <i class="bi bi-dash"></i> 0
                                        </span>
                                        @endif
                                </div>
                                <div class="progress modern-progress mt-2">
                                    <div class="progress-bar bg-green" role="progressbar" style="width: {{ $shippedPercent }}%" aria-valuenow="{{ $shippedPercent }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Arrived -->
                        <div class="col-md-6">
                            <div class="modern-status-card p-3 rounded position-relative h-100">
                                <div class="d-flex align-items-start mb-2">
                                    <div class="modern-icon-container bg-orange-soft p-2 rounded me-3">
                                        <i class="bi bi-box-seam text-orange fs-5"></i>
                                    </div>
                                    <div class="flex-grow-1 ml-2">
                                        <div class="text-dark-600 small mb-1">Arrived</div>
                                        <div class="fw-bold fs-3 text-dark">{{ $arrived }}</div>
                                    </div>
                                    <div class="text-orange fs-6 fw-semibold">{{ $arrivedPercent }}%</div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <div class="text-dark-600 small">Reached destination</div>
                                    @if($arrivedChange > 0)
                                    <span class="badge bg-orange-soft text-orange">
                                        <i class="bi bi-arrow-up"></i> {{ $arrivedChange }}
                                    </span>
                                    @elseif($arrivedChange < 0)
                                        <span class="badge bg-red-soft text-red">
                                        <i class="bi bi-arrow-down"></i> {{ abs($arrivedChange) }}
                                        </span>
                                        @else
                                        <span class="badge bg-gray-200 text-dark-600">
                                            <i class="bi bi-dash"></i> 0
                                        </span>
                                        @endif
                                </div>
                                <div class="progress modern-progress mt-2">
                                    <div class="progress-bar bg-orange" role="progressbar" style="width: {{ $arrivedPercent }}%" aria-valuenow="{{ $arrivedPercent }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Custom Done -->
                        <div class="col-md-6">
                            <div class="modern-status-card p-3 rounded position-relative h-100">
                                <div class="d-flex align-items-start mb-2">
                                    <div class="modern-icon-container bg-purple-soft p-2 rounded me-3">
                                        <i class="bi bi-check-circle text-purple fs-5"></i>
                                    </div>
                                    <div class="flex-grow-1 ml-2">
                                        <div class="text-dark-600 small mb-1">Custom Done</div>
                                        <div class="fw-bold fs-3 text-dark">{{ $customDone }}</div>
                                    </div>
                                    <div class="text-purple fs-6 fw-semibold">{{ $customDonePercent }}%</div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <div class="text-dark-600 small">Cleared by customs</div>
                                    @if($customDoneChange > 0)
                                    <span class="badge bg-purple-soft text-purple">
                                        <i class="bi bi-arrow-up"></i> {{ $customDoneChange }}
                                    </span>
                                    @elseif($customDoneChange < 0)
                                        <span class="badge bg-red-soft text-red">
                                        <i class="bi bi-arrow-down"></i> {{ abs($customDoneChange) }}
                                        </span>
                                        @else
                                        <span class="badge bg-gray-200 text-dark-600">
                                            <i class="bi bi-dash"></i> 0
                                        </span>
                                        @endif
                                </div>
                                <div class="progress modern-progress mt-2">
                                    <div class="progress-bar bg-purple" role="progressbar" style="width: {{ $customDonePercent }}%" aria-valuenow="{{ $customDonePercent }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Summary Section -->
                    <div class="mt-4 pt-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-dark-600 small">Total Containers: <span class="fw-semibold text-dark">{{ $totalContainers }}</span></div>
                            <div class="text-dark-600 small">Updated: <span class="fw-semibold text-dark">{{ now()->format('M j, Y g:i A') }}</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Panel: Bank-wise Balances -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <!-- Card Header -->
                <div class="card-header bg-gradient-info text-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 d-flex align-items-center fw-bold">
                        <i class="bi bi-box me-2 fs-5 mr-2"></i>
                        Pending Containers
                    </h6>

                    <a href="container/containerTbl" class="btn btn-outline-light btn-sm">
                        <i class="bi bi-eye me-1"></i> View Container
                    </a>
                </div>

                <!-- Card Body -->
                <div class="card-body p-0">
                    @php
                    $containers = DB::table('container')
                    ->select('id', 'name', 'number', 'shipping_date', 'status')
                    ->where('status', 0)
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get();
                    @endphp

                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light text-uppercase small">
                                <tr>
                                    <th>Name</th>
                                    <th>Number</th>
                                    <th>Shipping Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($containers as $container)
                                <tr class="align-middle animate__animated animate__fadeInUp" style="transition: all 0.3s; cursor: pointer;">
                                    <td class="fw-semibold">{{ $container->name }}</td>
                                    <td>{{ $container->number }}</td>
                                    <td>{{ \Carbon\Carbon::parse($container->shipping_date)->format('d M Y') }}</td>
                                    <td>
                                        <span class="badge rounded-pill bg-warning text-dark">Pending</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">
                                        No pending containers found.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Card Footer -->
                <div class="card-footer text-end bg-light border-top">
                    <small class="text-muted">Showing latest 5 pending containers</small>
                </div>
            </div>
        </div>

        <!-- Animate.css CDN for animations -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    </div>

    <div class="col-lg-12">
        @if (isset($containers))
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-gradient-info text-white">
                        <h6 class="mb-0 fw-bold" style="display: flex;">
                            <div class="bank-icon me-2">
                                <i class="bi bi-currency-dollar"></i>
                            </div> <span class="mt-2 ml-2">Details of cash flow</span>
                        </h6>
                    </div>
                    <div class="card-body position-relative p-0">
                        <div class="table-responsive">
                            <table id="cashflow-table" class="table table-bordered table-striped">
                                <thead>
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


    <div class="col-lg-12">
        <div class="card border-0 shadow-sm h-50">
            <div class="card-header bg-gradient-info text-white d-flex justify-content-between align-items-center">
                   <h6 class="mb-0 fw-bold" style="display: flex;">
                    <div class="bank-icon me-2">
                        <i class="bi bi-bank"></i>
                    </div> <span class="mt-2 ml-2">Bank Balances</span>
                </h6>
                    <a href="{{ route('bank.create') }}" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-plus-circle"></i> Add Bank
                </a>
            </div>


            <div class="card-body">
                @php
                $banks = DB::table('banks')
                ->select('id', 'bank_name', 'account_no', 'opening_balance','institution')
                ->get();

                foreach ($banks as $bank) {
                $transactions = DB::table('transactions')
                ->where('bank_id', $bank->id)
                ->select(
                DB::raw('SUM(in_amount) as total_in'),
                DB::raw('SUM(out_amount) as total_out')
                )
                ->first();

                $bank->last_balance = $bank->opening_balance
                + ($transactions->total_in ?? 0)
                - ($transactions->total_out ?? 0);
                }

                $totalBalance = $banks->sum('last_balance');
                @endphp

                <div class="table-responsive">
                    <table class="table align-middle table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Institution</th>
                                <th>Bank</th>
                                <th>Account No.</th>
                                <th class="text-end">Opening Balance</th>
                                <th class="text-end">Last Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($banks as $bank)
                            <tr style="font-weight: 500;font-family: auto;font-size: large;">
                                <td>
                                    <span class="text-muted">{{ $bank->institution }}</span>
                                </td>
                                <td>
                                    <a href="https://wholesale.uzanvati.com/banks">
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <span class="fw-semibold">{{ $bank->bank_name }}</span>
                                            </div>
                                        </div>
                                    </a>
                                </td>
                                <td>
                                    <span class="text-muted">{{ $bank->account_no }}</span>
                                </td>

                                <td class="text-end fw-bold {{ $bank->opening_balance < 0 ? 'text-danger' : 'text-success' }}">
                                    {{ format_currency($bank->opening_balance) }}
                                </td>
                                <td class="text-end fw-bold {{ $bank->last_balance < 0 ? 'text-danger' : 'text-danger' }}">
                                    {{ format_currency($bank->last_balance) }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">No banks available</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <!-- Live Charts Section -->
    <div class="row mb-4">
        <!-- Container Status Chart -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold">
                        <i class="bi bi-bar-chart-line me-2"></i> Live Container Status
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="containerChart" height="100"></canvas>
                </div>
            </div>
        </div>

        <!-- Container Costing Chart -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold">
                        <i class="bi bi-currency-dollar me-2"></i> Container Costing
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="costingChart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>

    @php

    // Container counts (already in your code)
    $pending = DB::table('container')->where('status', 0)->count();
    $shipped = DB::table('container')->where('status', 1)->count();
    $arrived = DB::table('container')->where('status', 2)->count();
    $customDone = DB::table('container')->where('status', 3)->count();

    // Costing data (sum total_cost_per_box by box_type)
    $costingData = DB::table('costing')
    ->select('box_type', DB::raw('SUM(total_cost_per_box) as total_cost'))
    ->groupBy('box_type')
    ->get();
    @endphp

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Container Status Chart
    const containerData = {
        pending: {{ $pending }},
        shipped: {{ $shipped }},
        arrived: {{ $arrived }},
        customDone: {{ $customDone }},
    };

    new Chart(document.getElementById("containerChart").getContext("2d"), {
        type: "bar",
        data: {
            labels: ["Pending", "Shipped", "Arrived", "Custom Done"],
            datasets: [{
                label: "Containers",
                data: [
                    containerData.pending,
                    containerData.shipped,
                    containerData.arrived,
                    containerData.customDone
                ],
                backgroundColor: ["#3b82f6", "#22c55e", "#f97316", "#a855f7"],
                borderRadius: 6,
                barPercentage: 0.5
            }]
        },
        options: {
            indexAxis: 'y', // horizontal
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { x: { beginAtZero: true }, y: { ticks: { font: { size: 14 } } } }
        }
    });

    // Container Costing Chart
    const costingLabels = @json($costingData->pluck('box_type'));
    const costingValues = @json($costingData->pluck('total_cost'));

            new Chart(document.getElementById("costingChart").getContext("2d"), {
                type: "bar",
                data: {
                    labels: costingLabels,
                    datasets: [{
                        label: "Total Cost per Box",
                        data: costingValues,
                        backgroundColor: "#1552FA",
                        borderRadius: 6,
                        barPercentage: 0.5
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(ctx) {
                                    return "TK " + ctx.raw;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true
                        },
                        y: {
                            ticks: {
                                font: {
                                    size: 14
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>


</div>
@endsection

@section('third_party_scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.5.0/chart.min.js"
    integrity="sha512-asxKqQghC1oBShyhiBwA+YgotaSYKxGP1rcSYTDrB0U6DxwlJjU59B67U8+5/++uFjcuVM8Hh5cokLjZlhm3Vg=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection

@push('page_scripts')
@vite('resources/js/chart-config.js')
@endpush
<style>
    .bank-icon {
        background: linear-gradient(135deg, #007bff, #0056b3);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 38px;
        border-radius: 50%;
        font-size: 1.2rem;
        transition: transform 0.2s ease-in-out;
    }

    .bank-icon:hover {
        transform: scale(1.1);
    }

    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }

    .modern-card {
        background: #fff;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        border-radius: 12px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .modern-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
    }

    .modern-status-card {
        background: #fff;
        border: 1px solid #f0f0f0;
        transition: all 0.3s ease;
    }

    .modern-status-card:hover {
        border-color: #e0e0e0;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.03);
    }

    .modern-icon-container {
        transition: transform 0.3s ease;
    }

    .modern-status-card:hover .modern-icon-container {
        transform: scale(1.1);
    }

    .modern-progress {
        height: 4px;
        border-radius: 10px;
        background-color: #f5f5f5;
    }

    .modern-progress .progress-bar {
        border-radius: 10px;
    }

    .modern-dropdown {
        border: 1px solid #eaeaea;
        border-radius: 8px;
        padding: 0.35rem 0.75rem;
        font-size: 0.8rem;
    }

    /* Modern color scheme */
    .text-blue {
        color: #3b82f6;
    }

    .bg-blue {
        background-color: #3b82f6;
    }

    .bg-blue-soft {
        background-color: rgba(59, 130, 246, 0.1);
    }

    .text-green {
        color: #10b981;
    }

    .bg-green {
        background-color: #10b981;
    }

    .bg-green-soft {
        background-color: rgba(16, 185, 129, 0.1);
    }

    .text-orange {
        color: #f59e0b;
    }

    .bg-orange {
        background-color: #f59e0b;
    }

    .bg-orange-soft {
        background-color: rgba(245, 158, 11, 0.1);
    }

    .text-purple {
        color: #8b5cf6;
    }

    .bg-purple {
        background-color: #8b5cf6;
    }

    .bg-purple-soft {
        background-color: rgba(139, 92, 246, 0.1);
    }

    .text-red {
        color: #ef4444;
    }

    .bg-red-soft {
        background-color: rgba(239, 68, 68, 0.1);
    }

    .bg-gray-200 {
        background-color: #f9fafb;
    }

    .text-dark-600 {
        color: #6b7280;
    }

    .dropdown-menu {
        border-radius: 10px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        border: 1px solid #f0f0f0;
    }

    .badge {
        border-radius: 6px;
        padding: 0.35em 0.65em;
        font-weight: 500;
    }
    .c-footer{
        display: none !important;
    }

    th,td{
        font-size: 12px !important;
        padding: 6px !important;
        font-weight: 400;
    }

    .card-header {
        padding: 6px !important;
    }
    #cashflow-table th,
    #cashflow-table td {
        width: 200px;
        white-space: nowrap;
        text-align: center;
        vertical-align: middle;
    }
</style>
