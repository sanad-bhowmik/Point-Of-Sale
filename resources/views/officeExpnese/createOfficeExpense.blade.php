@extends('layouts.app')

@section('title', 'Create Office Expense')

@section('breadcrumb')
<ol class="breadcrumb border-0 m-0">
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('office_expense.view') }}">Office Expenses</a></li>
    <li class="breadcrumb-item active">Add</li>
</ol>
@endsection

@section('content')
<div class="container-fluid">
    <form id="expense-form" action="{{ route('office_expense.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-lg-12">
                @include('utils.alerts')
                <div class="form-group mb-3">
                    <button type="submit" class="btn btn-primary">Create Office Expense <i class="bi bi-check"></i></button>
                </div>
            </div>

            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">

                        @php
                            use Illuminate\Support\Facades\DB;
                            $categories = DB::table('office_expense_categories')->get();
                        @endphp

                        <div class="form-row">
                            <!-- Expense Category Dropdown -->
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="category_id">Expense <span class="text-danger">*</span></label>
                                    <select name="category_id" id="category_id" class="form-control" required>
                                        <option value="" disabled selected>Select</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Employee Name -->
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="employee_name">Employee Name <span class="text-danger">*</span></label>
                                    <input type="text" name="employee_name" id="employee_name" class="form-control" placeholder="Enter Employee Name" required>
                                </div>
                            </div>

                            <!-- Amount -->
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="amount">Amount <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" id="amount" name="amount" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <!-- Date & Note -->
                        <div class="form-row mt-3">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="date">Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="date" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" required>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="note">Note (Optional)</label>
                                    <textarea name="note" id="note" class="form-control" rows="3"></textarea>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
    </form>
</div>
@endsection

@section('scripts')
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
