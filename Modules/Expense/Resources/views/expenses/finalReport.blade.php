@extends('layouts.app')

@section('title', 'Final Report')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item active">Final Report</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid mb-4">
        <div class="row">
            <div class="col-md-12">
                {{-- Filter Section --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header" style="background-color: #e3ff4a">
                        <h5 class="mb-0">Filter Final Report</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('expense.finalReportFilter') }}" method="post" class="row g-3">
                            @csrf
                            {{-- LC Dropdown --}}
                            <div class="col-md-4">
                                <label for="lc_id" class="form-label">Select LC</label>
                                <select name="lc_id" id="lc_id" class="form-control select2">
                                    <option value="">-- All LC --</option>
                                    @foreach ($lcs as $lc)
                                        <option value="{{ $lc->id }}"
                                            {{ old('lc_id', $find_lc?->id) == $lc->id ? 'selected' : '' }}>
                                            {{ $lc->lc_name }}--({{ $lc->lc_number }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Container Dropdown --}}
                            <div class="col-md-4">
                                <label for="container_id" class="form-label">Select Container</label>
                                <select name="container_id" id="container_id" class="form-control select2">
                                    <option value="">-- All Container --</option>
                                    @foreach ($containers as $container)
                                        <option value="{{ $container->id }}"
                                            {{ old('container_id', $find_container?->id) == $container->id ? 'selected' : '' }}>
                                            {{ $container->name }}--({{ $container->number }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Filter Button --}}
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-filter"></i> Filter
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Report Content --}}
                @if (isset($find_lc) && isset($find_container) && isset($costing))
                    {{-- Report Section --}}
                    <div class="row">
                        <div class="col-md-3"></div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <table class="table table-bordered table-striped text-center">
                                        <tbody>
                                            <tr>
                                                <td class="td-font">Taifa Traders</td>
                                            </tr>
                                            <tr>
                                                <td class="" id="container_name">{{ $find_container->name }}</td>
                                            </tr>
                                            <tr>
                                                <td class="td-font">FINAL REPORT</td>
                                            </tr>
                                            <tr>
                                                <td class="" id="lc_name">LC NO : {{ $find_lc->lc_number }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3"></div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="text-center mb-4">Price of Grapes</h4>

                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Price (USD)</th>
                                                    <th>Rate (TK)</th>
                                                    <th>Amount</th>
                                                    <th>Qty (Box)</th>
                                                    <th>Total Amount (TK)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if ($costing)
                                                    <tr>
                                                        <td>{{ $costing->product->product_name ? 'Price of ' . $costing->product->product_name . ' (LC)' : 'N/A' }}
                                                        </td>
                                                        <td>{{ $costing->base_value }}</td>
                                                        <td>{{ $costing->exchange_rate }}</td>
                                                        <td>{{ $costing->base_value * $costing->exchange_rate }}</td>
                                                        <td>{{ round($costing->qty) }}</td>
                                                        <td>{{ $costing->total_tk }}</td>
                                                    </tr>
                                                @else
                                                    <tr>
                                                        <td colspan="6" class="text-center text-muted">No costing record
                                                            found for this LC</td>
                                                    </tr>
                                                @endif
                                                @if ($container)
                                                    <tr>
                                                        <td>{{ $container->lc?->costing?->product?->product_name ? 'Price of ' . $costing->product->product_name . ' (TT)' : 'N/A' }}
                                                        </td>
                                                        <td>{{ $container->tt_value }}</td>
                                                        <td>{{ number_format($container->tt_exchange_rate, 2) }}</td>
                                                        <td>{{ number_format($container->tt_value * $container->tt_exchange_rate, 4) }}
                                                        </td>
                                                        <td>{{ $container->qty }}</td>
                                                        <td>{{ round($container->tt_value * $container->tt_exchange_rate * $container->qty, 2) }}
                                                        </td>
                                                    </tr>
                                                @else
                                                    <tr>
                                                        <td colspan="6" class="text-center text-muted">No costing record
                                                            found for this LC</td>
                                                    </tr>
                                                @endif
                                                <tr>
                                                    <td>Price of Grapes (Total)</td>
                                                    <td>{{ number_format($costing->base_value + $container->tt_value, 1) }}
                                                    </td>
                                                    <td colspan="3">Total price of Grapess</td>
                                                    <td>
                                                        {{ $costing && $container ? round($costing->total_tk + $container->tt_value * $container->tt_exchange_rate * $container->qty, 2) : '0' }}
                                                    </td>

                                                </tr>
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if (isset($expenseGroup))
                    <div class="row">
                        @foreach ($expenseGroup as $category_name => $group_category)
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header" style="background-color: #e3ff4a">
                                        <h4 class="text-center">{{ $category_name }}</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center">Discription</th>
                                                        <th class="text-center" style="width: 120px">Cost (TK)</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $totalCategoryAmount = 0;
                                                    @endphp
                                                    @foreach ($group_category as $expense)
                                                        <tr>
                                                            <td>{{ $expense->expenseName->expense_name ?? 'N/A' }}</td>
                                                            <td class="text-center">{{ $expense->amount }}</td>
                                                        </tr>
                                                        @php
                                                            $totalCategoryAmount += $expense->amount;
                                                        @endphp
                                                    @endforeach
                                                    <tr style="background-color: #b7ffcd;">
                                                        <td>Total {{ $category_name ?? 'N/A' }}</td>
                                                        <td class="text-center">{{ $totalCategoryAmount }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="row">
                        <div class="col-md-3"></div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header" style="background-color: #e3ff4a">
                                    <h4 class="text-center">Price of {{ $costing?->product?->product_name }}</h4>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered table-striped text-center">
                                        <thead>
                                            <tr>
                                                <th>Discription</th>
                                                <th>Cost (TK)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($expenseGroup as $category_name => $group_category)
                                                @php
                                                    $totalCategoryAmount = 0;
                                                @endphp
                                                @foreach ($group_category as $expense)
                                                    @php
                                                        $totalCategoryAmount += $expense->amount;
                                                    @endphp
                                                @endforeach
                                                <tr>
                                                    <td>Total {{ $category_name ?? 'N/A' }}</td>
                                                    <td class="text-center">{{ $totalCategoryAmount }}</td>
                                                </tr>
                                            @endforeach
                                            <tr style="background-color: #fffeb7;">
                                                <td>Total cost</td>
                                                <td class="text-center">
                                                    {{ $expenseGroup->flatten()->sum('amount') }}
                                                </td>
                                            </tr>
                                            <tr style="background-color: #fffeb7;">
                                                <td>Total price of Grapes</td>
                                                <td class="text-center">
                                                    {{ $costing && $container ? round($costing->total_tk + $container->tt_value * $container->tt_exchange_rate * $container->qty, 2) : '0' }}
                                                </td>
                                            </tr>
                                            <tr style="background-color: #b7ffcd;">
                                                <td>Value of Grapes after costs</td>
                                                <td class="text-center">
                                                    @php
                                                        $totalExpenses = $expenseGroup->flatten()->sum('amount');
                                                        $priceOfGrapes =
                                                            $costing && $container
                                                                ? round(
                                                                    $costing->total_tk +
                                                                        $container->tt_value *
                                                                            $container->tt_exchange_rate *
                                                                            $container->qty,
                                                                    2,
                                                                )
                                                                : 0;
                                                        $valueAfterCosts = $priceOfGrapes + $totalExpenses;
                                                    @endphp
                                                    {{ $valueAfterCosts }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3"></div>
                    </div>

                    <div class="row">
                        <div class="col-md-3"></div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header" style="background-color: #e3ff4a">
                                    <h4 class="text-center">
                                        Final Calculation of {{ $costing?->product?->product_name }}
                                    </h4>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered table-striped text-center">
                                        <thead>
                                            <tr>
                                                <th>Discription</th>
                                                <th>Amount (TK)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Total Sales</td>
                                                <td>{{ $totalSale }}</td>
                                            </tr>
                                            <tr>
                                                <td>Value of Grapes after costs</td>
                                                <td>{{ $valueAfterCosts }}</td>
                                            </tr>
                                            <tr style="background-color: #65ffaa;">
                                                <td>Profit or Loss</td>
                                                <td>{{ $totalSale - $valueAfterCosts }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3"></div>
                    </div>

                    <div class="row">
                        <div class="col-md-3"></div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header" style="background-color: #e3ff4a">
                                    <h4 class="text-center">
                                        Profit Margin Ratio of {{ $costing?->product?->product_name }}
                                    </h4>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered table-striped text-center">
                                        <thead>
                                            <tr>
                                                <th>Discription</th>
                                                <th>Ratio (%)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Profit Margin</td>
                                                <td>
                                                    @if ($totalSale > 0)
                                                        {{ number_format(( ($totalSale - $valueAfterCosts) / $valueAfterCosts) * 100, 2)  }}
                                                        %
                                                    @else
                                                        0 %
                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3"></div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('page_css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        td {
            font-size: 16px;
            font-weight: 600;
        }

        .td-font {
            font-size: 18px;
            font-weight: 600;
            padding: 4px !important;
        }
    </style>
@endpush

@push('page_scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.select2').select2({
                width: '100%',
                placeholder: 'Select an option'
            });
        });
    </script>
@endpush
