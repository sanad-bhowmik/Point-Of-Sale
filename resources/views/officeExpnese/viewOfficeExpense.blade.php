@extends('layouts.app')

@section('title', 'Office Expenses')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item active">Office Expenses</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid mb-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">Office Expense List</h5>
                            <div>
                                <button id="downloadExcelRaw" class="btn btn-success btn-sm me-2">Download Excel</button>
                                <a href="{{ route('office_expense.create') }}" class="btn btn-primary btn-sm">
                                    + Add Expense
                                </a>
                            </div>
                        </div>

                        <form method="GET" action="{{ route('office_expense.view') }}" class="mb-4">
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
                                    <a href="{{ route('office_expense.view') }}" class="btn btn-secondary">Reset</a>
                                </div>
                            </div>
                        </form>

                        <table id="officeExpenseTable" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Category</th>
                                    <th>Employee Name</th>
                                    <th>Note</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                    <th>Amount</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($expenses as $index => $expense)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ Carbon\Carbon::parse($expense->date)->format('d-m-Y') }}</td>
                                        <td>{{ $expense->category->category_name ?? 'N/A' }}</td>
                                        <td>{{ $expense->employee_name ?? 'N/A' }}</td>
                                        <td>{{ $expense->note ?? 'N/A' }}</td>
                                        <td>{{ $expense->quantity > 0 ? $expense->quantity : 0 }}</td>
                                        <td>
                                            @if ($expense->quantity > 0)
                                                {{ number_format($expense->amount / $expense->quantity, 2) }} 
                                            @endif
                                        </td>
                                        <td>{{ number_format($expense->amount, 2) }}</td>
                                        <td>
                                            <a href="{{ route('office_expense.edit', $expense->id) }}"
                                                class="btn btn-sm btn-warning">
                                                Edit
                                            </a>
                                            <form action="{{ route('office_expense.destroy', $expense->id) }}"
                                                method="POST" class="d-inline"
                                                onsubmit="return confirm('Are you sure you want to delete this expense?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">No office expenses found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS (for modal) -->
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
    </script>
@endsection

@push('page_scripts')
    <script>
        // ...existing code...
        document.getElementById("downloadExcelRaw").addEventListener("click", function() {
            let table = document.getElementById("officeExpenseTable");
            if (!table) {
                alert("Table not found!");
                return;
            }

            let clone = table.cloneNode(true);

            // Remove from thead
            if (clone.tHead && clone.tHead.rows[0].cells.length > 0) {
                clone.tHead.rows[0].deleteCell(-1);
            }
            // Remove from tbody
            for (let row of clone.tBodies[0].rows) {
                if (row.cells.length > 0) {
                    row.deleteCell(-1);
                }
            }

            // Add heading row
            let heading = `<tr>
        <th colspan="${clone.tHead.rows[0].cells.length}" style="font-size:22px;text-align:center;padding:15px;">
            Office Expense List
        </th>
    </tr>`;

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

            // Insert heading before thead
            let tableHTML = style +
                `<table>${heading}${clone.tHead.outerHTML}${clone.tBodies[0].outerHTML}</table>`;

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
        // ...existing code...
    </script>
@endpush
