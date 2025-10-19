@extends('layouts.app')

@section('title', 'Add Container')

@section('breadcrumb')
<ol class="breadcrumb border-0 m-0">
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('container.view') }}">Containers</a></li>
    <li class="breadcrumb-item active">Add Container</li>
</ol>
@endsection

@section('content')
<div class="container-fluid mb-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('container.store') }}" method="POST">
                        @csrf

                        <div class="form-row">

                            <!-- Container Information -->
                            <div class="col-md-12 mb-4">
                                <h5 class="section-title mb-3">Container Information</h5>
                                <div class="row">
                                    <div class="col-lg-3 mb-3">
                                        <label for="lc_id">LC Name <span class="text-danger">*</span></label>
                                        <select name="lc_id" id="lc_id" class="form-control" required>
                                            <option value="">Select LC</option>
                                            @foreach(\App\Models\Lc::all() as $lc)
                                                <option value="{{ $lc->id }}">{{ $lc->lc_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-3 mb-3 ">
                                        <label for="name">Container Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" id="name" class="form-control" placeholder="Enter container name" required>
                                    </div>

                                    <div class="col-lg-3 mb-3">
                                        <label for="number">Container Number <span class="text-danger">*</span></label>
                                        <input type="text" name="number" id="number" class="form-control" placeholder="Enter container number" required>
                                    </div>

                                    <div class="col-lg-3 mb-3">
                                        <label for="qty">Quantity <span class="text-danger">*</span></label>
                                        <input type="number" name="qty" id="qty" class="form-control" placeholder="Enter quantity" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Shipping Information -->
                            <div class="col-md-12 mb-4">
                                <h5 class="section-title mb-3">Shipping Information</h5>
                                <div class="row">
                                    <div class="col-lg-4 mb-3">
                                        <label for="shipping_date">Shipping Date</label>
                                        <div class="input-group">
                                            <input type="text" name="shipping_date" id="shipping_date" class="form-control flatpickr" placeholder="Select shipping date" data-date-format="Y-m-d">
                                            <div class="input-group-append">
                                                <span class="input-group-text" id="shipping_date_icon">
                                                    <i class="bi bi-calendar"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 mb-3">
                                        <label for="arriving_date">Arriving Date</label>
                                        <div class="input-group">
                                            <input type="text" name="arriving_date" id="arriving_date" class="form-control flatpickr" placeholder="Select arriving date" data-date-format="Y-m-d">
                                            <div class="input-group-append">
                                                <span class="input-group-text" id="arriving_date_icon">
                                                    <i class="bi bi-calendar"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 mb-3">
                                        <label for="status">Status</label>
                                        <select name="status" id="status" class="form-control">
                                            <option value="">-- Select Status --</option>
                                            <option value="0">Pending</option>
                                            <option value="1">Shipped</option>
                                            <option value="2">Arrived</option>
                                            <option value="3">Upcoming</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3 text-right">
                            <button type="submit" class="btn btn-primary">
                                Add Container <i class="bi bi-check"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Flatpickr CSS & JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<!-- Toastr CSS & JS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<style>
    .form-row { margin-bottom: 1rem; }
    .card { box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15); border: none; }
    .card-body { padding: 2rem; }
    .btn-primary {
        background-color: #3b7ddd;
        border-color: #3b7ddd;
        padding: 0.5rem 1.5rem;
        font-weight: 600;
        min-width: 150px;
    }
    .btn-primary:hover {
        background-color: #2c6ac1;
        border-color: #2c6ac1;
    }
    .input-group-text {
        background-color: #f8f9fa;
        cursor: pointer;
    }
    .flatpickr-input {
        background-color: #fff !important;
    }
    .section-title {
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #e9ecef;
        color: #3b7ddd;
        font-weight: 600;
    }
    .form-control:focus {
        border-color: #3b7ddd;
        box-shadow: 0 0 0 0.2rem rgba(59, 125, 221, 0.25);
    }
</style>

<script>
$(document).ready(function() {
    // Initialize date pickers
    const lcDate = flatpickr("#lc_date", { dateFormat: "Y-m-d", allowInput: true });
    const ttDate = flatpickr("#tt_date", { dateFormat: "Y-m-d", allowInput: true });
    const shippingDate = flatpickr("#shipping_date", { dateFormat: "Y-m-d", allowInput: true });
    const arrivingDate = flatpickr("#arriving_date", { dateFormat: "Y-m-d", allowInput: true });

    // Add click handlers for calendar icons
    $('#lc_date_icon').click(() => lcDate.open());
    $('#tt_date_icon').click(() => ttDate.open());
    $('#shipping_date_icon').click(() => shippingDate.open());
    $('#arriving_date_icon').click(() => arrivingDate.open());

    // Toastr notification configuration
    toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: "toast-top-right",
        timeOut: "5000"
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
});
</script>
@endsection
