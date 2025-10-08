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

                    {{-- Controls --}}
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        {{-- Supplier dropdown and search/clear buttons --}}
                        <div class="d-flex align-items-center gap-2">
                            <select id="supplierDropdown" class="form-select" style="width: 250px;height: 29px;border: 1px solid #ebe2e2;">
                                <option value="" selected disabled>Select Supplier</option>
                                @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ (request('supplier_id') == $supplier->id) ? 'selected' : '' }}>
                                    {{ $supplier->supplier_name }}
                                </option>
                                @endforeach
                            </select>

                            <button id="searchBtn" class="btn btn-primary btn-sm ml-2">Search</button>
                            <button id="clearBtn" class="btn btn-secondary btn-sm ml-2">Clear</button>
                        </div>

                        {{-- Excel button --}}
                        <button class="btn btn-secondary btn-sm" onclick="downloadTableAsExcel()">
                            <i class="bi bi-file-earmark-excel-fill"></i> Excel
                        </button>
                    </div>

                    {{-- Table --}}
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="containerTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>LC Name</th>
                                    <th>LC Number</th>
                                    <th>Container Name</th>
                                    <th>Container Number</th>
                                    <th>Supplier Name</th>
                                    <th>LC Total</th>
                                    <th>TT Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(request()->has('supplier_id') && isset($containers) && $containers->count() > 0)
                                @php
                                $grandLcTotal = 0;
                                $grandTtTotal = 0;
                                @endphp

                                @foreach($containers as $index => $container)
                                @php
                                $lcTotal = ($container->lc_value ?? 0) * ($container->lc_exchange_rate ?? 0) * ($container->qty ?? 0);
                                $ttTotal = ($container->tt_value ?? 0) * ($container->tt_exchange_rate ?? 0) * ($container->qty ?? 0);
                                $grandLcTotal += $lcTotal;
                                $grandTtTotal += $ttTotal;
                                @endphp
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $container->lc->lc_name ?? '-' }}</td>
                                    <td>{{ $container->lc->lc_number ?? '-' }}</td>
                                    <td>{{ $container->name ?? '-' }}</td>
                                    <td>{{ $container->number ?? '-' }}</td>
                                    <td>{{ $container->lc->costing->supplier->supplier_name ?? '-' }}</td>
                                    <td>{{ number_format($lcTotal, 2) }}</td>
                                    <td>{{ number_format($ttTotal, 2) }}</td>
                                </tr>
                                @endforeach

                                <tr style="background:#f1f1f1;font-weight:bold;">
                                    <td colspan="6" class="text-start">Grand Total:</td>
                                    <td>{{ number_format($grandLcTotal, 2) }}</td>
                                    <td>{{ number_format($grandTtTotal, 2) }}</td>
                                </tr>
                                @else
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-3">
                                        No data available. Select a supplier and click "Search".
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- jQuery & Toastr -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    $(document).ready(function() {
        $('#searchBtn').on('click', function() {
            let supplierId = $('#supplierDropdown').val();
            if (!supplierId) {
                toastr.warning("Please select a supplier");
                return;
            }
            let url = '{{ route("container.supplierTtLc") }}';
            window.location.href = url + '?supplier_id=' + supplierId;
        });

        $('#clearBtn').on('click', function() {
            $('#supplierDropdown').val('');
            window.location.href = '{{ route("container.supplierTtLc") }}';
        });
    });

  function downloadTableAsExcel() {
    let table = document.querySelector("#containerTable");
    if (!table) return;

    let rows = table.querySelectorAll("tr");

    let hasData = false;
    rows.forEach(row => {
        if (!row.querySelector('td.text-center.text-muted')) hasData = true;
    });
    if (!hasData) {
        toastr.warning("No data available to export");
        return;
    }

    let excelContent = `
    <table border="1" style="border-collapse:collapse; font-family:Calibri, sans-serif; font-size:14px;">
    `;

    // Header
    excelContent += "<tr>";
    table.querySelectorAll("thead tr th").forEach((cell, index) => {
        if (index === 0) return; // Skip SI column if desired
        excelContent += `
        <th style="
            padding:10px;
            background-color:#4472C4;
            color:#ffffff;
            font-weight:bold;
            text-align:center;
            border:1px solid #999;
        ">
            ${cell.innerText.trim()}
        </th>`;
    });
    excelContent += "</tr>";

    // Body
    table.querySelectorAll("tbody tr").forEach(row => {
        if (row.querySelector('td.text-center.text-muted')) return;
        if (row.classList.contains('grand-total-row')) return;

        excelContent += "<tr>";
        row.querySelectorAll("td").forEach((cell, index) => {
            if (index === 0) return; // skip SI
            let value = cell.innerText.trim();
            if (!isNaN(value.replace(/,/g, '')) && value !== "") {
                value = value.replace(/,/g, '');
                excelContent += `<td style="padding:8px; text-align:right; border:1px solid #ccc;">
                    ${parseFloat(value).toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2})}
                </td>`;
            } else {
                excelContent += `<td style="padding:8px; text-align:left; border:1px solid #ccc;">${value}</td>`;
            }
        });
        excelContent += "</tr>";
    });

    // Grand Total
    let grandTotalRow = table.querySelector(".grand-total-row");
    if (grandTotalRow) {
        excelContent += `<tr style="background-color:#E2EFDA; font-weight:bold;">
            <td colspan="6" style="text-align:left; padding:8px; border:1px solid #999; background-color:#D9E1F2; font-weight:bold;">Grand Total:</td>`;

        grandTotalRow.querySelectorAll("td").forEach((td, index) => {
            if (index < 6) return; // Skip first 6 cells
            let value = td.innerText.trim();
            excelContent += `<td style="padding:8px; text-align:right; font-weight:bold; background-color:#D9E1F2; border:1px solid #999;">${value}</td>`;
        });
        excelContent += "</tr>";
    }

    excelContent += "</table>";

    let today = new Date();
    let filename = `Supplier_total_LC&TT_${today.getDate()}_${today.getMonth() + 1}_${today.getFullYear()}.xls`;

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
</script>
@endsection
