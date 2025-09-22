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
                        <a href="{{ route('office_expense.create') }}" class="btn btn-primary btn-sm">
                            + Add Expense
                        </a>
                    </div>

                    <!-- 🔎 Filter Form -->
                    <form method="GET" action="{{ route('office_expense.view') }}" class="row g-2 mb-3">
                        <div class="col-md-3">
                            <input type="text" name="employee_name" value="{{ request('employee_name') }}"
                                class="form-control" placeholder="Search Employee">
                        </div>
                        <div class="col-md-3">
                            <input type="date" name="from_date" value="{{ request('from_date') }}"
                                class="form-control">
                        </div>
                        <div class="col-md-3">
                            <input type="date" name="to_date" value="{{ request('to_date') }}"
                                class="form-control">
                        </div>
                        <div class="col-md-3 d-flex">
                            <button type="submit" class="btn btn-info me-2">Filter</button>
                            <a href="{{ route('office_expense.view') }}" class="btn btn-secondary">Reset</a>
                        </div>
                    </form>


                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Expense Category</th>
                                <th>Employee Name</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>Note</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($expenses as $index => $expense)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $expense->expense_category }}</td>
                                <td>{{ $expense->employee_name }}</td>
                                <td>{{ number_format($expense->amount, 2) }}</td>
                                <td>{{ \Carbon\Carbon::parse($expense->date)->format('d M Y') }}</td>
                                <td>{{ $expense->note ?? '-' }}</td>
                                <td>
                                    <!-- Edit Button -->
                                    <button class="btn btn-sm btn-info mb-1"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editExpenseModal{{ $expense->id }}"
                                        style="font-size: 14px;"
                                        title="Click to Edit">
                                        Edit
                                    </button>

                                    <!-- Delete Form -->
                                    <form action="{{ route('office_expense.destroy', $expense->id) }}"
                                        method="POST"
                                        style="display:inline-block;"
                                        onsubmit="return confirm('Are you sure you want to delete this expense?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger mb-1"
                                            style="font-size: 14px;"
                                            title="Click to Delete">
                                            Delete
                                        </button>
                                    </form>

                                    <!-- Edit Expense Modal -->
                                    <div class="modal fade" id="editExpenseModal{{ $expense->id }}" tabindex="-1" aria-labelledby="editExpenseLabel{{ $expense->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editExpenseLabel{{ $expense->id }}">Edit Expense</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="border:none; background:white;">✖</button>
                                                </div>
                                                <form action="{{ route('office_expense.update', $expense->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label>Expense Category</label>
                                                            <input type="text" class="form-control" name="expense_category" value="{{ $expense->expense_category }}" readonly>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label>Employee Name</label>
                                                            <input type="text" class="form-control" name="employee_name" value="{{ $expense->employee_name }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label>Amount</label>
                                                            <input type="number" step="0.01" class="form-control" name="amount" value="{{ $expense->amount }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label>Date</label>
                                                            <input type="date" class="form-control" name="date" value="{{ $expense->date }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label>Note</label>
                                                            <textarea name="note" class="form-control">{{ $expense->note }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
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

    @if(session('success'))
        toastr.success("{{ session('success') }}");
    @endif

    @if(session('error'))
        toastr.error("{{ session('error') }}");
    @endif

    @if(session('warning'))
        toastr.warning("{{ session('warning') }}");
    @endif

    @if($errors->any())
        @foreach($errors->all() as $error)
            toastr.error("{{ $error }}");
        @endforeach
    @endif
</script>
@endsection
