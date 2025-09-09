@extends('layouts.app')

@section('title', 'Create Transaction')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('transaction.index') }}">Transactions</a></li>
        <li class="breadcrumb-item active">Add</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid mb-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm rounded-3">
                    <div class="card-body">
                        <form action="{{ route('transaction.store') }}" method="POST">
                            @csrf

                            <div class="row">
                                <!-- Bank Select -->
                                <div class="col-md-6 mb-3">
                                    <label for="bank_id" class="form-label">Bank</label>
                                    <select name="bank_id" id="bank_id" class="form-control" required>
                                        <option value="">Select Bank</option>
                                        @foreach ($banks as $bank)
                                            <option value="{{ $bank->id }}">{{ $bank->bank_name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Transaction Type -->
                                <div class="col-md-6 mb-3">
                                    <label for="transaction_type" class="form-label">Transaction Type</label>
                                    <select id="transaction_type" class="form-control" required>
                                        <option value="">Select Type</option>
                                        <option value="in">In Amount</option>
                                        <option value="out">Out Amount</option>
                                    </select>
                                </div>

                                <!-- In Amount -->
                                <div class="col-md-6 mb-3" id="in_amount_div" style="display: none;">
                                    <label for="in_amount" class="form-label">In Amount</label>
                                    <input type="number" name="in_amount" id="in_amount" class="form-control"
                                        step="0.01" value="0">
                                </div>

                                <!-- Out Amount -->
                                <div class="col-md-6 mb-3" id="out_amount_div" style="display: none;">
                                    <label for="out_amount" class="form-label">Out Amount</label>
                                    <input type="number" name="out_amount" id="out_amount" class="form-control"
                                        step="0.01" value="0">
                                </div>

                                <!-- Purpose -->
                                <div class="col-md-6 mb-3">
                                    <label for="purpose" class="form-label">Purpose</label>
                                    <textarea name="purpose" id="purpose" class="form-control" rows="4" placeholder="Enter purpose of transaction"
                                        required></textarea>
                                </div>

                                <!-- Date -->
                                <div class="col-md-6 mb-3">
                                    <label for="date" class="form-label">Posting Date</label>
                                    <input type="date" name="date" id="date" class="form-control"
                                        value="{{ date('Y-m-d') }}" required>
                                </div>
                            </div>

                            <!-- SAVE BUTTON -->
                            <div class="mt-3 text-end">
                                <button type="submit" class="btn btn-primary px-4">
                                    Save Transaction <i class="bi bi-check"></i>
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<div id="toast-container" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        function showToast(message, type = 'success', duration = 3000) {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            toast.classList.add('toast', type);
            toast.textContent = message;
            container.appendChild(toast);

            // Trigger animation
            setTimeout(() => toast.classList.add('show'), 100);

            // Remove after duration
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 500);
            }, duration);
        }

        // Laravel session success
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
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const transactionType = document.getElementById('transaction_type');
        const inDiv = document.getElementById('in_amount_div');
        const outDiv = document.getElementById('out_amount_div');
        const inInput = document.getElementById('in_amount');
        const outInput = document.getElementById('out_amount');

        transactionType.addEventListener('change', function() {
            if (this.value === 'in') {
                inDiv.style.display = 'block';
                outDiv.style.display = 'none';
                outInput.value = 0;
            } else if (this.value === 'out') {
                outDiv.style.display = 'block';
                inDiv.style.display = 'none';
                inInput.value = 0;
            } else {
                inDiv.style.display = 'none';
                outDiv.style.display = 'none';
                inInput.value = 0;
                outInput.value = 0;
            }
        });
    });
</script>

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
</style>
