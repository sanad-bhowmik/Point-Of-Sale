@extends('layouts.app')

@section('title', 'Create Seasonal Fruit')

@section('breadcrumb')
<ol class="breadcrumb border-0 m-0">
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('seasonalfruit.create') }}">Input Permit</a></li>
    <li class="breadcrumb-item active">Create</li>
</ol>
@endsection

@section('content')
<div class="container-fluid mb-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">

                    <form action="{{ route('input_permit.store') }}" method="POST">
                        @csrf

                        <div class="row g-3">
                            <div class="col-lg-6 mb-3">
                                <label for="to">To</label>
                                <input type="text" name="to" id="to" class="form-control">
                            </div>

                            <div class="col-lg-6 mb-3">
                                <label for="reference">Reference</label>
                                <input type="text" name="reference" id="reference" class="form-control">
                            </div>

                            <div class="col-lg-6 mb-3">
                                <label for="no">No</label>
                                <input type="text" name="no" id="no" class="form-control">
                            </div>

                            <div class="col-lg-6 mb-3">
                                <label for="date">Date</label>
                                <input type="date" name="date" id="date" class="form-control">
                            </div>

                            <div class="col-lg-6 mb-3">
                                <label for="importer_name">Importer Name</label>
                                <input type="text" name="importer_name" id="importer_name" class="form-control">
                            </div>

                            <div class="col-lg-6 mb-3">
                                <label for="importer_address">Importer Address</label>
                                <textarea name="importer_address" id="importer_address" class="form-control" rows="2"></textarea>
                            </div>

                            <div class="col-lg-6 mb-3">
                                <label for="means_of_transport">Means of Transport</label>
                                <input type="text" name="means_of_transport" id="means_of_transport" class="form-control">
                            </div>

                            <div class="col-lg-6 mb-3">
                                <label for="consignor_name">Consignor Name</label>
                                <input type="text" name="consignor_name" id="consignor_name" class="form-control">
                            </div>

                            <div class="col-lg-6 mb-3">
                                <label for="consignor_address">Consignor Address</label>
                                <textarea name="consignor_address" id="consignor_address" class="form-control" rows="2"></textarea>
                            </div>

                            <div class="col-lg-6 mb-3">
                                <label for="country_of_origin">Country of Origin</label>
                                <input type="text" name="country_of_origin" id="country_of_origin" class="form-control">
                            </div>

                            <div class="col-lg-6 mb-3">
                                <label for="country_of_export">Country of Export</label>
                                <input type="text" name="country_of_export" id="country_of_export" class="form-control">
                            </div>

                            <div class="col-lg-6 mb-3">
                                <label for="point_of_entry">Point of Entry</label>
                                <input type="text" name="point_of_entry" id="point_of_entry" class="form-control">
                            </div>

                            <div class="col-lg-6 mb-3">
                                <label for="plant_name">Name of Plant / Plant Products</label>
                                <input type="text" name="plant_name" id="plant_name" class="form-control">
                            </div>

                            <div class="col-lg-6 mb-3">
                                <label for="variety_category">Variety / Category</label>
                                <input type="text" name="variety_category" id="variety_category" class="form-control">
                            </div>

                            <div class="col-lg-6 mb-3">
                                <label for="pack_size">Pack Size</label>
                                <input type="text" name="pack_size" id="pack_size" class="form-control">
                            </div>

                            <div class="col-lg-6 mb-3">
                                <label for="quantity">Quantity</label>
                                <input type="number" name="quantity" id="quantity" class="form-control">
                            </div>

                            <div class="col-lg-6 mb-3">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="">Select Status</option>
                                    <option value="0">Pending</option>
                                    <option value="1">Approved</option>
                                    <option value="2">Rejected</option>
                                </select>
                            </div>

                        </div>

                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">
                                Add Input Permit <i class="bi bi-check"></i>
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
