@extends('layouts.app')

@section('title', 'Create Bank')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('bank.index') }}">Banks</a></li>
        <li class="breadcrumb-item active">Update</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid mb-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm rounded-3">
                    <div class="card-body">
                        <form action="{{ route('transaction.update', $transaction->id) }}" method="POST">
                            @csrf

                            <div class="row">
                                <!-- Bank Select -->
                                <div class="col-md-6 mb-3">
                                    <label for="bank_id" class="form-label">Bank <span class="text-danger">*</span></label>
                                    <select name="bank_id" id="bank_id" class="form-control" required>
                                        <option value="">Select Bank</option>
                                        @foreach ($banks as $bank)
                                            <option value="{{ $bank->id }}"
                                                {{ $transaction->bank_id == $bank->id ? 'selected' : '' }}>
                                                {{ $bank->bank_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Transaction Type -->
                                <div class="col-md-6 mb-3">
                                    <label for="transaction_type" class="form-label">Transaction Type <span class="text-danger">*</span></label>
                                    <select id="transaction_type" class="form-control" required>
                                        <option value="">Select Type</option>
                                        <option value="in" {{ $transaction->in_amount > 0 ? 'selected' : '' }}>In Amount
                                        </option>
                                        <option value="out" {{ $transaction->out_amount > 0 ? 'selected' : '' }}>Out
                                            Amount</option>
                                    </select>
                                </div>

                                <!-- In Amount -->
                                <div class="col-md-6 mb-3" id="in_amount_div"
                                    style="{{ $transaction->in_amount > 0 ? '' : 'display:none;' }}">
                                    <label for="in_amount" class="form-label">In Amount <span class="text-danger">*</span></label>
                                    <input type="number" name="in_amount" id="in_amount" class="form-control"
                                        step="0.01" value="{{ $transaction->in_amount ?? 0 }}">
                                </div>

                                <!-- Out Amount -->
                                <div class="col-md-6 mb-3" id="out_amount_div"
                                    style="{{ $transaction->out_amount > 0 ? '' : 'display:none;' }}">
                                    <label for="out_amount" class="form-label">Out Amount <span class="text-danger">*</span></label>
                                    <input type="number" name="out_amount" id="out_amount" class="form-control"
                                        step="0.01" value="{{ $transaction->out_amount ?? 0 }}">
                                </div>

                                <!-- Purpose -->
                                <div class="col-md-6 mb-3">
                                    <label for="purpose" class="form-label">Purpose</label>
                                    <textarea name="purpose" id="purpose" class="form-control" rows="4" required>{{ $transaction->purpose }}</textarea>
                                </div>

                                <!-- Date -->
                                <div class="col-md-6 mb-3">
                                    <label for="date" class="form-label">Posting Date <span class="text-danger">*</span></label>
                                    <input type="date" name="date" id="date" class="form-control"
                                        value="{{ \Carbon\Carbon::parse($transaction->date)->format('Y-m-d') }}" required>

                                </div>
                            </div>

                            <!-- UPDATE BUTTON -->
                            <div class="mt-3 text-end">
                                <button type="submit" class="btn btn-success px-4">
                                    Update Transaction <i class="bi bi-check2-circle"></i>
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

        // Function to toggle fields
        function toggleFields(type) {
            if (type === 'in') {
                inDiv.style.display = 'block';
                outDiv.style.display = 'none';
                outInput.value = 0;
            } else if (type === 'out') {
                outDiv.style.display = 'block';
                inDiv.style.display = 'none';
                inInput.value = 0;
            } else {
                inDiv.style.display = 'none';
                outDiv.style.display = 'none';
                inInput.value = 0;
                outInput.value = 0;
            }
        }

        // On change event
        transactionType.addEventListener('change', function() {
            toggleFields(this.value);
        });

        // Run once on page load (for edit)
        toggleFields(transactionType.value);
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
