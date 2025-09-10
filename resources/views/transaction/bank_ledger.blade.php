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

                        <div class="row">
                            <div class="col-md-9">
                                <form action="{{ route('transaction.ledger') }}" method="GET"
                                    class="row mb-3 align-items-end">
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
                                                placeholder="Select date range" value="{{ old('date_range', request('date_range')) }}">
                                            <span class="input-group-text"><i class="bi bi-calendar-event"></i></span>
                                        </div>
                                    </div>

                                    <div class="col-md-3 mb-3 d-flex">
                                        <button type="submit" class="btn btn-primary mr-2">Filter</button>
                                        <a href="{{ route('transaction.ledger') }}" class="btn btn-secondary">Reset</a>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-3 d-flex align-items-center">
                                <button id="downloadExcel" class="btn btn-success">Download Excel</button>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table id="downloadExcel" class="table table-bordered table-hover">
                                <thead class="">
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Purpose</th>
                                        <th>In Amount</th>
                                        <th>Out Amount</th>
                                        <th>Balance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            
                                        </td>
                                        <td>{{ $request->date_range ? explode(' to ', $request->date_range)[0] : \Carbon\Carbon::today()->format('d-m-Y') }}</td>
                                        <td colspan="3">
                                            <h5>Opening Balance</h5>
                                        </td>
                                        <td colspan=""><strong>{{ number_format($openingBalance, 2) }}</strong></td>
                                    </tr>
                                    @forelse($transactions as $index => $transaction)
                                        @php
                                            // Initialize ledger balance for this bank if not set
                                            // if (!isset($ledger[$transaction->bank_id])) {
                                            //     $ledger[$transaction->bank_id] =
                                            //         $transaction->bank->opening_balance ?? 0;
                                            // }

                                            // Update ledger balance for this transaction
                                            $ledger[$transaction->bank_id] +=
                                                $transaction->in_amount - $transaction->out_amount;
                                        @endphp
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ \Carbon\Carbon::parse($transaction->date)->format('d-m-Y') }}</td>
                                            <td>{{ $transaction->purpose }}</td>
                                            <td>{{ number_format($transaction->in_amount, 2) }}</td>
                                            <td>{{ number_format($transaction->out_amount, 2) }}</td>
                                            <td>{{ number_format($ledger[$transaction->bank_id], 2) }}</td>
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
    let dateInput = $('#date_range');
    let existingValue = dateInput.val();

    dateInput.daterangepicker({
        autoUpdateInput: true,
        locale: {
            format: 'YYYY-MM-DD',
            cancelLabel: 'Clear'
        },
        startDate: existingValue ? existingValue.split(' to ')[0] : moment(),
        endDate: existingValue ? existingValue.split(' to ')[1] : moment()
    });

    dateInput.on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('YYYY-MM-DD') + ' to ' + picker.endDate.format('YYYY-MM-DD'));
    });

    dateInput.on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });
});

    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script>
        document.getElementById('downloadExcel').addEventListener('click', function() {
            var table = document.querySelector('.table');

            // Clone table to avoid altering original
            var clone = table.cloneNode(true);

            // Convert to worksheet
            var wb = XLSX.utils.book_new();
            var ws = XLSX.utils.table_to_sheet(clone, {
                raw: true
            });

            // Optional: adjust column widths
            var colWidths = [];
            clone.querySelectorAll('th').forEach(th => {
                colWidths.push({
                    wch: th.innerText.length + 5
                });
            });
            ws['!cols'] = colWidths;

            XLSX.utils.book_append_sheet(wb, ws, 'Banks');
            XLSX.writeFile(wb, 'banks.xlsx');
        });
    </script>
@endpush

@push('page_css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endpush
