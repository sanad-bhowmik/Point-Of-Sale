@extends('layouts.app')

@section('title', content: 'Expense Ledger')

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
                                    {{-- <div class="col-md-4 mb-3">
                                        <label for="category_id" class="form-label">Select Category</label>
                                        <select name="category_id" id="category_id" class="form-control select2">
                                            <option value="">-- Select One --</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">
                                                    {{ $category->category_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div> --}}

                                    <div class="col-md-6 mb-3">
                                        <label for="date_range" class="form-label">Select Date Range</label>
                                        <div class="input-group">
                                            <input type="text" name="date_range" id="date_range" class="form-control"
                                                placeholder="Select date range"
                                                value="{{ old('date_range', request('date_range')) }}">
                                            <span class="input-group-text"><i class="bi bi-calendar-event"></i></span>
                                        </div>
                                    </div>

                                    <div class="col-md-4 mb-3 d-flex">
                                        <button type="submit" class="btn btn-primary mr-2">Filter</button>
                                        <a href="{{ route('expense.expenseLedger') }}" class="btn btn-secondary">Reset</a>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-3 d-flex align-items-center">
                                <button id="downloadExcelBtn" class="btn btn-success">Download Excel</button>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table id="reportTable" class="table table-bordered table-hover">
                                <tbody>
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
                                            <td colspan="7" class="text-center">No Transactions found.</td>
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
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
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
                $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format(
                    'YYYY-MM-DD'));
            });

            dateInput.on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });
        });
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script>
        document.getElementById("downloadExcelBtn").addEventListener("click", function() {
            let table = document.getElementById("reportTable");
            if (!table) {
                alert("Table not found!");
                return;
            }

            // Excel styling
            let style = `
        <style>
            * {
                font-family: Roboto, Arial, sans-serif;
            }
            table, th, td {
                border: 1px solid #000;
                border-collapse: collapse;
                text-align: center;
            }
            th, td {
                padding: 10px;
                height: 35px; /* row height */
                vertical-align: middle;
            }
            th {
                font-weight: bold;
            }
        </style>
    `;

            let tableHTML = style + table.outerHTML;

            let blob = new Blob(
                ['\ufeff' + tableHTML], {
                    type: "application/vnd.ms-excel"
                }
            );

            let url = URL.createObjectURL(blob);
            let a = document.createElement("a");
            a.href = url;
            a.download = "expense-ledger.xls";
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        });
    </script>
@endpush

@push('page_css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endpush
