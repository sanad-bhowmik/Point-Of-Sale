@extends('layouts.app')

@section('title', 'Containers')

@section('breadcrumb')
<ol class="breadcrumb border-0 m-0">
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
    <li class="breadcrumb-item active">Containers</li>
</ol>
@endsection

@section('content')
<div class="container-fluid mb-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Container List</h5>
                        <div>
                            <button class="btn btn-secondary buttons-excel" onclick="downloadTableAsExcel()">
                                <i class="bi bi-file-earmark-excel-fill"></i> Excel
                            </button>
                            <a href="{{ route('container.view') }}" class="btn btn-primary btn-sm">
                            + Add Container
                        </a>
                        </div>

                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>LC Name</th>
                                    <th>LC Number</th>
                                    <th>Container Name</th>
                                    <th>Container Number</th>
                                    <th>Container Quantity</th>
                                    <th>LC Value</th>
                                    <th>LC Exchange Rate</th>
                                    <th>LC Amount</th>
                                    <th>TT Value</th>
                                    <th>TT Exchange Rate</th>
                                    <th>TT Amount</th>

                                    <th>LC Total Amount ৳</th>
                                    <th>TT Total Amount ৳</th>
                                    <th>Grand Total ৳</th>
                                    <th>Shipping Date</th>
                                    <th>Arriving Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($containers as $index => $container)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $container->lc->lc_name ?? '-' }}</td>
                                    <td>{{ $container->lc->lc_number ?? '-' }}</td>
                                     <td>{{ $container->name }}</td>
                                    <td>{{ $container->number }}</td>
                                    <td>{{ $container->qty ?? '-' }}</td>
                                    <td>{{ $container->lc_value ?? '-' }}</td>
                                    <td>{{ $container->lc_exchange_rate ?? '-' }}</td>
                                    <td>
                                        {{ isset($container->lc_value, $container->lc_exchange_rate)
              ? number_format($container->lc_value * $container->lc_exchange_rate, 2)
              : '-' }}
                                    </td>
                                    <td>{{ $container->tt_value ?? '-' }}</td>
                                    <td>{{ $container->tt_exchange_rate ?? '-' }}</td>
                                    <td>
                                        {{ isset($container->tt_value, $container->tt_exchange_rate)
               ? number_format($container->tt_value * $container->tt_exchange_rate, 2)
               : '-' }}
                                    </td>


                                    {{-- LC Total Amount --}}
                                    <td>
                                        @php
                                        $lcAmount = isset($container->lc_value, $container->lc_exchange_rate)
                                        ? $container->lc_value * $container->lc_exchange_rate : 0;
                                        $lcTotal = $lcAmount * ($container->qty ?? 0);
                                        @endphp
                                        {{ $lcTotal ? number_format($lcTotal, 2) : '-' }}
                                    </td>

                                    {{-- TT Total Amount --}}
                                    <td>
                                        @php
                                        $ttAmount = isset($container->tt_value, $container->tt_exchange_rate)
                                        ? $container->tt_value * $container->tt_exchange_rate : 0;
                                        $ttTotal = $ttAmount * ($container->qty ?? 0);
                                        @endphp
                                        {{ $ttTotal ? number_format($ttTotal, 2) : '-' }}
                                    </td>

                                    {{-- Grand Total --}}
                                    <td>
                                        @php
                                        $grandTotal = $lcTotal + $ttTotal;
                                        @endphp
                                        {{ $grandTotal ? number_format($grandTotal, 2) : '-' }}
                                    </td>

                                    <td>{{ $container->shipping_date ?? '-' }}</td>
                                    <td>{{ $container->arriving_date ?? '-' }}</td>
                                 <td>
    @switch($container->status)
        @case(0)
            <span class="badge bg-warning text-light">Pending</span>
            @break

        @case(1)
            <span class="badge bg-primary  text-light">Shipped</span>
            @break

        @case(2)
            <span class="badge bg-success  text-light">Arrived</span>
            @break

        @case(3)
            <span class="badge bg-info  text-light">Upcoming</span>
            @break

        @default
            <span class="badge bg-secondary">-</span>
    @endswitch
</td>

                                    <td>
                                        <!-- Edit Button triggers modal -->
                                        <button type="button" class="btn btn-sm btn-info mb-1" data-bs-toggle="modal" data-bs-target="#editContainerModal{{ $container->id }}">
                                            Edit
                                        </button>

                                        <!-- Delete Form -->
                                        <!--<form action="{{ route('container.delete', $container->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this container?');">-->
                                        <!--    @csrf-->
                                        <!--    @method('DELETE')-->
                                        <!--    <button type="submit" class="btn btn-sm btn-danger">Delete</button>-->
                                        <!--</form>-->
                                    </td>
                                </tr>

                                <!-- Edit Container Modal -->
                                <div class="modal fade" id="editContainerModal{{ $container->id }}" tabindex="-1" aria-labelledby="editContainerLabel{{ $container->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editContainerLabel{{ $container->id }}">Edit Container</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="border: none;background-color: white;">✖</button>
                                            </div>
                                            <form action="{{ route('container.update', $container->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <label>LC Name</label>
                                                            <input type="text" class="form-control" value="{{ $container->lc->lc_name ?? '-' }}" readonly>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label>Container Name</label>
                                                            <input type="text" class="form-control" name="name" value="{{ $container->name }}" required>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <label>Container Number</label>
                                                            <input type="text" class="form-control" name="number" value="{{ $container->number }}" required>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label>Shipping Date</label>
                                                            <input type="date" class="form-control" name="shipping_date" value="{{ $container->shipping_date }}">
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <label>Arriving Date</label>
                                                            <input type="date" class="form-control" name="arriving_date" value="{{ $container->arriving_date }}">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label>Status</label>
                                                            <select name="status" class="form-control" required>
                                                                <option value="0" {{ $container->status == 0 ? 'selected' : '' }}>Pending</option>
                                                                <option value="1" {{ $container->status == 1 ? 'selected' : '' }}>Shipped</option>
                                                                <option value="2" {{ $container->status == 2 ? 'selected' : '' }}>Arrived</option>
                                                                <option value="3" {{ $container->status == 3 ? 'selected' : '' }}>Upcoming</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted">No containers found.</td>
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
                           Container
                        </th>
                     </tr>`;
        excelContent += `<tr></tr><tr></tr>`;

        rows.forEach((row, rowIndex) => {
            let cells = row.querySelectorAll("th, td");
            excelContent += "<tr>";

            cells.forEach((cell, colIndex) => {
                if (colIndex >= cells.length - 1) return;
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
        let filename = `Container_${day}_${month}_${year}_.xls`;

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

    @if(session('warning'))
        toastr.warning("{{ session('warning') }}");
    @endif

    @if($errors->any())
        @foreach($errors->all() as $error)
            toastr.error("{{ $error }}");
        @endforeach
    @endif
</script>
@endsection
