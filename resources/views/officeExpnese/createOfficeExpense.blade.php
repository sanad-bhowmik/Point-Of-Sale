@extends('layouts.app')

@section('title', 'Create Office Expense')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a
                href="{{ request()->get('page') === 'cashInHistory' ? route('office_expense.history') : route('office_expense.view') }}">{{ request()->get('page') === 'cashInHistory' ? 'Cash In History' : 'Office Expenses' }}</a>
        </li>
        <li class="breadcrumb-item active">Add</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        <form id="expense-form" action="{{ route('office_expense.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-lg-12">
                    @include('utils.alerts')
                    <div class="form-group mb-3">
                        <button type="submit" class="btn btn-primary">
                            {{ request()->get('page') === 'cashInHistory' ? 'Create Cash In' : 'Create Office Expense' }} <i
                                class="bi bi-check"></i></button>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">

                            @php
                                use Illuminate\Support\Facades\DB;
                                $categories = DB::table('office_expense_categories')->get();
                            @endphp

                            <div class="form-row">
                                <!-- Expense Category Dropdown -->
                                @if (request()->get('page') !== 'cashInHistory')
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="category_id">Expense Category<span
                                                    class="text-danger">*</span></label>
                                            <select name="category_id" id="category_id" class="form-control">
                                                <option value="" disabled selected>Select</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}">{{ $category->category_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @else
                                    <input type="text" name="category_id"
                                        value="{{ $categories->where('category_name', 'Funds')->first()?->id }}" hidden>
                                @endif

                                @if (request()->get('page') !== 'cashInHistory')
                                    <!-- Employee Name -->
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="employee_name">Employee Name</label>
                                            <input type="text" name="employee_name" id="employee_name"
                                                class="form-control" placeholder="Enter Employee Name">
                                        </div>
                                    </div>
                                @endif

                                <input type="text" name="status"
                                    value="{{ request()->get('page') === 'cashInHistory' ? 'in' : 'out' }}" hidden>
                                {{-- status --}}
                                {{-- <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="status">Status <span class="text-danger">*</span></label>
                                        <select name="status" id="status" class="form-control" required>
                                            <option value="in">In Amount</option>
                                            <option value="out">Out Amount</option>
                                        </select>
                                    </div>
                                </div> --}}

                                @if (request()->get('page') !== 'cashInHistory')
                                    <!-- Quantity -->
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="quantity">Quantity</label>
                                            <input type="text" id="quantity" name="quantity" class="form-control"
                                                value="">
                                        </div>
                                    </div>
                                @endif

                                <!-- Amount -->
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="amount">Amount <span class="text-danger">*</span></label>
                                        <input type="text" id="amount" name="amount" class="form-control" >
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="date">Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="date"
                                            value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="note">Note (Optional)</label>
                                        <textarea name="note" id="note" class="form-control" rows="12"></textarea>
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

@push('page_scripts')
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
@endpush
