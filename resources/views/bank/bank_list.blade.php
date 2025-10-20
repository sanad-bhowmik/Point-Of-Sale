@extends('layouts.app')

@section('title', 'View Banks')

@section('breadcrumb')
<ol class="breadcrumb border-0 m-0">
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
    <li class="breadcrumb-item active">Bank List</li>
</ol>
@endsection

@section('content')
<div class="container-fluid mb-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm rounded-3">
                <div class="card-body">
                    <a href="{{ route('bank.create') }}" class="btn btn-primary mb-3">Add New Bank</a>
                    <button id="downloadExcel" class="btn btn-success mb-3">Download Excel</button>

                    <div class="table-responsive">
                        <table id="reportTable" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <td style="border: none;"></td>
                                    <td style="border: none;"></td>
                                    <td style="border: none;"></td>
                                    <td style="border: none;"></td>
                                    <td colspan="3" class="text-center" style="font-size: 20px;">Taifa & Tasbeeh</td>
                                    <td style="border: none;"></td>
                                    <td style="border: none;"></td>
                                    <td style="border: none;"></td>
                                    <td style="border: none;"></td>
                                </tr>
                                <tr>
                                    <th>#</th>
                                    <th>Institution</th>
                                    <th>Account No</th>
                                    <th>Bank Name</th>
                                    <th>Branch Name</th>
                                    <th>Owner</th>
                                    <th>Date</th>
                                    <th>Opening Balance</th>
                                    <th>Current Balance</th>
                                    <th>Disclaimer</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($banks as $index => $bank)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $bank->institution }}</td>
                                    <td>{{ $bank->account_no }}</td>
                                    <td>{{ $bank->bank_name }}</td>
                                    <td>{{ $bank->branch_name }}</td>
                                    <td>{{ $bank->owner }}</td>
                                    <td>{{ \Carbon\Carbon::parse($bank->date)->format('d-m-Y') }}</td>
                                    <td>{{ number_format($bank->opening_balance, 2) }}</td>
                                    <td>{{ number_format($bank->current_balance, 2) }}</td>
                                    <td>{{ $bank->disclaimer }}</td>
                                    <td>
                                        <a href="{{ route('bank.edit', $bank->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('bank.destroy', $bank->id) }}" method="POST" class="d-block mt-1">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="11" class="text-center">No Banks found.</td>
                                </tr>
                                @endforelse
                            </tbody>

                            <!-- ✅ Total Current Balance Row -->
                            <tfoot>
                                <tr>
                                    <th colspan="8" class="text-end">Total Current Balance:</th>
                                    <th id="totalCurrentBalance">0.00</th>
                                    <th colspan="2"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom Toast Container -->
<div id="toast-container" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>

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

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                showToast(@json($error), 'error');
            @endforeach
        @endif

        // ✅ Calculate Total Current Balance
        const rows = document.querySelectorAll("#reportTable tbody tr");
        let total = 0;

        rows.forEach(row => {
            const cell = row.cells[8]; // 9th column = Current Balance
            if (cell && !isNaN(parseFloat(cell.textContent.replace(/,/g, '')))) {
                total += parseFloat(cell.textContent.replace(/,/g, ''));
            }
        });

        document.getElementById("totalCurrentBalance").textContent =
            total.toLocaleString(undefined, { minimumFractionDigits: 2 });
    });
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
    document.getElementById("downloadExcel").addEventListener("click", function() {
        let table = document.getElementById("reportTable");
        if (!table) {
            alert("Table not found!");
            return;
        }

        // Clone table so we can safely modify it
        let clonedTable = table.cloneNode(true);

        // Remove the last column (action buttons)
        for (let row of clonedTable.rows) {
            row.deleteCell(row.cells.length - 1);
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
                height: 35px;
                vertical-align: middle;
            }
            th {
                font-weight: bold;
            }
        </style>
        `;

        // Combine styling with modified table HTML
        let tableHTML = style + clonedTable.outerHTML;

        // Create Excel file
        let blob = new Blob(['\ufeff' + tableHTML], {
            type: "application/vnd.ms-excel"
        });

        // Trigger download
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

@endsection

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

    tfoot th {
        background-color: #f8f9fa;
        font-weight: bold;
        font-size: 1rem;
    }
</style>
