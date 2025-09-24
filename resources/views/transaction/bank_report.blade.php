@extends('layouts.app')

@section('title', content: 'Bank Report')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item active">Bank Report</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid mb-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm rounded-3">
                    <div class="card-body">
                        {{-- <h5 class="mb-3 border-bottom pb-2">Bank Report</h5> --}}

                        <div class="row">
                            <div class="col-md-9">
                                <form action="{{ route('transaction.bank_report') }}" method="GET"
                                    class="row mb-3 align-items-end" enctype="multipart/form-data">
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
                                                placeholder="Select date range"
                                                value="{{ old('date_range', request('date_range')) }}">
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
                                <button id="downloadExcelBtn" class="btn btn-success">Download Excel</button>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table id="reportTable" class="table table-bordered table-hover">
                                <tbody>
                                    <tr>
                                        <td style="border: none;"></td>
                                        <td style="border: none;"></td>
                                        <td colspan="2" class="text-center">
                                            <p style="font-size: 20px;" class="m-0">{{ $selectedBank?->bank_name }}</p>
                                        </td>
                                        <td style="border: none;"></td>
                                        <td style="border: none;"></td>
                                    </tr>
                                    <tr>
                                        <td style="border: none;"></td>
                                        <td style="border: none;"></td>
                                        <td colspan="2" class="text-center">
                                            <p style="font-size: 20px;" class="m-0">
                                                {{ $request->date_range
                                                    ? \Carbon\Carbon::parse(explode(' - ', $request->date_range)[0])->format('d-m-Y') .
                                                        ' - ' .
                                                        \Carbon\Carbon::parse(explode(' - ', $request->date_range)[1])->format('d-m-Y')
                                                    : '' }}
                                            </p>
                                        </td>
                                        <td style="border: none;"></td>
                                        <td style="border: none;"></td>
                                    </tr>
                                    <tr>
                                        <td>

                                        </td>
                                        <td>
                                            {{ $request->date_range
                                                ? \Carbon\Carbon::parse(explode(' - ', $request->date_range)[0])->format('d-m-Y')
                                                : \Carbon\Carbon::today()->format('d-m-Y') }}
                                        </td>

                                        <td colspan="3">
                                            <h5>Opening Balance</h5>
                                        </td>
                                        <td colspan=""><strong>{{ number_format($openingBalance, 2) }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>#</td>
                                        <td>Date</td>
                                        <td>Discription</td>
                                        <td>Debit</td>
                                        <td>Credit</td>
                                        <td>Current Balance</td>
                                    </tr>
                                    @forelse($transactions as $index => $transaction)
                                        @php
                                            $ledger[$transaction->bank_id] +=
                                                $transaction->in_amount - $transaction->out_amount;
                                        @endphp
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ \Carbon\Carbon::parse($transaction->date)->format('d-m-Y') }}</td>
                                            <td>{{ $transaction->purpose }}</td>
                                            <td>{{ number_format($transaction->out_amount, 2) }}</td>
                                            <td>{{ number_format($transaction->in_amount, 2) }}</td>
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
                startDate: existingValue ? existingValue.split(' - ')[0] : moment(),
                endDate: existingValue ? existingValue.split(' - ')[1] : moment()
            });

            dateInput.on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format(
                    'YYYY-MM-DD'));
            });

            dateInput.on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });
        });
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script>
        // document.getElementById('downloadExcel').addEventListener('click', function() {
        //     var table = document.querySelector('.table');

        //     var clone = table.cloneNode(true);

        //     var ws = XLSX.utils.table_to_sheet(clone, {
        //         raw: true
        //     });

        //     Object.keys(ws).forEach(function(cell) {
        //         if (cell[0] === '!') return;
        //         ws[cell].s = ws[cell].s || {};
        //         ws[cell].s.alignment = {
        //             horizontal: "center",
        //             vertical: "center"
        //         };
        //         ws[cell].s.font = {
        //             sz: 14
        //         };
        //     });

        //     ws['!rows'] = [];
        //     for (let i = 0; i < clone.rows.length; i++) {
        //         ws['!rows'].push({
        //             hpt: 28
        //         });
        //     }

        //     ws['!cols'] = [{
        //         wch: 8
        //     }, {
        //         wch: 16
        //     }, {
        //         wch: 30
        //     }, {
        //         wch: 18
        //     }, {
        //         wch: 18
        //     }, {
        //         wch: 18
        //     }];

        //     var wb = XLSX.utils.book_new();
        //     XLSX.utils.book_append_sheet(wb, ws, 'Banks');
        //     XLSX.writeFile(wb, 'banks.xlsx');
        // });

        document.getElementById("downloadExcelBtn").addEventListener("click", function() {
            let table = document.getElementById("reportTable");
            if (!table) {
                alert("Table not found!");
                return;
            }

            // Excel styling
            let style = `
        <style>
            * {
                font-family: Roboto, Arial, sans-serif;
            }
            table, th, td {
                border: 1px solid #000;
                border-collapse: collapse;
                text-align: center;
            }
            th, td {
                padding: 10px;
                height: 35px; /* row height */
                vertical-align: middle;
            }
            th {
                font-weight: bold;
            }
        </style>
    `;

            let tableHTML = style + table.outerHTML;

            let blob = new Blob(
                ['\ufeff' + tableHTML], {
                    type: "application/vnd.ms-excel"
                }
            );

            let url = URL.createObjectURL(blob);
            let a = document.createElement("a");
            a.href = url;
            a.download = "bank_report.xls";
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        });
    </script>
@endpush

@push('page_css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endpush
