@extends('layouts.app')

@section('title', 'Office Expense Ledger')

@section('content')
    <div class="container-fluid mb-4">
        <div class="row">
            <div class="col-md-12">

                <div class="card">
                    <div class="card-body">
                        <!-- Date filter form -->
                        <form method="GET" action="{{ route('office_expense.ledger') }}">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group mb-0">
                                        <label for="category_id">Category</label>
                                        <select name="category_id" class="form-control">
                                            <option value="">All Categories</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->category_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group mb-0">
                                        <label for="from_date">From Date</label>
                                        <input type="date" name="from_date" value="{{ request('from_date') }}"
                                            class="form-control">

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group mb-0">
                                        <label for="to_date">To Date</label>
                                        <input type="date" name="to_date" value="{{ request('to_date') }}"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3 mt-4">
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                    <a href="{{ route('office_expense.ledger') }}" class="btn btn-secondary">Reset</a>
                                    <button id="downloadExcelRaw" class="btn btn-success">Download Excel</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-body">
                        <table id="officeExpenseTable" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Receive Date</th>
                                    <th>Receive Description</th>
                                    <th>Total Received</th>
                                    <th>Payment Date</th>
                                    <th>Expense Category</th>
                                    <th>Payment Description</th>
                                    <th>Payment Amount</th>
                                    <th>Cash In Hand</th>
                                    <th>Cash In Hand Line Wise</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($expenses as $expense)
                                    <tr>
                                        <td>{{ $expense->status === 'in' ? Carbon\Carbon::parse($expense->date)->format('d-m-Y') : '' }}
                                        </td>
                                        <td>{{ $expense->status === 'in' ? $expense->note : '' }}</td>
                                        <td>{{ $expense->status === 'in' ? number_format($expense->amount, 2) : '' }}</td>
                                        <td>{{ $expense->status === 'out' ? Carbon\Carbon::parse($expense->date)->format('d-m-Y') : '' }}
                                        </td>
                                        <td>{{ $expense->category->category_name }}
                                        </td>
                                        <td>{{ $expense->status === 'out' ? $expense->note : '' }}</td>
                                        <td>{{ $expense->status === 'out' ? number_format($expense->amount, 2) : '' }}</td>
                                        <td>{{ $expense->status === 'out' ? '-' . number_format($expense->amount, 2) : number_format($expense->amount, 2) }}
                                        </td>
                                        <td>{{ number_format($expense->cash_in_hand_line, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-muted">No office expenses found.</td>
                                    </tr>
                                @endforelse
                                <tr>
                                    <td colspan="2">Total Cash In Hand</td>
                                    <td>{{ number_format($totalIn, 2) }}</td>
                                    <td colspan="3"></td>
                                    <td>{{ number_format($totalOut, 2) }}</td>
                                    <td>{{ number_format($cashInHand, 2) }}</td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page_scripts')
    <script>
        document.getElementById("downloadExcelRaw").addEventListener("click", function() {
            let table = document.getElementById("officeExpenseTable");
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
