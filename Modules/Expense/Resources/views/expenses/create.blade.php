@extends('layouts.app')

@section('title', 'Create Expense')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('expenses.index') }}">Expenses</a></li>
        <li class="breadcrumb-item active">Add</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        <form id="expense-form" action="{{ route('expenses.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-lg-12">
                    @include('utils.alerts')
                    <div class="form-group">
                        <button class="btn btn-primary">Create Expense <i class="bi bi-check"></i></button>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">

                            <!-- Expense Category -->
                            <div class="form-row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="category_id">Expense Category <span class="text-danger">*</span></label>
                                        <select name="category_id" id="category_id" class="form-control select2" required>
                                            <option value="">Select Category</option>
                                            @foreach (\Modules\Expense\Entities\ExpenseCategory::all() as $category)
                                                <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Expense Name (dependent on Category) -->
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="expense_name_id">Expense Name <span class="text-danger">*</span></label>
                                        <select name="expense_name_id" id="expense_name_id" class="form-control select2"
                                            required>
                                            <option value="">Select Expense Name</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- LC -->
                            <div class="form-row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="lc_id">LC Number <span class="text-danger">*</span></label>
                                        <select name="lc_id" id="lcSelect" class="form-control select2" required>
                                            <option value="">Select LC</option>
                                            @foreach ($lcs as $lc)
                                                <option value="{{ $lc->id }}">
                                                    {{ $lc->lc_name }}--({{ $lc->lc_number }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Container Name (optional, can be dynamic based on LC) -->
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="container_id">Container Name <span class="text-danger">*</span></label>
                                        <select name="container_id" id="containerSelect" class="form-control select2" required>
                                            <option value="">Select Container</option>
                                            @foreach ($containers as $container)
                                                <option value="{{ $container->id }}">{{ $container->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Amount & Date -->
                            <div class="form-row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="amount">Amount <span class="text-danger">*</span></label>
                                        <input id="amount" type="text" class="form-control" name="amount" required>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="date">Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="date"
                                            value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" required>
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

@push('page_css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@push('page_scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.select2').select2({
                width: '100%',
                placeholder: 'Select an option'
            });

            // When Category changes, load Expense Names
            $('#category_id').on('change', function() {
                var categoryId = $(this).val();
                $('#expense_name_id').html('<option value="">Loading...</option>');

                if (categoryId) {
                    $.ajax({
                        url: '/expenses/expense-names/' + categoryId,
                        type: 'GET',
                        success: function(data) {
                            var options = '<option value="">Select Expense Name</option>';
                            data.forEach(function(expense) {
                                options +=
                                    `<option value="${expense.id}">${expense.expense_name}</option>`;
                            });
                            $('#expense_name_id').html(options).trigger('change');
                        }
                    });
                } else {
                    $('#expense_name_id').html('<option value="">Select Expense Name</option>');
                }
            });

            $('#lcSelect').on('change', function() {
                var lcId = $(this).val();
                var $containerSelect = $('#containerSelect');
                $containerSelect.html('<option value="">Loading...</option>');
                if (lcId) {
                    $.get('/get-containers-by-lc/' + lcId, function(data) {
                        var options = '<option value="">-- Select Container --</option>';
                        data.forEach(function(container) {
                            options +=
                                `<option value="${container.id}">${container.name} (${container.number})</option>`;
                        });
                        $containerSelect.html(options).trigger('change');
                    });
                } else {
                    $containerSelect.html('<option value="">-- Select Container --</option>').trigger(
                        'change');
                }
            });

        });
    </script>
@endpush
