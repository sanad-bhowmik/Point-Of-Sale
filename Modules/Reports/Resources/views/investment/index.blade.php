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
    <div class="card shadow-lg border-0">
        <div class="card-header bg-danger text-white text-center">
            <h3 class="mb-0 fw-bold">TAIFA TRADERSE</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered text-center align-middle">
                <tbody>
                    <tr>
                        <th>TOTAL STORAGER</th>
                        <td>{{ number_format($totalStorager, 2) }}</td>
                    </tr>
                    <tr>
                        <th>MUSTAQ MAMA</th>
                        <td>9,014,491.00</td>
                    </tr>
                    <tr>
                        <th>UPCOMMING</th>
                        <td>21,979,969.53</td>
                    </tr>
                    <tr>
                        <th>TOTAL MARKET DUE</th>
                        <td>157,100.00</td>
                    </tr>
                    <tr>
                        <th>TOTAL LOSS</th>
                        <td>13,101,152.49</td>
                    </tr>
                    <tr>
                        <th>BANK AMMOUNT</th>
                        <td>2,563,269.20</td>
                    </tr>
                    <tr>
                        <th>PAYMENT GET</th>
                        <td>2,120,569.20</td>
                    </tr>
                    <tr>
                        <th>TOTAL PROFIT</th>
                        <td>1,810,649.01</td>
                    </tr>
                </tbody>
            </table>

            <div class="bg-danger text-white text-center p-3 mt-4 rounded">
                <h5 class="fw-bold mb-2">STORAGE + MUSTAQ MAMA + UPCOMMING + DUE + LOSS + BANK - PROFIT</h5>
                <h2 class="fw-bold mb-0">78,640,463.89</h2>
            </div>
        </div>
    </div>
</div>
@endsection
