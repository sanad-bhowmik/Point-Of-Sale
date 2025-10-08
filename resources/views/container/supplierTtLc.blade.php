@extends('layouts.app')

@section('title', 'Containers')

@section('breadcrumb')
<ol class="breadcrumb border-0 m-0">
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
    <li class="breadcrumb-item active">Containers</li>
</ol>
@endsection

@section('content')
<div class="container-fluid mb-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">

                    {{-- Controls --}}
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        {{-- Supplier dropdown and search/clear buttons --}}
                        <div class="d-flex align-items-center gap-2">
                            <select id="supplierDropdown" class="form-select" style="width: 250px;height: 29px;border: 1px solid #ebe2e2;">
                                <option value="" selected disabled>Select Supplier</option>
                                @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ (request('supplier_id') == $supplier->id) ? 'selected' : '' }}>
                                    {{ $supplier->supplier_name }}
                                </option>
                                @endforeach
                            </select>

                            <button id="searchBtn" class="btn btn-primary btn-sm ml-2">Search</button>
                            <button id="clearBtn" class="btn btn-secondary btn-sm ml-2">Clear</button>
                        </div>

                        {{-- Excel button --}}
                        <button class="btn btn-secondary btn-sm" onclick="downloadTableAsExcel()">
                            <i class="bi bi-file-earmark-excel-fill"></i> Excel
                        </button>
                    </div>

                    {{-- Table --}}
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="containerTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>LC Name</th>
                                    <th>LC Number</th>
                                    <th>Container Name</th>
                                    <th>Container Number</th>
                                    <th>Supplier Name</th>
                                    <th>LC Total</th>
                                    <th>TT Total</th>
                                    <th>LC Paid</th>
                                    <th>TT Paid</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $grandLcTotal = 0;
                                $grandTtTotal = 0;
                                $grandLcPaid = 0;
                                $grandTtPaid = 0;
                                @endphp

                                @if(isset($containers) && $containers->count() > 0)
                                @foreach($containers as $index => $container)
                                @php
                                $lcTotal = ($container->lc_value ?? 0) * ($container->lc_exchange_rate ?? 0) * ($container->qty ?? 0);
                                $ttTotal = ($container->tt_value ?? 0) * ($container->tt_exchange_rate ?? 0) * ($container->qty ?? 0);

                                $lcPaid = $container->lc_paid_amount ?? 0;
                                $ttPaid = $container->tt_paid_amount ?? 0;

                                // Remaining amounts after paid
                                $lcDue = $lcTotal - $lcPaid;
                                $ttDue = $ttTotal - $ttPaid;

                                $grandLcTotal += $lcDue; // Sum of due amounts
                                $grandTtTotal += $ttDue;
                                $grandLcPaid += $lcPaid;
                                $grandTtPaid += $ttPaid;
                                @endphp
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $container->lc->lc_name ?? '-' }}</td>
                                    <td>{{ $container->lc->lc_number ?? '-' }}</td>
                                    <td>{{ $container->name ?? '-' }}</td>
                                    <td>{{ $container->number ?? '-' }}</td>
                                    <td>{{ $container->lc->costing->supplier->supplier_name ?? '-' }}</td>
                                    <td>{{ number_format($lcDue, 2) }}</td> <!-- LC Total minus Paid -->
                                    <td>{{ number_format($ttDue, 2) }}</td> <!-- TT Total minus Paid -->
                                    <td>{{ number_format($lcPaid, 2) }}</td>
                                    <td>{{ number_format($ttPaid, 2) }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-success mb-1 lcPaymentBtn"
                                            data-container-id="{{ $container->id }}"
                                            data-lc-due="{{ $lcTotal - ($container->lc_paid_amount ?? 0) }}">
                                            LC Payment
                                        </button>

                                        <button class="btn btn-sm btn-primary ttPaymentBtn"
                                            data-container-id="{{ $container->id }}"
                                            data-tt-due="{{ $ttTotal - ($container->tt_paid_amount ?? 0) }}">
                                            TT Payment
                                        </button>

                                    </td>
                                </tr>
                                @endforeach

                                <tr style="background:#f1f1f1;font-weight:bold;">
                                    <td colspan="6" class="text-start">Grand Total:</td>
                                    <td>{{ number_format($grandLcTotal, 2) }}</td> <!-- sum of due LC -->
                                    <td>{{ number_format($grandTtTotal, 2) }}</td> <!-- sum of due TT -->
                                    <td></td>
                                </tr>
                                @else
                                <tr>
                                    <td colspan="11" class="text-center text-muted py-3">
                                        No data available.
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- LC Payment Modal -->
<div class="modal fade" id="lcPaymentModal" tabindex="-1" aria-labelledby="lcPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="lcPaymentForm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="lcPaymentModalLabel">LC Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="border: none;background-color: white;"><i class="c-sidebar-nav-icon bi bi-x"></i></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label>Due Amount</label>
                        <input type="number" class="form-control" id="lcDueAmount" readonly>
                    </div>
                    <div class="mb-2">
                        <label>Bank</label>
                        <select class="form-select" name="bank_id" required
                            style="width:100%;height:38px;padding:6px 12px;border:1px solid #ced4da;border-radius:4px;font-size:14px;">
                            <option value="" disabled selected>Select Bank</option>
                            @foreach($banks as $bank)
                            <option value="{{ $bank->id }}">{{ $bank->bank_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-2">
                        <label>Amount</label>
                        <input type="number" class="form-control" name="amount" id="lcAmount" placeholder="Enter amount">
                    </div>


                    <div class="mb-2">
                        <label>Date</label>
                        <input type="date" class="form-control" name="date" required>
                    </div>

                    <input type="hidden" name="container_id" id="lcContainerId">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Submit LC Payment</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- TT Payment Modal -->
<div class="modal fade" id="ttPaymentModal" tabindex="-1" aria-labelledby="ttPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="ttPaymentForm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ttPaymentModalLabel">TT Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="border: none;background-color: white;"><i class="c-sidebar-nav-icon bi bi-x"></i></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label>Due Amount</label>
                        <input type="number" class="form-control" id="ttDueAmount" readonly>
                    </div>

                    <div class="mb-2">
                        <label>Bank</label>
                        <select class="form-select" name="bank_id" required
                            style="width:100%;height:38px;padding:6px 12px;border:1px solid #ced4da;border-radius:4px;font-size:14px;">
                            <option value="" disabled selected>Select Bank</option>
                            @foreach($banks as $bank)
                            <option value="{{ $bank->id }}">{{ $bank->bank_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-2">
                        <label>Amount</label>
                        <input type="number" class="form-control" name="amount" id="ttAmount" placeholder="Enter amount">
                    </div>


                    <div class="mb-2">
                        <label>Date</label>
                        <input type="date" class="form-control" name="date" required>
                    </div>

                    <input type="hidden" name="container_id" id="ttContainerId">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Submit TT Payment</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Bootstrap 5 JS Bundle (includes Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.getElementById('ttPaymentForm').addEventListener('submit', function(e) {
        e.preventDefault();

        let formData = new FormData(this);

        fetch("{{ route('container.ttPayment') }}", {
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: data.message,
                        timer: 2000,
                        showConfirmButton: false,
                        toast: true,
                        position: 'top-end'
                    });

                    // Hide modal
                    let modal = bootstrap.Modal.getInstance(document.getElementById('ttPaymentModal'));
                    modal.hide();

                    // Reload page after 2 seconds
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                }
            })
            .catch(error => console.error(error));
    });

    document.getElementById('lcPaymentForm').addEventListener('submit', function(e) {
        e.preventDefault();

        let formData = new FormData(this);

        fetch("{{ route('container.lcPayment') }}", {
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: data.message,
                        timer: 2000,
                        showConfirmButton: false,
                        toast: true,
                        position: 'top-end'
                    });

                    // Hide modal
                    let modal = bootstrap.Modal.getInstance(document.getElementById('lcPaymentModal'));
                    modal.hide();

                    // Reload page after 2 seconds
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                }
            })
            .catch(error => console.error(error));
    });


    document.addEventListener('DOMContentLoaded', function() {
        // LC Payment Button
        document.querySelectorAll('.lcPaymentBtn').forEach(button => {
            button.addEventListener('click', function() {
                let containerId = this.getAttribute('data-container-id');
                let lcDue = parseFloat(this.getAttribute('data-lc-due'));

                document.getElementById('lcContainerId').value = containerId;
                document.getElementById('lcDueAmount').value = lcDue.toFixed(2);
                document.getElementById('lcAmount').value = '';

                let lcModal = new bootstrap.Modal(document.getElementById('lcPaymentModal'));
                lcModal.show();
            });
        });

        // TT Payment Button
        document.querySelectorAll('.ttPaymentBtn').forEach(button => {
            button.addEventListener('click', function() {
                let containerId = this.getAttribute('data-container-id');
                let ttDue = parseFloat(this.getAttribute('data-tt-due'));

                document.getElementById('ttContainerId').value = containerId;
                document.getElementById('ttDueAmount').value = ttDue.toFixed(2);
                document.getElementById('ttAmount').value = '';

                let ttModal = new bootstrap.Modal(document.getElementById('ttPaymentModal'));
                ttModal.show();
            });
        });

        // LC Payment Form
        document.getElementById('lcPaymentForm').addEventListener('submit', function(e) {
            e.preventDefault();
            console.log('LC Payment:', new FormData(this));
            let modal = bootstrap.Modal.getInstance(document.getElementById('lcPaymentModal'));
            modal.hide();
        });

        // TT Payment Form
        document.getElementById('ttPaymentForm').addEventListener('submit', function(e) {
            e.preventDefault();
            console.log('TT Payment:', new FormData(this));
            let modal = bootstrap.Modal.getInstance(document.getElementById('ttPaymentModal'));
            modal.hide();
        });
    });
