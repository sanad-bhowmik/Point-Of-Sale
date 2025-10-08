@extends('layouts.app')

@section('title', 'Lunch List')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item active">Lunch List</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid mb-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center">
                                <div class="">
                                    <form action="{{ route('catering.index') }}" method="GET"
                                        class="d-flex align-items-center" style="gap: 16px;">
                                        <div class="input-group">
                                            <input type="text" name="date_range" id="date_range" class="form-control"
                                                placeholder="Select date range"
                                                value="{{ old('date_range', request('date_range')) }}">
                                            <span class="input-group-text" style="font-size: 12px"><i
                                                    class="bi bi-calendar-event"></i></span>
                                        </div>
                                        <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                                        <a href="{{ route('catering.index') }}" class="btn btn-sm btn-secondary">Reset</a>
                                    </form>
                                </div>
                                <button id="downloadExcelRaw" class="btn btn-success ml-3">Download Excel</button>
                            </div>
                            <a href="{{ route('catering.create') }}" class="btn btn-primary btn-sm">
                                + Add Lunch
                            </a>
                        </div>
                        <div class="table-responsive mt-4">
                            <table id="lunch_list" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Note</th>
                                        <th>Quantity</th>
                                        <th>Unit Price</th>
                                        <th>Total</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($lunches as $lunch)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ \Carbon\Carbon::parse($lunch->date)->format('d-m-Y') }}</td>
                                            <td>{{ $lunch?->note }}</td>
                                            <td>{{ $lunch?->quantity }}</td>
                                            <td>{{ number_format($lunch?->unit_price, 2) }}</td>
                                            <td>{{ number_format($lunch?->total, 2) }}</td>
                                            <td>
                                                <a href="{{ route('catering.edit', $lunch->id) }}"
                                                    class="btn btn-sm btn-warning">Edit</a>
                                                <form action="{{ route('catering.delete', $lunch->id) }}" method="POST"
                                                    class="d-inline"
                                                    onsubmit="return confirm('Are you sure you want to delete this lunch?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">No lunches found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
@endsection

@push('page_scripts')
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Toastr CSS & JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let dateInput = $('#date_range');
            let existingValue = dateInput.val();

            dateInput.daterangepicker({
                autoUpdateInput: true,
                locale: {
                    format: 'YYYY-MM-DD',
                    cancelLabel: 'Clear'
                },
                startDate: existingValue ? existingValue.split(' - ')[0] : moment(),
                endDate: existingValue ? existingValue.split(' - ')[1] : moment()
            });

            dateInput.on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format(
                    'YYYY-MM-DD'));
            });

            dateInput.on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });
        });

        document.getElementById("downloadExcelRaw").addEventListener("click", function() {
            let table = document.getElementById("lunch_list");
            if (!table) {
                alert("Table not found!");
                return;
            }

            // Clone the table to avoid changing the DOM
            let clone = table.cloneNode(true);

            // Remove last column from thead
            if (clone.tHead && clone.tHead.rows[0].cells.length > 0) {
                clone.tHead.rows[0].deleteCell(-1);
            }

            // Remove last column from all tbody rows
            for (let row of clone.tBodies[0].rows) {
                if (row.cells.length > 0) {
                    row.deleteCell(-1);
                }
            }

            // Excel styling
            let style = `
                        <style>
                        * {
                            font-family: Roboto, Arial, sans-serif;
                        }
                        table, th, td {
                            font-size: 16px;
                            border: 1px solid #000;
                            border-collapse: collapse;
                            text-align: center;
                        }
                        th, td {
                            padding: 18px 30px; 
                            height: 45px;
                            width: 200px;
                            vertical-align: middle;
                        }
                        th {
                            font-weight: bold;
                        }
                    </style>
                    `;

            let tableHTML = style + clone.outerHTML;

            let blob = new Blob(
                ['\ufeff' + tableHTML], {
                    type: "application/vnd.ms-excel"
                }
            );

            let url = URL.createObjectURL(blob);
            let a = document.createElement("a");
            a.href = url;
            a.download = "lunch_list.xls";
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        });

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

@push('page_css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endpush
