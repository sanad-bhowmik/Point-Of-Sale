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
        <form id="office-expense-category-form" action="{{ route('office_expense.store_office_expense_category') }}"
            method="POST" enctype="multipart/form-data">
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
                                        <input type="text" name="category_name" id="category_name" class="form-control"
                                            placeholder="Enter Category Name">
                                    </div>
                                </div>

                                <!-- Category Description -->
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="category_description">Category Description (Optional)</label>
                                        <input type="text" name="category_description" id="category_description"
                                            class="form-control" placeholder="Enter Description">
                                    </div>
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
                        </div>
                    </div>
                </div>

        </form>
    </div>

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

    <script>
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
