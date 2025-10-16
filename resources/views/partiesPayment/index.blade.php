@extends('layouts.app')

@section('title', 'Create Parties Payment')

@section('breadcrumb')
<ol class="breadcrumb border-0 m-0">
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('partiesPayment.index') }}">Parties Payment</a></li>
    <li class="breadcrumb-item active">Create</li>
</ol>
@endsection

@section('content')
<div class="container-fluid mb-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">

                    <form action="{{ route('partiesPayment.store') }}" method="POST">
                        @csrf

                        <div class="row g-3">
                            <div class="col-lg-6 mb-3">
                                <label for="name">Party Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control" required>
                            </div>

                            <div class="col-lg-6 mb-3">
                                <label for="usd_amount">USD Amount <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="usd_amount" id="usd_amount" class="form-control" required>
                            </div>

                            <div class="col-lg-6 mb-3">
                                <label for="exchange_rate">Exchange Rate <span class="text-danger">*</span></label>
                                <input type="number" step="0.0001" name="exchange_rate" id="exchange_rate" class="form-control" required>
                            </div>

                            <!-- New Dropdown -->
                            <div class="col-lg-6 mb-3">
                                <label for="type">Payment Type <span class="text-danger">*</span></label>
                                <select name="type" id="type" class="form-control" required>
                                    <option value="normal" selected>Select Type</option>
                                    <option value="damarage">Damarage</option>
                                </select>
                            </div>


                            <!-- Normal Amount (visible by default) -->
                            <div class="col-lg-6 mb-3" id="normalAmountDiv">
                                <label for="amount">Amount (Tk) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="amount" id="amount" class="form-control">
                            </div>

                            <!-- Damarage Amount (hidden by default) -->
                            <div class="col-lg-6 mb-3 d-none" id="damarageAmountDiv">
                                <label for="damarage_amount">Amount (Tk) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="damarage_amount" id="damarage_amount" class="form-control">
                            </div>

                            <div class="col-lg-6 mb-3">
                                <label for="date">Date <span class="text-danger">*</span></label>
                                <input type="date" name="date" id="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>

                            <div class="col-lg-12 mb-3">
                                <label for="description">Description</label>
                                <textarea name="description" id="description" class="form-control" rows="2"></textarea>
                            </div>
                        </div>

                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">
                                Add Payment
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
    // Toastr configuration
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
    @if($errors->any())
        @foreach($errors->all() as $error)
            toastr.error("{{ $error }}");
        @endforeach
    @endif

    // Toggle amount fields based on dropdown
    $(document).ready(function() {
        $('#type').on('change', function() {
            const selected = $(this).val();
            if (selected === 'damarage') {
                $('#normalAmountDiv').addClass('d-none');
                $('#damarageAmountDiv').removeClass('d-none');
                $('#amount').val('');
            } else {
                $('#damarageAmountDiv').addClass('d-none');
                $('#normalAmountDiv').removeClass('d-none');
                $('#damarage_amount').val('');
            }
        });
    });
</script>
@endsection
