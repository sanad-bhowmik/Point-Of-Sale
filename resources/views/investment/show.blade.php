@extends('layouts.app')

@section('title', 'Investment List')

@section('breadcrumb')
<ol class="breadcrumb border-0 m-0">
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
    <li class="breadcrumb-item active">Investment List</li>
</ol>
@endsection

@section('content')
<div class="container-fluid mb-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Investment Report</h3>
                    <a href="{{ route('investment.create') }}" class="btn btn-primary">+ Add New Investment</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th rowspan="2" class="text-center align-middle">SL</th>
                                    <th rowspan="2" class="text-center align-middle">Date</th>
                                    <th rowspan="2" class="text-center align-middle">LC Number</th>
                                    <th rowspan="2" class="text-center align-middle">Description</th>
                                    <th colspan="4" class="text-center">Investment</th>
                                    <th rowspan="2" class="text-center align-middle">Total-Receivable -Date wise</th>
                                </tr>
                                <tr>
                                    <th class="text-center">Invest</th>
                                    <th class="text-center">Expense</th>
                                    <th class="text-center">Profit</th>
                                    <th class="text-center">Cash Invest</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalInvest = 0;
                                    $totalExpense = 0;
                                    $totalProfit = 0;
                                    $totalCashInvest = 0;
                                    $grandTotal = 0;
                                    $serialNumber = 1;
                                    $runningReceivable = 0;

                                    // Group investments by LC number
                                    $groupedInvestments = [];
                                    foreach($investments as $investment) {
                                        $groupedInvestments[$investment->lc_number][] = $investment;
                                    }
                                @endphp

                                @foreach($groupedInvestments as $lcNumber => $lcInvestments)
                                    @php
                                        $rowCount = count($lcInvestments);
                                        $isFirstRow = true;
                                    @endphp

                                    @foreach($lcInvestments as $index => $investment)
                                        @php
                                            // Initialize all amounts to 0
                                            $investAmount = 0;
                                            $expenseAmount = 0;
                                            $profitAmount = 0;
                                            $cashInvestAmount = 0;
                                            $receivedAmount = 0;

                                            // Set the amount based on investment type
                                            switch($investment->investment) {
                                                case 'Invest':
                                                    $investAmount = $investment->amount;
                                                    $totalInvest += $investAmount;
                                                    break;
                                                case 'Expense':
                                                    $expenseAmount = $investment->amount;
                                                    $totalExpense += $expenseAmount;
                                                    break;
                                                case 'Profit':
                                                    $profitAmount = $investment->amount;
                                                    $totalProfit += $profitAmount;
                                                    break;
                                                case 'Cash Invest':
                                                    $cashInvestAmount = $investment->amount;
                                                    $totalCashInvest += $cashInvestAmount;
                                                    break;
                                            }

                                            // Calculate receivable for this row (F+G-H from Excel)
                                            $receivable = ($investAmount + $expenseAmount + $profitAmount + $cashInvestAmount) - $receivedAmount;

                                            // Running total (like Excel cumulative sum)
                                            $runningReceivable += $receivable;

                                            $grandTotal = $totalInvest + $totalExpense + $totalProfit + $totalCashInvest;
                                        @endphp

                                        <tr>
                                            <td class="text-center">{{ $serialNumber++ }}</td>
                                            <td>{{ \Carbon\Carbon::parse($investment->date)->format('d/m/Y') }}</td>
                                            <td>
                                                @if($isFirstRow)
                                                    {{ $lcNumber }}
                                                    @php $isFirstRow = false; @endphp
                                                @endif
                                            </td>
                                            <td>{{ $investment->description }}</td>

                                            {{-- Invest (Green Badge) --}}
                                            <td class="text-end">
                                                @if($investAmount > 0)
                                                    <span class="">
                                                        {{ number_format($investAmount, 2) }}
                                                    </span>
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            {{-- Expense (Red Badge with Minus Sign) --}}
                                            <td class="text-end">
                                                @if($expenseAmount > 0)
                                                    <span class="">
                                                        -{{ number_format($expenseAmount, 2) }}
                                                    </span>
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            {{-- Profit (Blue Badge) --}}
                                            <td class="text-end">
                                                @if($profitAmount > 0)
                                                    <span class="">
                                                        {{ number_format($profitAmount, 2) }}
                                                    </span>
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            {{-- Cash Invest (Primary Badge) --}}
                                            <td class="text-end">
                                                @if($cashInvestAmount > 0)
                                                    <span class="">
                                                        {{ number_format($cashInvestAmount, 2) }}
                                                    </span>
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            <td class="text-end fw-bold">{{ number_format($runningReceivable, 2) }}</td>
                                        </tr>
                                    @endforeach
                                @endforeach

                                @if($investments->isEmpty())
                                    <tr>
                                        <td colspan="9" class="text-center py-4">No investments found.</td>
                                    </tr>
                                @else
                                    <!-- Total Row -->
                                    <tr class="table-success fw-bold">
                                        <td colspan="4" class="text-end">TOTAL:</td>
                                        <td class="text-end">{{ number_format($totalInvest, 2) }}</td>
                                        <td class="text-end text-danger">-{{ number_format($totalExpense, 2) }}</td>
                                        <td class="text-end">{{ number_format($totalProfit, 2) }}</td>
                                        <td class="text-end">{{ number_format($totalCashInvest, 2) }}</td>
                                        <td class="text-end">{{ number_format($runningReceivable, 2) }}</td>
                                    </tr>

                                    <!-- Grand Total Row -->
                                    <tr class="table-primary fw-bold fs-5">
                                        <td colspan="4" class="text-end">GRAND TOTAL:</td>
                                        <td colspan="4" class="text-center">{{ number_format($grandTotal, 2) }}</td>
                                        <td class="text-end">{{ number_format($runningReceivable, 2) }}</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toastr CSS & JS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "timeOut": "5000"
    };

    @if(session('success'))
        toastr.success("{{ session('success') }}");
    @endif

    @if(session('error'))
        toastr.error("{{ session('error') }}");
    @endif

    @if($errors->any())
        @foreach($errors->all() as $error)
            toastr.error("{{ $error }}");
        @endforeach
    @endif
</script>

<style>
    .table th {
        font-weight: 600;
        font-size: 0.875rem;
    }
    .table td {
        vertical-align: middle;
    }
    .table-responsive {
        border-radius: 0.375rem;
    }
    .table td:nth-child(3) {
        font-weight: 600;
    }
    .badge {
        font-size: 0.85rem;
        padding: 0.4em 0.65em;
    }
</style>
@endsection
