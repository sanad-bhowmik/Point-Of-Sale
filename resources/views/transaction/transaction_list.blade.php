@extends('layouts.app')

@section('title', 'View Transactions')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item active">Transaction List</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid mb-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm rounded-3">
                    <div class="card-body">
                        <h5 class="mb-3 border-bottom pb-2">Transaction List</h5>

                        <form action="{{ route('transaction.index') }}" method="GET" class="row mb-3">
                            <div class="col-md-4">
                                <label for="date_range" class="form-label">Select Date Range</label>
                                <div class="input-group">
                                    <input type="text" name="date_range" id="date_range" class="form-control"
                                        placeholder="Select date range" value="{{ request('date_range') }}">
                                    <span class="input-group-text"><i class="bi bi-calendar-event"></i></span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="transaction_type" class="form-label">Transaction Type</label>
                                <select name="transaction_type" id="transaction_type" class="form-control">
                                    <option value="">All</option>
                                    <option value="in" {{ request('transaction_type') == 'in' ? 'selected' : '' }}>In
                                        Amount</option>
                                    <option value="out" {{ request('transaction_type') == 'out' ? 'selected' : '' }}>Out
                                        Amount</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="">All</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending
                                    </option>
                                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>
                                        Approved
                                    </option>
                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>
                                        Rejected
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary mr-2">Filter</button>
                                <a href="{{ route('transaction.index') }}" class="btn btn-secondary">Reset</a>
                            </div>
                        </form>

                        <div>
                            <a href="{{ route('transaction.create') }}" class="btn btn-primary mb-3">Add New Transaction</a>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Bank</th>
                                        <th>In Amount</th>
                                        <th>Out Amount</th>
                                        <th>Purpose</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($transactions as $index => $transaction)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $transaction->bank->bank_name ?? 'N/A' }}</td>
                                            <td>{{ number_format($transaction->in_amount, 2) }}</td>
                                            <td>{{ number_format($transaction->out_amount, 2) }}</td>
                                            <td>{{ $transaction->purpose }}</td>
                                            <td>
                                                <button
                                                    class="btn btn-sm
                                                    @if ($transaction->status == 'pending') btn-warning
                                                    @elseif($transaction->status == 'approved') btn-success
                                                    @else btn-danger @endif"
                                                    onclick="openStatusModal({{ $transaction->id }}, '{{ $transaction->status }}')">
                                                    {{ ucfirst($transaction->status) }}
                                                    <i class="bi bi-pencil-square ms-1"></i>
                                                </button>
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($transaction->date)->format('d-m-Y') }}</td>
                                            <td>
                                                <a href="{{ route('transaction.edit', $transaction->id) }}"
                                                    class="btn btn-sm btn-warning">Edit</a>
                                                @if ($transaction->status != 'approved')
                                                    <form action="{{ route('transaction.destroy', $transaction->id) }}"
                                                        method="POST" class="d-block mt-1">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-sm btn-danger"
                                                            onclick="return confirm('Are you sure?')">Delete</button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center">No Transactions found.</td>
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

    <!-- Custom Toast Container -->
    <div id="toast-container" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>

    <!-- Single Status Modal -->
    <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered custom-modal">
            <form id="statusForm" action="" class="w-100" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="statusModalLabel">Update Transaction Status</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <select name="status" id="statusSelect" class="form-control" required>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Status</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function showToast(message, type = 'success', duration = 3000) {
                const container = document.getElementById('toast-container');
                const toast = document.createElement('div');
                toast.classList.add('toast', type);
                toast.textContent = message;
                container.appendChild(toast);

                setTimeout(() => toast.classList.add('show'), 100);
                setTimeout(() => {
                    toast.classList.remove('show');
                    setTimeout(() => toast.remove(), 500);
                }, duration);
            }

            @if (session('success'))
                showToast(@json(session('success')), 'success');
            @endif

            // Laravel validation errors
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    showToast(@json($error), 'error');
                @endforeach
            @endif

        });


        //  @if (session('success'))
        //     showToast(@json(session('success')), 'success');
        // @endif

        // // Laravel validation errors
        // @if ($errors->any())
        //     @foreach ($errors->all() as $error)
        //         showToast(@json($error), 'error');
        //     @endforeach
        // @endif

        // });
    </script>

@endsection

@push('page_scripts')
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            $('#date_range').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    format: 'YYYY-MM-DD',
                    cancelLabel: 'Clear'
                }
            });

            $('#date_range').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD') + ' to ' + picker.endDate.format(
                    'YYYY-MM-DD'));
            });

            $('#date_range').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });
        });
    </script>

    <script>
        function openStatusModal(transactionId, currentStatus) {
            // Set form action dynamically
            const form = document.getElementById('statusForm');
            form.action = `/transactions/${transactionId}/status`;

            // Set current status selected
            const select = document.getElementById('statusSelect');
            select.value = currentStatus;

            // Show Bootstrap modal
            const statusModal = new bootstrap.Modal(document.getElementById('statusModal'));
            statusModal.show();
        }
    </script>
@endpush

@push('page_css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endpush

<style>
    .toast {
        background-color: #333;
        color: #fff;
        padding: 12px 20px;
        margin-bottom: 10px;
        border-radius: 5px;
        box-shadow: 0px 3px 6px rgba(0, 0, 0, 0.2);
        opacity: 0;
        transform: translateX(100%);
        transition: all 0.5s ease;
        min-width: 250px;
        font-family: sans-serif;
    }

    .toast.show {
        opacity: 1;
        transform: translateX(0);
    }

    .toast.success {
        background-color: #28a745;
    }

    .toast.error {
        background-color: #dc3545;
    }

    .custom-modal {
        max-width: 800px;
    }

    .custom-modal .modal-content {
        height: 220px;
    }
</style>
