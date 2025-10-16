@extends('layouts.app')

@section('title', 'Add Lunch')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('catering.index') }}">Lunch List</a></li>
        <li class="breadcrumb-item active">Add Lunch</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid mb-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('catering.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="date" class="form-label">Date</label>
                                    <input type="text" id="date" name="date" class="form-control"
                                        placeholder="Select date" required autocomplete="off">
                                </div>
                                <div class="col-md-4">
                                    <label for="quantity" class="form-label">Quantity</label>
                                    <input type="text" id="quantity" name="quantity" class="form-control" value="0"
                                        required>
                                </div>
                                <div class="col-md-4">
                                    <label for="unit_price" class="form-label">Unit Price</label>
                                    <input type="text" id="unit_price" name="unit_price" class="form-control"
                                        min="0" value="0" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="total" class="form-label">Total</label>
                                    <input type="text" id="total" name="total" class="form-control" readonly
                                        value="0">
                                </div>
                                <div class="col-md-8">
                                    <label for="note" class="form-label">Note (Optional)</label>
                                    <textarea id="note" name="note" class="form-control" rows="4" placeholder="Add a note"></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Upload Excel File</label>
                                    <div class="upload-box" id="uploadBox">
                                        <input type="file" id="excel_file" name="excel_file" accept=".xlsx,.xls,.csv">
                                        <div class="upload-info" id="uploadInfo">
                                            <i class="fa-solid fa-cloud-arrow-up"></i>
                                            <p>Click or Drag & Drop file here</p>
                                            <small>Allowed formats: .xlsx, .xls, .csv</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-end">
                                    <button type="submit" class="btn btn-primary">Add Lunch</button>
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

        const uploadBox = document.getElementById('uploadBox');
        const fileInput = document.getElementById('excel_file');
        const uploadInfo = document.getElementById('uploadInfo');

        // Display selected file name
        fileInput.addEventListener('change', () => {
            if (fileInput.files.length > 0) {
                uploadInfo.querySelector('p').textContent = fileInput.files[0].name;
            } else {
                uploadInfo.querySelector('p').textContent = 'Click or Drag & Drop file here';
            }
        });

        // Drag & drop visual feedback
        uploadBox.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadBox.classList.add('hover');
        });

        uploadBox.addEventListener('dragleave', () => {
            uploadBox.classList.remove('hover');
        });

        uploadBox.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadBox.classList.remove('hover');
            fileInput.files = e.dataTransfer.files;
            uploadInfo.querySelector('p').textContent = fileInput.files[0].name;
        });
    </script>

    <style>
        .upload-box {
            position: relative;
            border: 2px dashed #cbd5e1;
            border-radius: 12px;
            background: #f9fafb;
            text-align: center;
            padding: 40px 20px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .upload-box.hover {
            border-color: #04415f;
            background: #f1f5f7;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .upload-box input[type="file"] {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        .upload-info i {
            font-size: 40px;
            color: #04415f;
            margin-bottom: 10px;
        }

        .upload-info p {
            font-size: 15px;
            font-weight: 500;
            margin-bottom: 5px;
            color: #011e2c;
        }

        .upload-info small {
            font-size: 13px;
            color: #6b7280;
        }

        .form-label {
            display: block;
            font-weight: 600;
            font-size: 15px;
            color: #011e2c;
            margin-bottom: 10px;
        }
    </style>
@endsection
