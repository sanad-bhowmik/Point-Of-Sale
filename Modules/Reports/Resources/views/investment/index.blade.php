@extends('layouts.app')

@section('title', 'TAIFA TRADERSE Report')

@section('breadcrumb')
<ol class="breadcrumb border-0 m-0">
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
    <li class="breadcrumb-item active">TAIFA TRADERSE Report</li>
</ol>
@endsection

@section('content')
<div class="container-fluid mt-4">
    <div class="d-flex flex-wrap gap-4">
        <!-- Left Table -->
        <div class="flex-fill">
            <h2 class="mb-3 text-center fw-bold">Taifa Traderse</h2>
            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <table class="table table-hover table-bordered align-middle text-center mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>Category</th>
                                <th>Amount (৳)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            // Calculate all relevant values
                            $leftValues = [
                                $totalStorager,
                                $totalInvestment,
                                $calculateUpcoming,
                                $totalDueAmount,
                                $totalLose,
                                $totalOpeningBalance,
                                $totalInvestmentAmount,
                                $cashInOutsite, // add cash in
                            ];

                            // Subtract total profit and cash withdraw
                            $leftTotal = array_sum($leftValues) - $totalProfit - $cashWithdraw;

                            // Add office expense to get subtotal
                            $subTotal = $leftTotal + $officeExpense;
                            @endphp

                            <tr>
                                <td class="text-start">Total Storager</td>
                                <td>{{ number_format($totalStorager, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="text-start">Mustaq Mama</td>
                                <td>{{ number_format($totalInvestment, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="text-start">Upcoming</td>
                                <td>{{ number_format($calculateUpcoming, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="text-start">Total Market Due</td>
                                <td>{{ number_format($totalDueAmount, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="text-start">Total Loss</td>
                                <td>{{ number_format($totalLose, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="text-start">Bank Amount</td>
                                <td>{{ number_format($totalOpeningBalance, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="text-start">Payment Get</td>
                                <td>{{ number_format($totalInvestmentAmount, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="text-start">Cash in From out site</td>
                                <td>{{ number_format($cashInOutsite, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="text-start">Cash Withdraw</td>
                                <td>{{ number_format($cashWithdraw, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="text-start">Total Profit</td>
                                <td>{{ number_format($totalProfit, 2) }}</td>
                            </tr>

                            <!-- Total Row -->
                            <tr class="fw-bold fs-5">
                                <td class="text-end">Total</td>
                                <td>{{ number_format($leftTotal, 2) }}</td>
                            </tr>

                            <!-- Office Expense -->
                            <tr>
                                <td class="text-start">Office Expense</td>
                                <td>{{ number_format($officeExpense, 2) }}</td>
                            </tr>

                            <!-- Sub Total Row -->
                            <tr class="fw-bold fs-5 table-info">
                                <td class="text-end">Sub Total</td>
                                <td>{{ number_format($subTotal, 2) }}</td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right Table -->
        <div class="flex-fill">
            <h2 class="mb-3 text-center fw-bold">TAIFA TRADERSE - TOTAL ASSET</h2>
            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <table class="table table-hover table-bordered align-middle text-center mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>Category</th>
                                <th>Amount (৳)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            use Illuminate\Support\Facades\DB;

                            // Step 1: Get sum of parties_payment.amount and damage_amount directly
                            $partiesPaymentAmount = DB::table('parties_payment')->sum('amount');
                            $damageAmount = DB::table('parties_payment')->sum('damarage_amount');

                            // Step 2: Apply your formula
                            $v1 = $partiesPaymentAmount + $damageAmount;
                            $v2 = $v1 + 311436; // add fixed value
                            $totalGet = $v2 - $totalInvestmentAmount;

                            // Step 3: Other existing values (including new leftTotal)
                            $totalInvestmentValue = $leftTotal;
                            $totalLossValue = $totalLose;

                            // Step 4: Calculate Total Value (reflects updated leftTotal)
                            $totalValue = $totalInvestmentValue + $totalGet - $totalLossValue;
                            @endphp

                            <tr>
                                <td class="text-start">Total Investment</td>
                                <td>{{ number_format($totalInvestmentValue, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="text-start">Total Get</td>
                                <td>{{ number_format($totalGet, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="text-start">Total Loss</td>
                                <td>{{ number_format($totalLossValue, 2) }}</td>
                            </tr>
                            <tr class="fw-bold fs-5 table-success">
                                <td class="text-end">Total Value</td>
                                <td>{{ number_format($totalValue, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
    .table th,
    .table td {
        vertical-align: middle !important;
        padding: 10px 8px;
        text-transform: capitalize;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.05);
        transition: 0.3s ease-in-out;
    }

    .table-dark {
        background: linear-gradient(135deg, #2c3e50, #34495e) !important;
        color: #fff;
    }

    .table-success {
        background-color: rgba(40, 167, 69, 0.15) !important;
    }

    .d-flex {
        gap: 1rem;
        flex-wrap: wrap;
    }

    .flex-fill {
        flex: 1 1 48%;
    }

    h2 {
        font-size: 2rem;
    }
</style>
@endsection
