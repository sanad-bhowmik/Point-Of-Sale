@extends('layouts.app')

@section('title', 'Customers')

@section('third_party_stylesheets')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap4.min.css">
@endsection

@section('breadcrumb')
<ol class="breadcrumb border-0 m-0">
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
    <li class="breadcrumb-item active">Customers</li>
</ol>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <a href="{{ route('customers.create') }}" class="btn btn-primary">
                            Add Customer <i class="bi bi-plus"></i>
                        </a>

                        <button id="exportExcel" class="btn btn-success">
                            <i class="bi bi-file-earmark-excel-fill"></i> Download Excel
                        </button>
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
{{-- DataTables core --}}
{!! $dataTable->scripts() !!}

{{-- DataTables Buttons + JSZip (for Excel export) --}}
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<script>
    $(document).ready(function() {
        const table = $('#customers-table').DataTable();

        $('#exportExcel').on('click', function() {
            // Create temporary export button configuration
            new $.fn.dataTable.Buttons(table, {
                buttons: [{
                    extend: 'excelHtml5',
                    title: 'Customers_List_' + new Date().toISOString().slice(0, 10),
                    exportOptions: {
                        // ✅ Exclude last two columns (action and created_at)
                        columns: [0, 1, 2]
                    }
                }]
            });

            // Trigger the Excel export
            table.buttons(0, 0).trigger();
        });
    });
</script>
@endpush
