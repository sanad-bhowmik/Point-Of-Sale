@extends('layouts.app')

@section('title', 'Bank Ledger')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item active">Bank Ledger</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid mb-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm rounded-3">
                    <div class="card-body">
                        <h5 class="mb-3 border-bottom pb-2">Bank Ledger</h5>

                        <form action="{{ route('transaction.ledger') }}" method="GET" class="row mb-3 align-items-end">
                            <div class="col-md-3 mb-3">
                                <label for="bank_id" class="form-label">Select Bank</label>
                                <select name="bank_id" id="bank_id" class="form-control">
                                    <option value="">Select Bank</option>
                                    @foreach ($banks as $bank)
                                        <option value="{{ $bank->id }}"
                                            {{ request('bank_id') == $bank->id ? 'selected' : '' }}>
                                            {{ $bank->bank_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="date_range" class="form-label">Select Date Range</label>
                                <div class="input-group">
                                    <input type="text" name="date_range" id="date_range" class="form-control"
                                        placeholder="Select date range" value="{{ request('date_range') }}">
                                    <span class="input-group-text"><i class="bi bi-calendar-event"></i></span>
                                </div>
                            </div>

                            <div class="col-md-3 mb-3 d-flex">
                                <button type="submit" class="btn btn-primary mr-2">Filter</button>
                                <a href="{{ route('transaction.ledger') }}" class="btn btn-secondary">Reset</a>
                            </div>
                        </form>
                        @if (isset($selectedBank))
                            <div class="row">
                            <div class="col-mg-4">
                                <h3 class="p-2">Opening Balance : {{ $selectedBank->opening_balance }}</h3>
                            </div>
                        </div>
                        @endif
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>In Amount</th>
                                        <th>Out Amount</th>
                                        <th>Total Amount</th>
                                        <th>Purpose</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($transactions as $index => $transaction)
                                        @php
                                            // Initialize ledger balance for this bank if not set
                                            if (!isset($ledger[$transaction->bank_id])) {
                                                $ledger[$transaction->bank_id] = $transaction->bank->opening_balance ?? 0;
                                            }

                                            // Update ledger balance for this transaction
                                            $ledger[$transaction->bank_id] +=
                                                $transaction->in_amount - $transaction->out_amount;
                                        @endphp
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ \Carbon\Carbon::parse($transaction->date)->format('d-m-Y') }}</td>
                                            <td>{{ number_format($transaction->in_amount, 2) }}</td>
                                            <td>{{ number_format($transaction->out_amount, 2) }}</td>
                                            <td>{{ number_format($ledger[$transaction->bank_id], 2) }}</td>
                                            <td>{{ $transaction->purpose }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">No Transactions found.</td>
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
@endsection

@push('page_scripts')
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            $('#date_range').daterangepicker({
                autoUpdateInput: true,
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
@endpush

@push('page_css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endpush