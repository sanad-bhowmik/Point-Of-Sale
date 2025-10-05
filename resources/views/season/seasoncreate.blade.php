@extends('layouts.app')

@section('title', 'Create Seasonal Fruit')

@section('breadcrumb')
<ol class="breadcrumb border-0 m-0">
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('seasonalfruit.create') }}">Seasonal Fruits</a></li>
    <li class="breadcrumb-item active">Add</li>
</ol>
@endsection

@section('content')
<div class="container-fluid mb-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">

                    <form action="{{ route('seasonalfruit.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="form-row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="name">Fruit Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="name" id="name" required>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Season (From – To) <span class="text-danger">*</span></label>
                                    <div class="d-flex gap-2">
                                        <div class="position-relative flex-fill">
                                            <input type="month" class="form-control month-input-hide-year" name="season_from" required>
                                        </div>
                                        <div class="position-relative flex-fill">
                                            <input type="month" class="form-control month-input-hide-year" name="season_to" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-row mt-3">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="image">Image <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control" name="image" id="image" accept="image/*" required>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="remarks">Remarks (Optional)</label>
                                    <textarea name="remarks" id="remarks" rows="3" class="form-control" style="height: 6vh;"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">
                                Add Seasonal Fruit <i class="bi bi-check"></i>
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

<style>
/* Hide year in month inputs */
.month-input-hide-year {
    position: relative;
}

.month-input-hide-year::-webkit-datetime-edit-year-field {
    display: none !important;
}

.month-input-hide-year::-webkit-datetime-edit-text {
    display: none;
}

.month-input-hide-year::-webkit-datetime-edit-month-field {
    color: #495057;
    padding: 0;
}

/* For Firefox */
.month-input-hide-year {
    color: transparent;
    position: relative;
}

.month-input-hide-year:focus {
    color: transparent;
}

.month-input-hide-year::before {
    content: attr(placeholder);
    color: #495057;
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    pointer-events: none;
}

.month-input-hide-year:focus::before {
    display: none;
}

.month-input-hide-year:not(:placeholder-shown)::before {
    display: none;
}

/* Show only month when not focused */
.month-input-hide-year:not(:focus) {
    color: transparent;
}

.month-input-hide-year:not(:focus)::after {
    content: attr(data-month-display);
    color: #495057;
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    pointer-events: none;
}
</style>

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

    // Update month display values
    document.addEventListener('DOMContentLoaded', function() {
        const monthInputs = document.querySelectorAll('.month-input-hide-year');
        const monthNames = ["January", "February", "March", "April", "May", "June",
                           "July", "August", "September", "October", "November", "December"];

        monthInputs.forEach(input => {
            // Set placeholder
            input.setAttribute('placeholder', 'Select month');

            // Update display when value changes
            input.addEventListener('change', function() {
                if (this.value) {
                    const monthIndex = parseInt(this.value.split('-')[1]) - 1;
                    this.setAttribute('data-month-display', monthNames[monthIndex]);
                }
            });

            // Initialize display if there's a value
            if (input.value) {
                const monthIndex = parseInt(input.value.split('-')[1]) - 1;
                input.setAttribute('data-month-display', monthNames[monthIndex]);
            }
        });
    });
</script>

@endsection
