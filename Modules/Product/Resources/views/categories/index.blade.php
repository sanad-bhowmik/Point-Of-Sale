@extends('layouts.app')

@section('title', 'Product Categories')

@section('third_party_stylesheets')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
@endsection

@section('breadcrumb')
<ol class="breadcrumb border-0 m-0">
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></li>
    <li class="breadcrumb-item active">Categories</li>
</ol>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            @include('utils.alerts')
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5>Product Categories</h5>
                        <div>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#categoryCreateModal">
                                Add Category <i class="bi bi-plus"></i>
                            </button>
                            <button type="button" class="btn btn-secondary ml-2" id="exportExcelBtn">
                                <i class="bi bi-file-earmark-excel"></i> Export to Excel
                            </button>
                        </div>
                    </div>

                    <hr>

                    <div class="table-responsive">
                        {!! $dataTable->table(['class' => 'table table-bordered table-striped table-hover']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Modal -->
@include('product::includes.category-modal')
@endsection

@push('page_scripts')
<!-- SheetJS Library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
{!! $dataTable->scripts() !!}

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const exportExcelBtn = document.getElementById('exportExcelBtn');

        exportExcelBtn.addEventListener('click', function() {
            exportToExcel();
        });

        function exportToExcel() {
            // Show loading state
            const originalHTML = exportExcelBtn.innerHTML;
            exportExcelBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Exporting...';
            exportExcelBtn.disabled = true;

            try {
                const table = $('#product-categories-table').DataTable();
                const data = table.rows({
                    search: 'applied'
                }).data().toArray();

                console.log('Data found:', data.length, 'rows');

                const excelData = [
                    ['Category Code', 'Category Name'] // Headers only for visible columns
                ];

                // Add data rows
                data.forEach(row => {
                    excelData.push([
                        row.category_code || '',
                        row.category_name || ''
                    ]);
                });

                console.log('Excel data prepared:', excelData);

                // Create workbook and worksheet
                const ws = XLSX.utils.aoa_to_sheet(excelData);

                // Auto-size columns
                const colWidths = [{
                        wch: 20
                    }, // Category Code
                    {
                        wch: 30
                    } // Category Name
                ];
                ws['!cols'] = colWidths;

                const wb = XLSX.utils.book_new();
                XLSX.utils.book_append_sheet(wb, ws, 'Product Categories');

                // Generate Excel file and download
                const fileName = 'product_categories_' + new Date().toISOString().slice(0, 10) + '.xlsx';
                XLSX.writeFile(wb, fileName);

            } catch (error) {
                console.error('Error exporting to Excel:', error);
                alert('Error exporting data to Excel. Please check console for details.');
            } finally {
                // Reset button state
                exportExcelBtn.innerHTML = originalHTML;
                exportExcelBtn.disabled = false;
            }
        }
    });
</script>
@endpush
