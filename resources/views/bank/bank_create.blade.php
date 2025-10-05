@extends('layouts.app')

@section('title', 'Create Bank')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('bank.index') }}">Banks</a></li>
        <li class="breadcrumb-item active">Add</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid mb-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm rounded-3">
                    <div class="card-body">
                        <form action="{{ route('bank.store') }}" method="POST">
                            @csrf

                            <div class="row">
                                <!-- Institution -->
                                <div class="col-md-6 mb-3">
                                    <label for="institution" class="form-label">Institution <span class="text-danger">*</span></label>
                                    <input type="text" name="institution" id="institution" class="form-control" required>
                                </div>

                                <!-- Account No -->
                                <div class="col-md-6 mb-3">
                                    <label for="account_no" class="form-label">Account No <span class="text-danger">*</span></label>
                                    <input type="text" name="account_no" id="account_no" class="form-control" required>
                                </div>

                                <!-- Bank Name -->
                                <div class="col-md-6 mb-3">
                                    <label for="bank_name" class="form-label">Bank Name <span class="text-danger">*</span></label>
                                    <input type="text" name="bank_name" id="bank_name" class="form-control" required>
                                </div>

                                <!-- Branch Name -->
                                <div class="col-md-6 mb-3">
                                    <label for="branch_name" class="form-label">Branch Name <span class="text-danger">*</span></label>
                                    <input type="text" name="branch_name" id="branch_name" class="form-control" required>
                                </div>

                                <!-- Owner -->
                                <div class="col-md-6 mb-3">
                                    <label for="owner" class="form-label">Owner <span class="text-danger">*</span></label>
                                    <input type="text" name="owner" id="owner" class="form-control" required>
                                </div>

                                <!-- Date -->
                                <div class="col-md-6 mb-3">
                                    <label for="date" class="form-label">Date <span class="text-danger">*</span></label>
                                    <input type="date" name="date" id="date" class="form-control" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="opening_balance" class="form-label">Opening Balance <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" name="opening_balance" id="opening_balance"
                                        class="form-control" required>
                                </div>

                                <!-- Disclaimer -->
                                <div class="col-md-6 mb-3">
                                    <label for="disclaimer" class="form-label">Disclaimer <span class="text-danger">*</span></label>
                                    <input type="text" name="disclaimer" id="disclaimer" class="form-control">
                                </div>
                            </div>

                            <!-- SAVE BUTTON -->
                            <div class="mt-3 text-end">
                                <button type="submit" class="btn btn-primary px-4">
                                    Save Bank <i class="bi bi-check"></i>
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
