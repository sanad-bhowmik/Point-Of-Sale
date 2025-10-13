@extends('layouts.app')

@section('title', 'Create Investment')

@section('breadcrumb')
<ol class="breadcrumb border-0 m-0">
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('investment.create') }}">Investment</a></li>
    <li class="breadcrumb-item active">Create</li>
</ol>
@endsection

@section('content')
<div class="container-fluid mb-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">

                    <form action="{{ route('investment.store') }}" method="POST">
                        @csrf

                        <div class="row g-3">
                            <div class="col-lg-6 mb-3">
                                <label for="date">Date <span class="text-danger">*</span></label>
                                <input type="date" name="date" id="date" class="form-control" required>
                            </div>

                            <div class="col-lg-6 mb-3">
                                <label for="lc_number">LC Number <span class="text-danger">*</span></label>
                                <input type="text" name="lc_number" id="lc_number" class="form-control" required>
                            </div>


                            <div class="col-lg-6 mb-3">
                                <label for="amount">Amount <span class="text-danger">*</span></label>
                                <input type="number" name="amount" id="amount" class="form-control" required>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label for="invest">Invest <span class="text-danger">*</span></label>
                                <select name="investment" id="investment" class="form-control" required>
                                    <option value="">Select Investment Type</option>
                                    <option value="Invest">Invest</option>
                                    <option value="Expense">Expense</option>
                                    <option value="Profit">Profit</option>
                                    <option value="Cash Invest">Cash Invest</option>
                                </select>

                            </div>
                            <div class="col-lg-12 mb-3">
                                <label for="description">Description </label>
                                <textarea name="description" id="description" class="form-control" rows="2"></textarea>
                            </div>

                        </div>

                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">
                                Add Investment
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
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "timeOut": "5000"
    };
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
