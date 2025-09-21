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
        <div class="col-md-6 col-lg-3">
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

        <div class="col-md-6 col-lg-3">
            <div class="card border-0">
                <div class="card-body p-0 d-flex align-items-center shadow-sm">
                    <div class="bg-gradient-warning p-4 mfe-3 rounded-left">
                        <i class="bi bi-arrow-return-left font-2xl"></i>
                    </div>
                    <div>
                        <div class="text-value text-warning">{{ format_currency($sale_returns) }}</div>
                        <div class="text-muted text-uppercase font-weight-bold small">Sales Return</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
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

        <div class="col-md-6 col-lg-3">
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
                <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center pb-0">
                    <h6 class="mb-0 fw-semibold text-dark">Container Status Overview</h6>

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
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold" style="display: flex;">
                        <div class="bank-icon me-2">
                            <i class="bi bi-bank"></i>
                        </div> <span class="mt-2 ml-2">Bank Balances</span>
                    </h6>
                    <a href="{{ route('bank.create') }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-plus-circle"></i> Add Bank
                    </a>
                </div>

                <div class="card-body">
                    @php

                    $banks = DB::table('banks')->select('id','bank_name','account_no','last_balance','opening_balance')->get();
                    $totalBalance = $banks->sum('last_balance');
                    @endphp

                    <div class="table-responsive">
                        <table class="table align-middle table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Bank</th>
                                    <th>Account No.</th>
                                    <th class="text-end">Opening Balance</th>
                                    <!--<th class="text-end">Last Balance</th>-->
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($banks as $bank)
                                <tr>
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
                                    <!--<td class="text-end fw-bold {{ $bank->last_balance < 0 ? 'text-danger' : 'text-danger' }}">-->
                                    <!--    {{ format_currency($bank->last_balance) }}-->
                                    <!--</td>-->
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">No banks available</td>
                                </tr>
                                @endforelse
                            </tbody>

                        </table>
                    </div>
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
document.addEventListener("DOMContentLoaded", function () {
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
            plugins: { legend: { display: false }, tooltip: { callbacks: { label: function(ctx){ return "TK " + ctx.raw; } } } },
            scales: { x: { beginAtZero: true }, y: { ticks: { font: { size: 14 } } } }
        }
    });
});
</script>

    @can('show_weekly_sales_purchases|show_month_overview')
    <div class="row mb-4">
        @can('show_weekly_sales_purchases')
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header">
                    Sales & Purchases of Last 7 Days
                </div>
                <div class="card-body">
                    <canvas id="salesPurchasesChart"></canvas>
                </div>
            </div>
        </div>
        @endcan
        @can('show_month_overview')
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header">
                    Overview of {{ now()->format('F, Y') }}
                </div>
                <div class="card-body d-flex justify-content-center">
                    <div class="chart-container" style="position: relative; height:auto; width:280px">
                        <canvas id="currentMonthChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        @endcan
    </div>
    @endcan

    @can('show_monthly_cashflow')
    <div class="row">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header">
                    Monthly Cash Flow (Payment Sent & Received)
                </div>
                <div class="card-body">
                    <canvas id="paymentChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    @endcan
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
</style>
