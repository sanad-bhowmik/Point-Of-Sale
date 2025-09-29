@extends('layouts.app')

@section('title', 'Edit Office Expense')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ request()->get('page') === 'cashInHistory' ? route('office_expense.history') : route('office_expense.view') }}">{{ request()->get('page') === 'cashInHistory' ? 'Cash In History' : 'Office Expenses' }}</a></li>
        <li class="breadcrumb-item active">Edit</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        <form id="expense-form" action="{{ route('office_expense.update', $expense->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-lg-12">
                    @include('utils.alerts')
                    <div class="form-group mb-3">
                        <button type="submit" class="btn btn-primary">
                            {{ request()->get('page') === 'cashInHistory' ? 'Update Cash In' : 'Update Office Expense' }} <i class="bi bi-check"></i>
                        </button>
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
                                        <label for="category_id">Expense Category <span class="text-danger">*</span></label>
                                        <select name="category_id" id="category_id" class="form-control" required>
                                            <option value="" disabled>Select</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}" 
                                                    {{ $expense->category_id == $category->id ? 'selected' : '' }}>
                                                    {{ $category->category_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                @if (request()->get('page') !== 'cashInHistory')
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="employee_name">Employee Name</label>
                                        <input type="text" name="employee_name" id="employee_name" 
                                               class="form-control"
                                               value="{{ old('employee_name', $expense->employee_name) }}"
                                               placeholder="Enter Employee Name">
                                    </div>
                                </div>
                                @endif

                                <input type="text" name="status" value="{{ request()->get('page') === 'cashInHistory' ? 'in' : 'out' }}" hidden>
                                {{-- status --}}
                                {{-- <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="status">Status <span class="text-danger">*</span></label>
                                        <select name="status" id="status" class="form-control" required>
                                            <option value="in" {{ $expense->status == 'in' ? 'selected' : '' }}>In Amount</option>
                                            <option value="out" {{ $expense->status == 'out' ? 'selected' : '' }}>Out Amount</option>
                                        </select>
                                    </div>
                                </div> --}}

                                @if (request()->get('page') !== 'cashInHistory')
                                    <!-- Quantity -->
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="quantity">Quantity</label>
                                        <input type="text" id="quantity" name="quantity" 
                                               class="form-control"
                                               value="{{ old('quantity', $expense->quantity) }}">
                                    </div>
                                </div>
                                @endif

                                <!-- Amount -->
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="amount">Amount <span class="text-danger">*</span></label>
                                        <input type="text" id="amount" name="amount" 
                                               class="form-control"
                                               value="{{ old('amount', $expense->amount) }}" required>
                                    </div>
                                </div>

                                <!-- Date -->
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="date">Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="date"
                                               value="{{ old('date', \Carbon\Carbon::parse($expense->date)->format('Y-m-d')) }}"
                                               required>
                                    </div>
                                </div>

                                <!-- Note -->
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="note">Note (Optional)</label>
                                        <textarea name="note" id="note" class="form-control" rows="12">{{ old('note', $expense->note) }}</textarea>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
        </form>
    </div>
@endsection

@push('page_scripts')
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
@endpush
