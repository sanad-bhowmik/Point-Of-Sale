@extends('layouts.app')

@section('title', 'TAIFA TRADERSE Report')

@section('breadcrumb')
<ol class="breadcrumb border-0 m-0">
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
    <li class="breadcrumb-item active">TAIFA TRADERSE Report</li>
</ol>
@endsection

@section('content')
<div class="container-fluid mt-5">
    <div class="card shadow-lg border-0">
        <div class="card-header bg-gradient-danger text-white text-center py-4" style="background: linear-gradient(90deg, #c31432, #240b36);">
            <h2 class="fw-bold mb-0 text-uppercase letter-spacing-1">TAIFA TRADERSE</h2>
            <p class="mb-0 small">Comprehensive Investment Report</p>
        </div>

        <div class="card-body bg-light">
            <table class="table table-hover table-bordered align-middle text-center shadow-sm bg-white rounded">
                <thead class="table-dark">
                    <tr>
                        <th>Category</th>
                        <th>Amount (৳)</th>
                    </tr>
                </thead>
                <tbody class="fw-semibold">
                    <tr>
                        <td class="text-start">TOTAL STORAGER</td>
                        <td>{{ number_format($totalStorager, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="text-start">MUSTAQ MAMA</td>
                        <td>{{ number_format(9014491.00, 2) }}</td>
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
                        <td>{{ number_format(2563269.20, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="text-start">PAYMENT GET</td>
                        <td>{{ number_format(2120569.20, 2) }}</td>
                    </tr>
                    <tr class="table-success">
                        <td class="text-start">TOTAL PROFIT</td>
                        <td>{{ number_format($totalProfit, 2) }}</td>
                    </tr>
                </tbody>
            </table>

        </div>
    </div>
</div>

<style>
    .letter-spacing-1 {
        letter-spacing: 1px;
    }

    .table th,
    .table td {
        vertical-align: middle !important;
    }

    .table-hover tbody tr:hover {
        background-color: #f5f5f5;
        transition: 0.3s ease-in-out;
    }
</style>
@endsection
