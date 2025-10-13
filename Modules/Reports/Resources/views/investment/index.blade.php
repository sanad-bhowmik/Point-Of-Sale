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
                            <tr>
                                <td class="text-start">TOTAL STORAGER</td>
                                <td>{{ number_format($totalStorager, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="text-start">MUSTAQ MAMA</td>
                                <td>{{ number_format($totalInvestment, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="text-start">UPCOMING</td>
                                <td>{{ number_format($calculateUpcoming, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="text-start">TOTAL MARKET DUE</td>
                                <td>{{ number_format($totalDueAmount, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="text-start">TOTAL LOSS</td>
                                <td>{{ number_format($totalLose, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="text-start">BANK AMOUNT</td>
                                <td>{{ $totalOpeningBalance }}</td>
                            </tr>
                            <tr>
                                <td class="text-start">PAYMENT GET</td>
                                <td>{{ number_format($totalInvestmentAmount, 2) }}</td>
                            </tr>
                            <tr class="table-start">
                                <td class="text-start">TOTAL PROFIT</td>
                                <td>{{ number_format($totalProfit, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right Table -->
        <div class="flex-fill">
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
                            <tr>
                                <td class="text-start">TOTAL STORAGER</td>
                                <td>{{ number_format($totalStorager, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="text-start">MUSTAQ MAMA</td>
                                <td>{{ number_format($totalInvestment, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="text-start">UPCOMING</td>
                                <td>{{ number_format($calculateUpcoming, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="text-start">TOTAL MARKET DUE</td>
                                <td>{{ number_format($totalDueAmount, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="text-start">BANK AMOUNT</td>
                                <td>{{ $totalOpeningBalance }}</td>
                            </tr>
                            <tr>
                                <td class="text-start">PAYMENT GET</td>
                                <td>{{ number_format(2120569.20, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="text-start">PAYMENT GET - FAIR</td>
                                <td>311,436.00</td>
                            </tr>
                            <tr>
                                <td class="text-start">TOTAL PROFIT</td>
                                <td>{{ number_format($totalProfit, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="text-start">TOTAL LOSS</td>
                                <td>{{ number_format($totalLose, 2) }}</td>
                            </tr>
                            <tr class=" fw-bold fs-5">
                                <td class="text-start">TOTAL ASSETS</td>
                                <td>57,117,256.92</td>
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

    .table-info {
        background-color: rgba(23, 162, 184, 0.2) !important;
    }

    .d-flex {
        gap: 1rem;
        flex-wrap: wrap;
    }

    .flex-fill {
        flex: 1 1 48%;
    }
</style>
@endsection