</script>

<!-- jQuery & Toastr -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    $(document).ready(function() {
        $('#searchBtn').on('click', function() {
            let supplierId = $('#supplierDropdown').val();
            if (!supplierId) {
                toastr.warning("Please select a supplier");
                return;
            }
            let url = '{{ route("container.supplierTtLc") }}';
            window.location.href = url + '?supplier_id=' + supplierId;
        });

        $('#clearBtn').on('click', function() {
            $('#supplierDropdown').val('');
            window.location.href = '{{ route("container.supplierTtLc") }}';
        });
    });

    function downloadTableAsExcel() {
        let table = document.querySelector("#containerTable");
        if (!table) return;

        let rows = table.querySelectorAll("tr");

        let hasData = false;
        rows.forEach(row => {
            if (!row.querySelector('td.text-center.text-muted')) hasData = true;
        });
        if (!hasData) {
            toastr.warning("No data available to export");
            return;
        }

        let excelContent = `
    <table border="1" style="border-collapse:collapse; font-family:Calibri, sans-serif; font-size:14px;">
    `;

        // Header
        excelContent += "<tr>";
        table.querySelectorAll("thead tr th").forEach((cell, index) => {
            if (index === 0) return; // Skip SI column if desired
            excelContent += `
        <th style="
            padding:10px;
            background-color:#4472C4;
            color:#ffffff;
            font-weight:bold;
            text-align:center;
            border:1px solid #999;
        ">
            ${cell.innerText.trim()}
        </th>`;
        });
        excelContent += "</tr>";

        // Body
        table.querySelectorAll("tbody tr").forEach(row => {
            if (row.querySelector('td.text-center.text-muted')) return;
            if (row.classList.contains('grand-total-row')) return;

            excelContent += "<tr>";
            row.querySelectorAll("td").forEach((cell, index) => {
                if (index === 0) return; // skip SI
                let value = cell.innerText.trim();
                if (!isNaN(value.replace(/,/g, '')) && value !== "") {
                    value = value.replace(/,/g, '');
                    excelContent += `<td style="padding:8px; text-align:right; border:1px solid #ccc;">
                    ${parseFloat(value).toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2})}
                </td>`;
                } else {
                    excelContent += `<td style="padding:8px; text-align:left; border:1px solid #ccc;">${value}</td>`;
                }
            });
            excelContent += "</tr>";
        });

        // Grand Total
        let grandTotalRow = table.querySelector(".grand-total-row");
        if (grandTotalRow) {
            excelContent += `<tr style="background-color:#E2EFDA; font-weight:bold;">
            <td colspan="6" style="text-align:left; padding:8px; border:1px solid #999; background-color:#D9E1F2; font-weight:bold;">Grand Total:</td>`;

            grandTotalRow.querySelectorAll("td").forEach((td, index) => {
                if (index < 6) return; // Skip first 6 cells
                let value = td.innerText.trim();
                excelContent += `<td style="padding:8px; text-align:right; font-weight:bold; background-color:#D9E1F2; border:1px solid #999;">${value}</td>`;
            });
            excelContent += "</tr>";
        }

        excelContent += "</table>";

        let today = new Date();
        let filename = `Supplier_total_LC&TT_${today.getDate()}_${today.getMonth() + 1}_${today.getFullYear()}.xls`;

        let downloadLink = document.createElement("a");
        downloadLink.href = 'data:application/vnd.ms-excel,' + encodeURIComponent(excelContent);
        downloadLink.download = filename;
        downloadLink.click();
    }

    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "timeOut": "5000"
    };
</script>
@endsection
