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

                                    <!-- LC -->
                                    <div class="form-row">
                                        <div class="col-lg-12">
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
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label for="container_id">Container Name <span
                                                        class="text-danger">*</span></label>
                                                <select name="container_id" id="containerSelect"
                                                    class="form-control select2" required>
                                                    <option value="">Select Container</option>
                                                    {{-- @foreach ($containers as $container)
                                                        <option value="{{ $container->id }}">{{ $container->name }}</option>
                                                    @endforeach --}}
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Amount & Date -->
                                    <div class="form-row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label for="total_amount">Total Amount</label>
                                                <input type="text" id="total_amount" class="form-control" readonly
                                                    value="0.00">
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label for="date">Date <span class="text-danger">*</span></label>
                                                <input type="date" class="form-control" name="date"
                                                    value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" required>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label for="note">Note (Optional)</label>
                                                <textarea name="note" id="note" class="form-control" rows="3"></textarea>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <!-- Expense Name (dependent on Category) -->
                                <div class="col-lg-6">
                                    <div id="subcategory-container" class="mb-3"></div>
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
            $('.select2').select2();

            // When category changes -> load subcategories
            $('#category_id').on('change', function() {
                const categoryId = $(this).val();
                const container = $('#subcategory-container');
                container.empty();
                $('#total_amount').val('0.00');

                if (!categoryId) return;

                $.get('/expenses/expense-names/' + categoryId, function(data) {
                    if (!data.length) {
                        container.html('<p class="text-muted">No subcategories found.</p>');
                        return;
                    }

                    let html = '<table class="table table-bordered">';
                    html += '<thead><tr><th>Subcategory</th><th>Amount</th></tr></thead><tbody>';

                    data.forEach(function(item) {
                        html += `
                    <tr>
                        <td>${item.expense_name}</td>
                        <td>
                            <input 
                                type="number" 
                                step="0.01" 
                                min="0" 
                                name="subcategory_amounts[${item.id}]" 
                                class="form-control subamount" 
                                placeholder="Enter amount">
                        </td>
                    </tr>`;
                    });

                    html += '</tbody></table>';
                    container.html(html);
                });
            });

            // Calculate total dynamically
            $(document).on('input', '.subamount', function() {
                let total = 0;
                $('.subamount').each(function() {
                    total += parseFloat($(this).val()) || 0;
                });
                $('#total_amount').val(total.toFixed(2));
            });

            // If category changes again -> clear subcategories
            $('#category_id').on('change', function() {
                $('#subcategory-container').empty();
                $('#total_amount').val('0.00');
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
