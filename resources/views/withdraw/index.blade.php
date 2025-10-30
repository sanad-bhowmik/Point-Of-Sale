@extends('layouts.app')

@section('title', 'Withdraw Form')

@section('breadcrumb')
<ol class="breadcrumb border-0 m-0">
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
    <li class="breadcrumb-item active">Withdraw</li>
</ol>
@endsection

@section('content')
<div class="container-fluid mb-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">

                    <form action="{{ route('withdraw.store') }}" method="POST">
                        @csrf

                        <div class="row g-3">
                            <!-- Transaction Type -->
                            <div class="col-lg-6 mb-3">
                                <label for="transaction_type">Transaction Type</label>
                                <select name="transaction_type" id="transaction_type" class="form-control" required>
                                    <option value="">Select Type</option>
                                    <option value="cash_in_amount">Cash In From Outside</option>
                                    <option value="cash_withdraw_amount">Cash Withdraw</option>
                                </select>
                            </div>

                            <!-- Amount -->
                            <div class="col-lg-6 mb-3">
                                <label for="amount">Amount</label>
                                <input type="number" name="amount" id="amount" class="form-control" placeholder="Enter amount" required>
                            </div>

                            <!-- Description -->
                            <div class="col-lg-12 mb-3">
                                <label for="description">Description</label>
                                <textarea name="description" id="description" class="form-control" rows="3" placeholder="Enter description (optional)"></textarea>
                            </div>
                        </div>

                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">
                                Submit <i class="bi bi-check"></i>
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toastr CSS & JS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    // Toastr options
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "timeOut": "5000"
    };

    // Success message
    @if(session('success'))
        toastr.success("{{ session('success') }}");
    @endif

    // Error message
    @if(session('error'))
        toastr.error("{{ session('error') }}");
    @endif

    // Validation errors
    @if($errors->any())
        @foreach($errors->all() as $error)
            toastr.error("{{ $error }}");
        @endforeach
    @endif
</script>

@endsection
