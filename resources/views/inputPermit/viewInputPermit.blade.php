@extends('layouts.app')

@section('title', 'Input Permits')

@section('breadcrumb')
<ol class="breadcrumb border-0 m-0">
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
    <li class="breadcrumb-item active">Import Permits</li>
</ol>
@endsection

@section('content')
<div class="container-fluid mb-4">
    <div class="row mb-3">
        <div class="col-md-12">

        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <a href="{{ route('input_permit.create') }}" class="btn btn-primary btn-sm">
                            + Add Import Permit
                        </a>
                        <form method="GET" action="{{ route('input_permit.view') }}" class="mb-3 d-flex gap-2" style="gap: 10px;">
                            <input type="text" name="reference" value="{{ request('reference') }}" class="form-control" placeholder="Search Reference">
                            <input type="date" name="date" value="{{ request('date') }}" class="form-control">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </form>
                        <button class="btn btn-secondary buttons-excel" onclick="downloadTableAsExcel()">
                            <i class="bi bi-file-earmark-excel-fill"></i> Excel
                        </button>
                        {{ $inputPermits->links() }}
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>To</th>
                                    <th>Reference</th>
                                    <th>No</th>
                                    <th>Date</th>
                                    <th>Importer Name</th>
                                    <th>Importer Address</th>
                                    <th>Means of Transport</th>
                                    <th>Consignor Name</th>
                                    <th>Consignor Address</th>
                                    <th>Country of Origin</th>
                                    <th>Country of Export</th>
                                    <th>Point of Entry</th>
                                    <th>Plant Name & Products</th>
                                    <th>Variety / Category</th>
                                    <th>Pack Size</th>
                                    <th>Quantity</th>
                                    <th>Status</th>
                                    <th>Attachment</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($inputPermits as $index => $permit)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $permit->to_text ?? '-' }}</td>
                                    <td>{{ $permit->reference ?? '-' }}</td>
                                    <td>{{ $permit->no ?? '-' }}</td>
                                    <td>{{ $permit->date ?? '-' }}</td>
                                    <td>{{ $permit->importer_name ?? '-' }}</td>
                                    <td>{{ $permit->importer_address ?? '-' }}</td>
                                    <td>{{ $permit->means_of_transport ?? '-' }}</td>
                                    <td>{{ $permit->consignor_name ?? '-' }}</td>
                                    <td>{{ $permit->consignor_address ?? '-' }}</td>
                                    <td>{{ $permit->country_of_origin ?? '-' }}</td>
                                    <td>{{ $permit->country_of_export ?? '-' }}</td>
                                    <td>{{ $permit->point_of_entry ?? '-' }}</td>
                                    <td>{{ $permit->plant_name_and_products ?? '-' }}</td>
                                    <td>{{ $permit->variety_or_category ?? '-' }}</td>
                                    <td>{{ $permit->pack_size ?? '-' }}</td>
                                    <td>{{ $permit->quantity ?? '-' }}</td>
                                    <td>
                                        @switch($permit->status)
                                        @case(0)
                                        Pending
                                        @break
                                        @case(1)
                                        Approved
                                        @break
                                        @case(2)
                                        Rejected
                                        @break
                                        @default
                                        -
                                        @endswitch
                                    </td>

                                    <td>
                                        @if($permit->attachment)
                                        <a href="{{ $permit->attachment }}" target="_blank"><img src="{{ asset('images/google-docs.png') }}" alt="View" style="width: 30px; height: 30px;"></a>
                                        @else
                                        -
                                        @endif
                                    </td>
                                    <td>
                                        <!-- Edit Modal Trigger -->
                                        <button type="button" class="btn btn-sm btn-info mb-1" data-bs-toggle="modal" data-bs-target="#editPermitModal{{ $permit->id }}">
                                            Edit
                                        </button>

                                        <!-- Delete Form -->
                                        <form action="{{ route('input_permit.destroy', $permit->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this input permit?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                        </form>

                                        <!-- Edit Modal -->
                                        <div class="modal fade" id="editPermitModal{{ $permit->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Import Permit</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form action="{{ route('input_permit.update', $permit->id) }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-body">
                                                            <div class="row g-3">
                                                                <div class="col-md-6 mb-3">
                                                                    <label>To</label>
                                                                    <input type="text" name="to" class="form-control" value="{{ $permit->to }}">
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <label>Reference</label>
                                                                    <input type="text" name="reference" class="form-control" value="{{ $permit->reference }}">
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <label>No</label>
                                                                    <input type="text" name="no" class="form-control" value="{{ $permit->no }}">
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <label>Date</label>
                                                                    <input type="date" name="date" class="form-control" value="{{ $permit->date }}">
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <label>Importer Name</label>
                                                                    <input type="text" name="importer_name" class="form-control" value="{{ $permit->importer_name }}">
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <label>Importer Address</label>
                                                                    <input type="text" name="importer_address" class="form-control" value="{{ $permit->importer_address }}">
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <label>Means of Transport</label>
                                                                    <input type="text" name="means_of_transport" class="form-control" value="{{ $permit->means_of_transport }}">
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <label>Consignor Name</label>
                                                                    <input type="text" name="consignor_name" class="form-control" value="{{ $permit->consignor_name }}">
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <label>Consignor Address</label>
                                                                    <input type="text" name="consignor_address" class="form-control" value="{{ $permit->consignor_address }}">
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <label>Country of Origin</label>
                                                                    <input type="text" name="country_of_origin" class="form-control" value="{{ $permit->country_of_origin }}">
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <label>Country of Export</label>
                                                                    <input type="text" name="country_of_export" class="form-control" value="{{ $permit->country_of_export }}">
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <label>Point of Entry</label>
                                                                    <input type="text" name="point_of_entry" class="form-control" value="{{ $permit->point_of_entry }}">
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <label>Plant Name & Products</label>
                                                                    <input type="text" name="plant_name" class="form-control" value="{{ $permit->plant_name }}">
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <label>Variety / Category</label>
                                                                    <input type="text" name="variety_category" class="form-control" value="{{ $permit->variety_category }}">
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <label>Pack Size</label>
                                                                    <input type="text" name="pack_size" class="form-control" value="{{ $permit->pack_size }}">
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <label>Quantity</label>
                                                                    <input type="number" name="quantity" class="form-control" value="{{ $permit->quantity }}">
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <label>Status</label>
                                                                    <input type="text" name="status" class="form-control" value="{{ $permit->status }}">
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <label>Attachment</label>
                                                                    <input type="file" name="attachment" class="form-control">
                                                                    @if($permit->attachment)
                                                                    <small><a href="{{ $permit->attachment }}" target="_blank">Current Attachment</a></small>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary">Update</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Modal -->
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="20" class="text-center text-muted">No input permits found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $inputPermits->links() }} <!-- Pagination -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Toastr CSS & JS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    function downloadTableAsExcel() {
        let table = document.querySelector("table");
        let rows = table.querySelectorAll("tr");

        let excelContent = "<table border='1' style='border-collapse:collapse;'>";

        let totalCols = rows[0].querySelectorAll("th, td").length - 2;
        excelContent += `<tr>
                        <th colspan="${totalCols}" rowspan="3"
                            style="text-align:center; vertical-align:middle; font-size:28px; font-weight:bold; padding:15px;">
                            Import Permit
                        </th>
                     </tr>`;
        excelContent += `<tr></tr><tr></tr>`;

        rows.forEach((row, rowIndex) => {
            let cells = row.querySelectorAll("th, td");
            excelContent += "<tr>";

            cells.forEach((cell, colIndex) => {
                if (colIndex >= cells.length - 2) return;
                let tag = (rowIndex === 0) ? "th" : "td";
                excelContent += `<${tag} style="padding:5px;">${cell.innerText.trim()}</${tag}>`;
            });

            excelContent += "</tr>";
        });

        excelContent += "</table>";

        let today = new Date();
        let day = String(today.getDate()).padStart(2, '0');
        let month = String(today.getMonth() + 1).padStart(2, '0');
        let year = today.getFullYear();
        let filename = `Import_Permit_${day}_${month}_${year}_.xls`;

        let downloadLink = document.createElement("a");
        downloadLink.href = 'data:application/vnd.ms-excel,' + encodeURIComponent(excelContent);
        downloadLink.download = filename;
        downloadLink.click();
    }
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
</script>
@endsection
