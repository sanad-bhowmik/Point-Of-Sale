@extends('layouts.app')

@section('title', 'Expense Ledger')

@section('third_party_stylesheets')
{{-- Select2 CSS --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css" rel="stylesheet" />
@endsection

@section('breadcrumb')
<ol class="breadcrumb border-0 m-0">
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
    <li class="breadcrumb-item active">Expense Ledger</li>
</ol>
@endsection

@section('content')
<div class="container-fluid mb-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm rounded-3">
                <div class="card-body">
                    <h5 class="mb-3 border-bottom pb-2">Expense Ledger</h5>

                    <div class="row">
                        <div class="col-md-9">
                            <form action="{{ route('expense.expenseLedger') }}" method="GET"
                                class="row mb-3 align-items-end" enctype="multipart/form-data">

                                {{-- LC Dropdown --}}
                                <div class="col-md-4 mb-3">
                                    <label for="lc_id" class="form-label">Select LC</label>
                                    <select name="lc_id" id="lc_id" class="form-control select2">
                                        <option value="">-- All LC --</option>
                                        @foreach (\App\Models\Lc::all() as $lc)
                                        <option value="{{ $lc->id }}" {{ request('lc_id') == $lc->id ? 'selected' : '' }}>
                                            {{ $lc->lc_number }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Date Range --}}
                                <div class="col-md-5 mb-3">
                                    <label for="date_range" class="form-label">Select Date Range</label>
                                    <div class="input-group">
                                        <input type="text" name="date_range" id="date_range" class="form-control"
                                            placeholder="Select date range"
                                            value="{{ old('date_range', request('date_range')) }}">
                                        <span class="input-group-text"><i class="bi bi-calendar-event"></i></span>
                                    </div>
                                </div>

                                {{-- Filter Buttons --}}
                                <div class="col-md-3 mb-3 d-flex">
                                    <button type="submit" class="btn btn-primary mr-2">Filter</button>
                                    <a href="{{ route('expense.expenseLedger') }}" class="btn btn-secondary">Reset</a>
                                </div>
                            </form>
                        </div>

                        {{-- Excel Download Button --}}
                        <div class="col-md-3 d-flex align-items-center">
                            <button id="downloadExcelBtn" class="btn btn-success">Download Excel</button>
                        </div>
                    </div>

                    {{-- Expense Ledger Table --}}
                    <div class="table-responsive">
                        <table id="reportTable" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>LC Number</th>
                                    <th>Container Number</th>
                                    <th>Category</th>
                                    <th>Expense Name</th>
                                    <th>Amount</th>
                                    <th>Current Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $runningTotal = $openingBalance;
                                @endphp
                                @if ($openingBalance > 0)
                                <tr>
                                    <td colspan="7" class="text-end"><strong>Opening Balance</strong></td>
                                    <td><strong>{{ number_format($openingBalance, 2) }}</strong></td>
                                </tr>
                                @endif
                                @forelse($expenses as $index => $expense)
                                @php
                                $runningTotal += $expense->amount;
                                @endphp
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ \Carbon\Carbon::parse($expense->date)->format('d-m-Y') }}</td>
                                    <td>{{ $expense?->lc?->lc_number ?? '-' }}</td>
                                    <td>{{ $expense?->container?->number ?? '-' }}</td>
                                    <td>{{ $expense?->category?->category_name ?? '-' }}</td>
                                    <td>{{ $expense?->expenseName?->expense_name ?? '-' }}</td>
                                    <td>{{ number_format($expense->amount, 2) }}</td>
                                    <td>{{ number_format($runningTotal, 2) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">No Transactions found.</td>
                                </tr>
                                @endforelse
                                <tr>
                                    <td colspan="7" class="text-end"><strong>Total Expense</strong></td>
                                    <td><strong>{{ number_format($ledgerTotal, 2) }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('page_scripts')
{{-- Select2 JS --}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

{{-- Date Range Picker --}}
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

{{-- Excel Export --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Enable Select2
        $('#lc_id').select2({
            theme: 'bootstrap4',
            placeholder: "-- All LC --",
            allowClear: true
        });

        // Initialize Date Range Picker
        let dateInput = $('#date_range');
        let existingValue = dateInput.val();

        dateInput.daterangepicker({
            autoUpdateInput: true,
            locale: {
                format: 'YYYY-MM-DD',
                cancelLabel: 'Clear'
            },
            startDate: existingValue ? existingValue.split(' - ')[0] : moment(),
            endDate: existingValue ? existingValue.split(' - ')[1] : moment()
        });

        dateInput.on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
        });

        dateInput.on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });

        // Excel Export
        document.getElementById("downloadExcelBtn").addEventListener("click", function() {
            let table = document.getElementById("reportTable");
            if (!table) {
                alert("Table not found!");
                return;
            }

            let style = `
                    <style>
                        * { font-family: Roboto, Arial, sans-serif; }
                        table, th, td { border: 1px solid #000; border-collapse: collapse; text-align: center; }
                        th, td { padding: 10px; height: 35px; vertical-align: middle; }
                        th { font-weight: bold; }
                    </style>
                `;
            let tableHTML = style + table.outerHTML;

            let blob = new Blob(['\ufeff' + tableHTML], {
                type: "application/vnd.ms-excel"
            });
            let url = URL.createObjectURL(blob);
            let a = document.createElement("a");
            a.href = url;
            a.download = "expense-ledger.xls";
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        });
    });
</script>
@endpush
@push('page_scripts')
<style>
    /* Fix Select2 border and height to look like Bootstrap input */
    .select2-container--bootstrap4 .select2-selection {
        border: 1px solid #ced4da !important;
        border-radius: 0.375rem !important;
        height: calc(2.25rem + 2px) !important;
        padding: 0.375rem 0.75rem !important;
    }

    .select2-container--bootstrap4 .select2-selection__rendered {
        line-height: 1.5 !important;
        padding-left: 0 !important;
    }

    .select2-container--bootstrap4 .select2-selection__arrow {
        height: calc(2.25rem + 2px) !important;
    }
</style>
@endpush
