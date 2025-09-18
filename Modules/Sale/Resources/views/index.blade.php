@extends('layouts.app')

@section('title', 'Sales')

@section('third_party_stylesheets')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endsection

@section('breadcrumb')
<ol class="breadcrumb border-0 m-0">
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
    <li class="breadcrumb-item active">Sales</li>
</ol>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <!-- Header Row with Add Sale, Date Range, and Excel -->
                    <div class="row mb-3">
                        <div class="col-md-4 d-flex align-items-center">
                            <a href="{{ route('sales.create') }}" class="btn btn-primary">
                                Add Sale <i class="bi bi-plus"></i>
                            </a>
                        </div>

                        <div class="col-md-4 d-flex justify-content-center">
                            <div class="input-group shadow-sm" style="max-width: 300px; border-radius: 6px; overflow: hidden;">
                                <input type="date" id="filterDate" class="form-control border-start-0" placeholder="Select Date">
                                <button class="btn btn-primary" id="filterDateBtn" style="border-radius: 0 6px 6px 0;">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </div>


                        <div class="col-md-4 d-flex justify-content-end">
                            <button class="btn btn-secondary buttons-excel" onclick="downloadTableAsExcel()">
                                <i class="bi bi-file-earmark-excel-fill"></i> Excel
                            </button>
                        </div>
                    </div>

                    <hr>

                    <div class="table-responsive">
                        {!! $dataTable->table() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('page_scripts')
{!! $dataTable->scripts() !!}
<script src="https://cdn.jsdelivr.net/npm/moment/min/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script>
    $(function() {
        let table = $('#sales-table').DataTable();

        $('#filterDateBtn').on('click', function() {
            let selectedDate = $('#filterDate').val();
            table.ajax.url("{!! route('sales.index') !!}?date=" + selectedDate).load();
        });

        $('#filterDate').on('change', function() {
            // Optionally auto-trigger search on date change
            // $('#filterDateBtn').click();
        });
    });
</script>

<script>
    function downloadTableAsExcel() {
        let table = document.querySelector("table");
        let rows = table.querySelectorAll("tr");

        let excelContent = "<table border='1' style='border-collapse:collapse; font-family: Arial, sans-serif;'>";

        // Add title row
        let totalCols = rows[0].querySelectorAll("th, td").length - 1; // Skip last column
        excelContent += `<tr>
        <th colspan="${totalCols}"
            style="text-align:center; vertical-align:middle; font-size:24px; font-weight:bold; padding:10px; background-color:#4CAF50; color:white;">
            Sales Report
        </th>
    </tr>`;

        // Empty row for spacing
        excelContent += `<tr><td colspan="${totalCols}"></td></tr>`;

        rows.forEach((row, rowIndex) => {
            let cells = row.querySelectorAll("th, td");
            excelContent += "<tr>";

            cells.forEach((cell, colIndex) => {
                if (colIndex === cells.length - 1) return; // Skip last column

                let cellText = cell.innerText.trim();

                // Remove TK sign or any other currency symbol
                cellText = cellText.replace(/৳/g, '').trim();

                let tag = (rowIndex === 0) ? "th" : "td";

                // Apply basic styling
                let style = "padding:5px; text-align:center; border:1px solid #999;";
                if (rowIndex === 0) {
                    style += " background-color:#f2f2f2; font-weight:bold;";
                }

                excelContent += `<${tag} style="${style}">${cellText}</${tag}>`;
            });

            excelContent += "</tr>";
        });

        excelContent += "</table>";

        let today = new Date();
        let day = String(today.getDate()).padStart(2, '0');
        let month = String(today.getMonth() + 1).padStart(2, '0');
        let year = today.getFullYear();
        let filename = `Sales_report_${day}_${month}_${year}.xls`;

        let downloadLink = document.createElement("a");
        downloadLink.href = 'data:application/vnd.ms-excel,' + encodeURIComponent(excelContent);
        downloadLink.download = filename;
        downloadLink.click();
    }
</script>
@endpush
