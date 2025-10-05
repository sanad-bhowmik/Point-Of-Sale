@extends('layouts.app')

@section('title', 'Products')

@section('third_party_stylesheets')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
@endsection

@section('breadcrumb')
<ol class="breadcrumb border-0 m-0">
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
    <li class="breadcrumb-item active">Products</li>
</ol>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <!-- Left: Add Product Button -->
                        <div>
                            <a href="{{ route('products.create') }}" class="btn btn-primary">
                                Add Product <i class="bi bi-plus"></i>
                            </a>
                        </div>

                        <!-- Right: DataTable Buttons -->
                        <div>
                            <button class="btn btn-secondary buttons-excel" onclick="downloadTableAsExcel()">
                                <i class="bi bi-file-earmark-excel-fill"></i> Excel
                            </button>
                        </div>
                    </div>

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
@endpush
<script>
    function downloadTableAsExcel() {
        let table = document.querySelector("table");
        let rows = table.querySelectorAll("tr");

        let excelContent = "<table border='1' style='border-collapse:collapse;'>";

        // Calculate number of columns for the title (excluding first and last)
        let totalCols = rows[0].querySelectorAll("th, td").length - 2;

        // Title row
        excelContent += `<tr>
                        <th colspan="${totalCols}" rowspan="3"
                            style="text-align:center; vertical-align:middle; font-size:28px; font-weight:bold; padding:15px;">
                            Category
                        </th>
                     </tr>`;
        excelContent += `<tr></tr><tr></tr>`;

        // Loop through table rows
        rows.forEach((row, rowIndex) => {
            let cells = row.querySelectorAll("th, td");
            excelContent += "<tr>";

            cells.forEach((cell, colIndex) => {
                // Skip first and last column
                if (colIndex === 0 || colIndex === cells.length - 1) return;

                let tag = (rowIndex === 0) ? "th" : "td";

                // Remove Taka sign "৳" and trim
                let cellText = cell.innerText.replace(/৳/g, '').trim();

                excelContent += `<${tag} style="padding:5px;">${cellText}</${tag}>`;
            });

            excelContent += "</tr>";
        });

        excelContent += "</table>";

        // Generate filename with current date
        let today = new Date();
        let day = String(today.getDate()).padStart(2, '0');
        let month = String(today.getMonth() + 1).padStart(2, '0');
        let year = today.getFullYear();
        let filename = `Category_${day}_${month}_${year}_.xls`;

        // Create download link and trigger download
        let downloadLink = document.createElement("a");
        downloadLink.href = 'data:application/vnd.ms-excel,' + encodeURIComponent(excelContent);
        downloadLink.download = filename;
        downloadLink.click();
    }
</script>
