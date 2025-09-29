@extends('layouts.app')

@section('title', 'Create Office Expense Category')

@section('breadcrumb')
<ol class="breadcrumb border-0 m-0">
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('office_expense.view') }}">Office Expense Categories</a></li>
    <li class="breadcrumb-item active">Create</li>
</ol>
@endsection

@section('content')
<div class="container-fluid">
    <form id="office-expense-category-form" action="{{ route('office_expense.store_office_expense_category') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-lg-12">
                @include('utils.alerts')
                <div class="d-flex gap-2 mb-3">
                    <!-- Save Category Button -->
                    <button type="submit" class="btn btn-primary mr-3">
                        Save Category <i class="bi bi-check"></i>
                    </button>

                    <!-- View Category Button -->
                    <a href="{{ route('office_expense.view_names') }}" class="btn btn-secondary">
                        View Category <i class="bi bi-eye"></i>
                    </a>
                </div>
            </div>


            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="form-row">

                            <!-- Category Name -->
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="category_name">Category Name <span class="text-danger">*</span></label>
                                    <input type="text" name="category_name" id="category_name" class="form-control" placeholder="Enter Category Name" required>
                                </div>
                            </div>

                            <!-- Category Description -->
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="category_description">Category Description (Optional)</label>
                                    <input type="text" name="category_description" id="category_description" class="form-control" placeholder="Enter Description">
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
    // toastr.options = {
    //     "closeButton": true,
    //     "progressBar": true,
    //     "positionClass": "toast-top-right",
    //     "timeOut": "5000"
    // };

    // @if(session('success'))
    //     toastr.success("{{ session('success') }}");
    // @endif

    // @if(session('error'))
    //     toastr.error("{{ session('error') }}");
    // @endif

    // @if(session('warning'))
    //     toastr.warning("{{ session('warning') }}");
    // @endif

    // @if($errors->any())
    //     @foreach($errors->all() as $error)
    //         toastr.error("{{ $error }}");
    //     @endforeach
    // @endif
</script>
@endsection
