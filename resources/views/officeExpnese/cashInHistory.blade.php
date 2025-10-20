@extends('layouts.app')

@section('title', 'Cash In History')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item active">Cash In History</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid mb-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">Cash In History</h5>
                            <div>
                                <button id="downloadExcelRaw" class="btn btn-success btn-sm me-2">Download Excel</button>
                                <a href="{{ route('office_expense.create', ['page' => 'cashInHistory']) }}" class="btn btn-primary btn-sm">
                                    + Add Cash
                                </a>
                            </div>
                        </div>

                        <form method="GET" action="{{ route('office_expense.history') }}" class="mb-4">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group mb-0">
                                        <label for="from_date">From Date</label>
                                        <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group mb-0">
                                        <label for="to_date">To Date</label>
                                        <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3 mt-4">
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                    <a href="{{ route('office_expense.history') }}" class="btn btn-secondary">Reset</a>
                                </div>
                            </div>
                        </form>

                        <table id="officeExpenseTable" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Expense Category</th>
                                    <th>Amount</th>
                                    <th>Note</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($expenses as $index => $expense)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ Carbon\Carbon::parse($expense->date)->format('d-m-Y') }}</td>
                                        <td>{{ $expense->category->category_name ?? 'N/A' }}</td>
                                        <td>{{ number_format($expense->amount, 2) }}</td>
                                        <td>{{ $expense->note ?? 'N/A' }}</td>
                                        <td>
                                            <a href="{{ route('office_expense.edit', ['id' =>$expense->id, 'page' => 'cashInHistory']) }}" class="btn btn-sm btn-warning">
                                                Edit
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">No office expenses found.</td>
                                    </tr>
                                @endforelse
                            </tbody>

                            <!-- ✅ Total Amount Row -->
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-end">Total Amount:</th>
                                    <th id="totalAmount">0.00</th>
                                    <th colspan="2"></th>
                                </tr>
                            </tfoot>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

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

        @if (session('success'))
            toastr.success("{{ session('success') }}");
        @endif

        @if (session('error'))
            toastr.error("{{ session('error') }}");
        @endif

        @if (session('warning'))
            toastr.warning("{{ session('warning') }}");
        @endif

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                toastr.error("{{ $error }}");
            @endforeach
        @endif

        // ✅ Calculate Total Amount
        document.addEventListener("DOMContentLoaded", function() {
            const rows = document.querySelectorAll("#officeExpenseTable tbody tr");
            let total = 0;

            rows.forEach(row => {
                const amountCell = row.cells[3]; // 4th column (index 3)
                if (amountCell && !isNaN(parseFloat(amountCell.textContent.replace(/,/g, '')))) {
                    total += parseFloat(amountCell.textContent.replace(/,/g, ''));
                }
            });

            document.getElementById("totalAmount").textContent =
                total.toLocaleString(undefined, { minimumFractionDigits: 2 });
        });
    </script>
@endsection

@push('page_scripts')
    <script>
        document.getElementById("downloadExcelRaw").addEventListener("click", function() {
            let table = document.getElementById("officeExpenseTable");
            if (!table) {
                alert("Table not found!");
                return;
            }

            let clone = table.cloneNode(true);

            // Remove "Actions" column from header and rows
            if (clone.tHead && clone.tHead.rows[0].cells.length > 0) {
                clone.tHead.rows[0].deleteCell(-1);
            }
            for (let row of clone.tBodies[0].rows) {
                if (row.cells.length > 0) {
                    row.deleteCell(-1);
                }
            }

            // Excel styling
            let style = `
            <style>
                * { font-family: Roboto, Arial, sans-serif; }
                table, th, td { border: 1px solid #000; border-collapse: collapse; text-align: center; }
                th, td { padding: 10px; height: 35px; vertical-align: middle; }
                th { font-weight: bold; }
            </style>
            `;

            let tableHTML = style + clone.outerHTML;

            let blob = new Blob(['\ufeff' + tableHTML], {
                type: "application/vnd.ms-excel"
            });

            let url = URL.createObjectURL(blob);
            let a = document.createElement("a");
            a.href = url;
            a.download = "cash_in_history.xls";
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        });
    </script>
@endpush

<style>
    tfoot th {
        background-color: #f8f9fa;
        font-weight: bold;
        font-size: 1rem;
    }
</style>
