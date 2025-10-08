@extends('layouts.app')

@section('title', 'Update Lunch')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('catering.index') }}">Lunch List</a></li>
        <li class="breadcrumb-item active">Update Lunch</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid mb-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('catering.update', $lunch->id) }}" method="POST">
                            @csrf
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="date" class="form-label">Date</label>
                                    <input type="text" id="date" name="date" class="form-control"
                                        placeholder="Select date" required autocomplete="off"
                                        value="{{ old('date', \Carbon\Carbon::parse($lunch->date)->format('d-m-Y')) }}">
                                </div>
                                <div class="col-md-4">
                                    <label for="quantity" class="form-label">Quantity</label>
                                    <input type="text" id="quantity" name="quantity" class="form-control"
                                        value="{{ old('quantity', $lunch->quantity) }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="unit_price" class="form-label">Unit Price</label>
                                    <input type="text" id="unit_price" name="unit_price" class="form-control"
                                        min="0" value="{{ old('unit_price', $lunch->unit_price) }}" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="total" class="form-label">Total</label>
                                    <input type="text" id="total" name="total" class="form-control" readonly
                                        value="{{ old('total', number_format($lunch->quantity * $lunch->unit_price, 2)) }}">
                                </div>
                                <div class="col-md-8">
                                    <label for="note" class="form-label">Note (Optional)</label>
                                    <textarea id="note" name="note" class="form-control" rows="4" placeholder="Add a note">{{ old('note', $lunch->note) }}</textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-end">
                                    <button type="submit" class="btn btn-success">Update Lunch</button>
                                </div>
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

    <script>
        flatpickr("#date", {
            dateFormat: "d-m-Y",
            defaultDate: "today"
        });

        function calculateTotal() {
            let qty = parseFloat($('#quantity').val()) || 0;
            let price = parseFloat($('#unit_price').val()) || 0;
            let total = (qty * price).toFixed(2);
            $('#total').val(total);
        }

        $('#quantity, #unit_price').on('input', calculateTotal);
        $(document).ready(calculateTotal);

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
@endsection
