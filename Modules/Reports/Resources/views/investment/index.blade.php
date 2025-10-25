@extends('layouts.app')

@section('title', 'Fair International Report')

@section('breadcrumb')
<div class="d-flex justify-content-between align-items-center w-100">
    <ol class="breadcrumb border-0 m-0 mb-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item active">Fair International Report</li>
    </ol>
    <button id="downloadExcel" class="btn btn-success btn-sm">
        <i class="bi bi-file-earmark-excel"></i> Export Excel
    </button>
</div>
@endsection


@section('content')
<div class="container-fluid mt-4">
    <!-- Header Section with Logo -->
    <div class="row mb-4">
        <div class="col-12 text-center">
            <div class="d-flex align-items-center justify-content-center mb-3">
                <div class="company-logo me-3">
                    <img src="{{ asset('images/logo2.png') }}" alt="Fair International Logo" style="height: 60px;">
                </div>
                <div>
                    <h1 class="fw-bold text-primary mb-1">Fair International</h1>
                    <p class="text-muted mb-0">Financial Report Dashboard</p>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex flex-wrap gap-4">
        <!-- Left Table -->
        <div class="flex-fill">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-success text-white text-center fw-bold fs-5">
                    FAIR - INVESTMENT
                </div>
                <div class="card-body p-0">
                    <table id="investmentTable" class="table table-hover table-bordered align-middle text-center mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>Category</th>
                                <th>Amount (৳)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $leftValues = [
                            $totalStorager,
                            $calculateUpcoming,
                            $totalDueAmount,
                            $totalLose,
                            $totalOpeningBalance,
                            $totalInvestmentAmount,
                            ];
                            $leftTotal = array_sum($leftValues) - $totalProfit;
                            @endphp
                            <tr>
                                <td class="text-start">Total Storage</td>
                                <td>{{ number_format($totalStorager,2) }}</td>
                            </tr>
                            <tr>
                                <td class="text-start">Upcoming</td>
                                <td>{{ number_format($calculateUpcoming,2) }}</td>
                            </tr>
                            <tr>
                                <td class="text-start">Total Market Due</td>
                                <td>{{ number_format($totalDueAmount,2) }}</td>
                            </tr>
                            <tr>
                                <td class="text-start">Total Loss</td>
                                <td>{{ number_format($totalLose,2) }}</td>
                            </tr>
                            <tr>
                                <td class="text-start">Bank Amount</td>
                                <td>{{ number_format($totalOpeningBalance,2) }}</td>
                            </tr>
                            <tr>
                                <td class="text-start">Payment Get</td>
                                <td>{{ number_format($totalInvestmentAmount,2) }}</td>
                            </tr>
                            <tr>
                                <td class="text-start">Total Profit</td>
                                <td>{{ number_format($totalProfit,2) }}</td>
                            </tr>
                            <tr class="fw-bold fs-5 table-active">
                                <td class="text-end">Total</td>
                                <td>{{ number_format($leftTotal,2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right Table -->
        <div class="flex-fill">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-success text-white text-center fw-bold fs-5">
                    FAIR - TOTAL ASSET
                </div>
                <div class="card-body p-0">
                    <table id="assetTable" class="table table-hover table-bordered align-middle text-center mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>Category</th>
                                <th>Amount (৳)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            use Illuminate\Support\Facades\DB;
                            $partiesPaymentAmount = DB::table('parties_payment')->sum('amount');
                            $damageAmount = DB::table('parties_payment')->sum('damarage_amount');
                            $v1 = $partiesPaymentAmount + $damageAmount;
                            $v2 = $v1 + 311436;
                            $totalGet = $v2 - $totalInvestmentAmount;
                            $totalInvestmentValue = $leftTotal;
                            $totalLossValue = $totalLose;
                            $totalValue = $totalInvestmentValue + $totalGet - $totalLossValue;
                            @endphp
                            <tr>
                                <td class="text-start">Total Investment</td>
                                <td>{{ number_format($totalInvestmentValue,2) }}</td>
                            </tr>
                            <tr>
                                <td class="text-start">Total Get</td>
                                <td>0</td>
                            </tr>
                            <tr>
                                <td class="text-start">Total Loss</td>
                                <td>{{ number_format($totalLossValue,2) }}</td>
                            </tr>
                            <tr class="fw-bold fs-5 table-active">
                                <td class="text-end">Total Assets Value</td>
                                <td>{{ number_format($totalValue,2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/exceljs/dist/exceljs.min.js"></script>
<script>
    document.getElementById('downloadExcel').addEventListener('click', async function() {
        console.log('🟢 Excel button clicked');

        const wb = new ExcelJS.Workbook();

        // Helper function for styling
        function styleRow(row, isHeader = false) {
            row.eachCell((cell) => {
                cell.alignment = {
                    vertical: 'middle',
                    horizontal: 'center'
                };
                if (isHeader) {
                    cell.font = {
                        bold: true,
                        color: {
                            argb: 'FFFFFFFF'
                        },
                        size: 12
                    };
                    cell.fill = {
                        type: 'pattern',
                        pattern: 'solid',
                        fgColor: {
                            argb: 'FF2C3E50'
                        }
                    };
                } else {
                    cell.font = {
                        size: 11
                    };
                }
                cell.border = {
                    top: {
                        style: 'thin'
                    },
                    left: {
                        style: 'thin'
                    },
                    bottom: {
                        style: 'thin'
                    },
                    right: {
                        style: 'thin'
                    }
                };
            });
        }

        // Add Investment Sheet
        const invSheet = wb.addWorksheet('FAIR - INVESTMENT');
        const investmentTable = document.getElementById('investmentTable');
        investmentTable.querySelectorAll('tr').forEach((tr, i) => {
            const row = invSheet.getRow(i + 1);
            tr.querySelectorAll('th, td').forEach((cell, j) => {
                row.getCell(j + 1).value = cell.innerText.trim();
            });
            styleRow(row, i === 0); // Style header row
            row.commit();
        });
        invSheet.columns.forEach(col => col.width = 25); // Set column width

        // Add Asset Sheet
        const assetSheet = wb.addWorksheet('FAIR - TOTAL ASSET');
        const assetTable = document.getElementById('assetTable');
        assetTable.querySelectorAll('tr').forEach((tr, i) => {
            const row = assetSheet.getRow(i + 1);
            tr.querySelectorAll('th, td').forEach((cell, j) => {
                row.getCell(j + 1).value = cell.innerText.trim();
            });
            styleRow(row, i === 0); // Style header row
            row.commit();
        });
        assetSheet.columns.forEach(col => col.width = 25);

        // Generate and download
        const buf = await wb.xlsx.writeBuffer();
        const blob = new Blob([buf], {
            type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
        });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = `Fair_International_Report_${new Date().toISOString().slice(0,10)}.xlsx`;
        link.click();
        URL.revokeObjectURL(link.href);

        console.log('✅ Excel file generated with styles');
    });
</script>


<style>
    .table th,
    .table td {
        vertical-align: middle !important;
        padding: 12px 10px;
        text-transform: capitalize;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.05);
        transition: 0.3s ease-in-out;
        transform: translateY(-1px);
    }

    .table-dark {
        background: linear-gradient(135deg, #2c3e50, #34495e) !important;
        color: #fff;
    }

    .table-success {
        background-color: rgba(40, 167, 69, 0.15) !important;
        border: 2px solid rgba(40, 167, 69, 0.3);
    }

    .table-active {
        background-color: rgba(0, 0, 0, 0.05) !important;
    }

    .d-flex {
        gap: 1.5rem;
        flex-wrap: wrap;
    }

    .flex-fill {
        flex: 1 1 48%;
        min-width: 300px;
    }

    .card {
        overflow: hidden;
    }

    .card-header {
        border-bottom: none;
    }

    .company-logo {
        padding: 10px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    h1 {
        font-size: 2.5rem;
        background: linear-gradient(135deg, #66ea79, #02561e);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    @media (max-width: 768px) {
        .flex-fill {
            flex: 1 1 100%;
        }

        h1 {
            font-size: 2rem;
        }

        .d-flex {
            gap: 1rem;
        }
    }
</style>
@endsection
