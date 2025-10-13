@extends('layouts.app')

@section('title', 'Parties Payment List')

@section('breadcrumb')
<ol class="breadcrumb border-0 m-0">
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
    <li class="breadcrumb-item active">Parties Payment List</li>
</ol>
@endsection

@section('content')
<div class="container-fluid mb-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Parties Payment Report</h3>
                    <a href="{{ route('partiesPayment.index') }}" class="btn btn-primary">+ Add New Payment</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th class="text-center">SL</th>
                                    <th class="text-center">Date</th>
                                    <th class="text-center">Party Name</th>
                                    <th class="text-center">USD Amount</th>
                                    <th class="text-center">Exchange Rate</th>
                                    <th class="text-center">Amount (Tk)</th>
                                    <th class="text-center">Description</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $serialNumber = 1; @endphp
                                @forelse($payments as $payment)
                                    <tr>
                                        <td class="text-center">{{ $serialNumber++ }}</td>
                                        <td>{{ \Carbon\Carbon::parse($payment->date)->format('d/m/Y') }}</td>
                                        <td>{{ $payment->name }}</td>
                                        <td class="text-end">{{ number_format($payment->usd_amount, 2) }}</td>
                                        <td class="text-end">{{ number_format($payment->exchange_rate, 4) }}</td>
                                        <td class="text-end">{{ number_format($payment->amount, 2) }}</td>
                                        <td>{{ $payment->description ?? '-' }}</td>
                                        <td class="text-center">
                                            <!-- Edit Button -->
                                            <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editPaymentModal"
                                                    data-id="{{ $payment->id }}"
                                                    data-date="{{ \Carbon\Carbon::parse($payment->date)->format('Y-m-d') }}"
                                                    data-name="{{ $payment->name }}"
                                                    data-usd-amount="{{ $payment->usd_amount }}"
                                                    data-exchange-rate="{{ $payment->exchange_rate }}"
                                                    data-amount="{{ $payment->amount }}"
                                                    data-description="{{ $payment->description ?? '' }}">
                                                Edit
                                            </button>

                                            <!-- Delete Form -->
                                            <form action="{{ route('partiesPayment.delete', $payment->id) }}" method="POST" style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">No payments found.</td>
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

<!-- Edit Payment Modal -->
<div class="modal fade" id="editPaymentModal" tabindex="-1" aria-labelledby="editPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="editPaymentForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editPaymentModalLabel">Edit Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_date" class="form-label">Date</label>
                            <input type="date" class="form-control" id="edit_date" name="date" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_name" class="form-label">Party Name</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_usd_amount" class="form-label">USD Amount</label>
                            <input type="number" step="0.01" class="form-control" id="edit_usd_amount" name="usd_amount" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_exchange_rate" class="form-label">Exchange Rate</label>
                            <input type="number" step="0.0001" class="form-control" id="edit_exchange_rate" name="exchange_rate" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_amount" class="form-label">Amount (Tk)</label>
                            <input type="number" step="0.01" class="form-control" id="edit_amount" name="amount" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_description" class="form-label">Description</label>
                            <textarea class="form-control" id="edit_description" name="description" rows="1"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- CSS & JS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Toastr notifications
    toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: "toast-top-right",
        timeOut: "5000"
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

    // Edit Payment Modal Script
    document.addEventListener('DOMContentLoaded', function() {
        const editModal = document.getElementById('editPaymentModal');

        if(editModal) {
            editModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;

                // Get data attributes
                const id = button.getAttribute('data-id');
                const date = button.getAttribute('data-date');
                const name = button.getAttribute('data-name');
                const usd = button.getAttribute('data-usd-amount');
                const rate = button.getAttribute('data-exchange-rate');
                const amount = button.getAttribute('data-amount');
                const description = button.getAttribute('data-description');

                // Set form action
                const form = editModal.querySelector('#editPaymentForm');
                form.action = `/parties-payment/${id}/update`;

                // Populate fields
                editModal.querySelector('#edit_date').value = date;
                editModal.querySelector('#edit_name').value = name;
                editModal.querySelector('#edit_usd_amount').value = usd;
                editModal.querySelector('#edit_exchange_rate').value = rate;
                editModal.querySelector('#edit_amount').value = amount;
                editModal.querySelector('#edit_description').value = description;
            });
        }
    });
</script>

<style>
    .table th {
        font-weight: 600;
        font-size: 0.875rem;
    }
    .table td {
        vertical-align: middle;
    }
    .table-responsive {
        border-radius: 0.375rem;
    }
</style>
@endsection
