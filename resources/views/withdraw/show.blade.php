@extends('layouts.app')

@section('title', 'Withdraw Records')

@section('breadcrumb')
<ol class="breadcrumb border-0 m-0">
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
    <li class="breadcrumb-item active">Withdraw Records</li>
</ol>
@endsection

@section('content')
<div class="container-fluid mb-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Withdraw Transactions</h5>
                        <div>
                            <a href="{{ route('withdraw.index') }}" class="btn btn-primary btn-sm me-2">+ New Transaction</a>
                            <button id="exportExcel" class="btn btn-success btn-sm"><i class="bi bi-file-earmark-excel-fill"></i> Export Excel</button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="withdrawTable" class="table table-bordered table-striped align-middle">
                            <thead>
                                <tr>
                                    <th>Si</th>
                                    <th>Cash In Amount</th>
                                    <th>Cash Withdraw Amount</th>
                                    <th>Description</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $totalCashIn = 0;
                                $totalCashOut = 0;
                                @endphp
                                @forelse($withdraws as $index => $withdraw)
                                @php
                                $totalCashIn += $withdraw->cash_in_amount ?? 0;
                                $totalCashOut += $withdraw->cash_withdraw_amount ?? 0;
                                @endphp
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $withdraw->cash_in_amount ?? '-' }}</td>
                                    <td>{{ $withdraw->cash_withdraw_amount ?? '-' }}</td>
                                    <td>{{ $withdraw->description ?? '-' }}</td>
                                    <td>
                                        <!-- Edit Button -->
                                        <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#editModal{{ $withdraw->id }}">
                                            <i class="bi bi-pencil"></i> Edit
                                        </button>

                                        <!-- Delete Form -->
                                        <form action="{{ route('withdraw.delete', $withdraw->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this record?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Edit Modal -->
                                <div class="modal fade" id="editModal{{ $withdraw->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $withdraw->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editModalLabel{{ $withdraw->id }}">Edit Transaction</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('withdraw.update', $withdraw->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="cash_in_amount_{{ $withdraw->id }}" class="form-label">Cash In Amount</label>
                                                        <input type="number" step="0.01" name="cash_in_amount" id="cash_in_amount_{{ $withdraw->id }}" class="form-control" value="{{ $withdraw->cash_in_amount }}">
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="cash_withdraw_amount_{{ $withdraw->id }}" class="form-label">Cash Withdraw Amount</label>
                                                        <input type="number" step="0.01" name="cash_withdraw_amount" id="cash_withdraw_amount_{{ $withdraw->id }}" class="form-control" value="{{ $withdraw->cash_withdraw_amount }}">
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
                                    <td colspan="5" class="text-center text-muted">No transactions found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr class="fw-bold bg-light">
                                    <td>Total</td>
                                    <td>{{ number_format($totalCashIn, 2) }}</td>
                                    <td>{{ number_format($totalCashOut, 2) }}</td>
                                    <td colspan="2"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- DataTables, SheetJS, Toastr -->
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function () {
    $('#withdrawTable').DataTable({
        "pageLength": 10,
        "order": [[0, "desc"]]
    });

    // Export table to Excel (excluding Action column)
    $('#exportExcel').on('click', function () {
        const wb = XLSX.utils.book_new();
        const tableClone = $('#withdrawTable').clone();
        tableClone.find('tr').each(function () {
            $(this).find('th:last, td:last').remove();
        });
        const ws = XLSX.utils.table_to_sheet(tableClone[0]);
        XLSX.utils.book_append_sheet(wb, ws, "Withdraws");
        XLSX.writeFile(wb, "Withdraw_Records.xlsx");
    });
});

// Toastr notifications
toastr.options = {
    "closeButton": true,
    "progressBar": true,
    "positionClass": "toast-top-right",
    "timeOut": "4000"
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
