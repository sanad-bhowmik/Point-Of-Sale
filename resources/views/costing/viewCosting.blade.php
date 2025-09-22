@extends('layouts.app')

@section('title', 'View Costings')

@section('breadcrumb')
<ol class="breadcrumb border-0 m-0">
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
    <li class="breadcrumb-item active">Costing List</li>
</ol>
@endsection

@section('content')
<div class="container-fluid mb-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm rounded-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Costing Records</h5>
                        <div>
                            <button class="btn btn-secondary buttons-excel" onclick="downloadTableAsExcel()">
                                <i class="bi bi-file-earmark-excel-fill"></i> Excel
                            </button>
                            <a href="{{ route('costing.addCosting') }}" class="btn btn-primary">
                                + Add Costing
                            </a>
                        </div>
                    </div>
                    <!-- <h5 class="mb-3 border-bottom pb-2"></h5> -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Supplier</th>
                                    <th>Product</th>
                                    <th>Box Type</th>
                                    <th>Size</th>
                                    <th>Currency</th>
                                    <th>Base Value</th>
                                    <th>Quantity</th>
                                    <th>Exchange Rate</th>
                                    <th>Total</th>
                                    <th>Total (BDT)</th>
                                    <th>Insurance (%)</th>
                                    <th>Insurance (BDT)</th>
                                    <th>Landing Charge (%)</th>
                                    <th>Landing Charge (BDT)</th>
                                    <th>CD</th>
                                    <th>RD</th>
                                    <th>SD</th>
                                    <th>VAT</th>
                                    <th>AIT</th>
                                    <th>AT</th>
                                    <th>ATV</th>
                                    <th>Total Tax</th>
                                    <th>Transport</th>
                                    <th>Arrot</th>
                                    <th>CNS Charge</th>
                                    <th>Others Total</th>
                                    <th>TT Amount</th>

                                    <!--   New Fields -->
                                    <th>Total Tariff (LC)</th>
                                    <th>Tariff per Ton (LC)</th>
                                    <th>Tariff per Kg (LC)</th>
                                    <th>Actual Cost per Kg</th>
                                    <th>Total Cost per Kg</th>
                                    <th>Total Cost per Box (20 KG)</th>

                                    <!--   New Status Column -->
                                    <th>Status</th>

                                    <!-- Actions -->
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($costings as $index => $costing)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $costing->supplier->supplier_name ?? '-' }}</td>
                                    <td>{{ $costing->product->product_name ?? '-' }}</td>
                                    <td>{{ $costing->box_type }}</td>
                                    @php
                                    $sizeName = DB::table('sizes')->where('id', $costing->size)->value('size');
                                    @endphp
                                    <td>{{ $sizeName ?? '-' }}</td>
                                    <td>{{ $costing->currency }}</td>
                                    <td>{{ $costing->base_value }}</td>
                                    <td>{{ $costing->qty }}</td>
                                    <td>{{ $costing->exchange_rate }}</td>
                                    <td>{{ $costing->total }}</td>
                                    <td>{{ $costing->total_tk }}</td>
                                    <td>{{ $costing->insurance }}</td>
                                    <td>{{ $costing->insurance_tk }}</td>
                                    <td>{{ $costing->landing_charge }}</td>
                                    <td>{{ $costing->landing_charge_tk }}</td>
                                    <td>{{ $costing->cd }}</td>
                                    <td>{{ $costing->rd }}</td>
                                    <td>{{ $costing->sd }}</td>
                                    <td>{{ $costing->vat }}</td>
                                    <td>{{ $costing->ait }}</td>
                                    <td>{{ $costing->at }}</td>
                                    <td>{{ $costing->atv }}</td>
                                    <td>{{ $costing->total_tax }}</td>
                                    <td>{{ $costing->transport }}</td>
                                    <td>{{ $costing->arrot }}</td>
                                    <td>{{ $costing->cns_charge }}</td>
                                    <td>{{ $costing->others_total }}</td>
                                    <td>{{ $costing->tt_amount }}</td>

                                    <!--   New Fields Data -->
                                    <td>{{ $costing->total_tariff_lc }}</td>
                                    <td>{{ $costing->tariff_per_ton_lc }}</td>
                                    <td>{{ $costing->tariff_per_kg_lc }}</td>
                                    <td>{{ $costing->actual_cost_per_kg }}</td>
                                    <td>{{ $costing->total_cost_per_kg }}</td>
                                    <td>{{ $costing->total_cost_per_box }}</td>

                                    <!--   Status Column with Button -->
                                    <td>
                                        <button class="btn btn-sm btn-info status-btn" data-costing-id="{{ $costing->id }}">
                                            LC Status
                                        </button>
                                    </td>


                                    <!--  Actions -->
                                    <td>
                                        <button class="edit-btn btn btn-warning" data-id="{{ $costing->id }}" data-base_value="{{ $costing->base_value }}" data-qty="{{ $costing->qty }}" data-exchange_rate="{{ $costing->exchange_rate }}" data-transport="{{ $costing->transport }}" data-arrot="{{ $costing->arrot }}" data-cns_charge="{{ $costing->cns_charge }}" data-actual_cost_per_kg="{{ $costing->actual_cost_per_kg }}">Edit</button>

                                        <form action="{{ route('costing.destroy', $costing->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="35" class="text-center">No costings found.</td>
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
<!-- Status Modal -->
<!-- LC / Shipment Modal -->
<!-- Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusModalLabel">Update LC / Shipment Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" style="border: none;background-color: white;">✖</button>
            </div>
            <div class="modal-body">
                <form id="statusForm">
                    @csrf
                    <input type="hidden" name="costing_id" id="status_costing_id">
                    <div class="row">
                        <!-- Existing fields -->
                        <div class="col-md-6 mb-3">
                            <label>LC Name</label>
                            <input type="text" class="form-control input" name="lc_name">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>LC Number</label>
                            <input type="number" class="form-control input" name="lc_number">
                        </div>

                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveStatusBtn">Save</button>
            </div>
        </div>
    </div>
</div>

<!-- Include Flatpickr CSS & JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<!-- Initialize Flatpickr -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        flatpickr(".flatpickr", {
            dateFormat: "Y-m-d",
            allowInput: true,
        });
    });
    document.addEventListener('DOMContentLoaded', function() {
        const statusModal = new bootstrap.Modal(document.getElementById('statusModal'));

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

        // Open modal when status button clicked
        document.querySelectorAll('.status-btn').forEach(button => {
            button.addEventListener('click', function() {
                const costingId = this.dataset.costingId;
                document.getElementById('status_costing_id').value = costingId;

                // Reset form
                document.getElementById('statusForm').reset();

                // Show modal
                statusModal.show();
            });
        });

        // Save LC via AJAX
        document.getElementById('saveStatusBtn').addEventListener('click', function() {
            const form = document.getElementById('statusForm');
            const formData = new FormData(form);

            fetch("{{ route('costing.lc.store') }}", {
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value
                    },
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        showToast(data.message, 'success'); // ✅ success toast
                        statusModal.hide();
                        location.reload(); // reload to reflect LC id update
                    } else if (data.errors) {
                        // show each validation error
                        Object.values(data.errors).flat().forEach(err => showToast(err, 'error'));
                    } else {
                        showToast("Failed to save LC.", 'error');
                    }
                })
                .catch(err => {
                    console.error(err);
                    showToast("Something went wrong.", 'error'); // toast for exceptions
                });
        });

        // Laravel session / validation messages
    @if(session('success'))
        showToast(@json(session('success')), 'success');
    @endif

    @if($errors->any())
        @foreach($errors->all() as $error)
            showToast(@json($error), 'error');
        @endforeach
    @endif
    });
</script>
<script>
    function downloadTableAsExcel() {
        let table = document.querySelector("table");
        let rows = table.querySelectorAll("tr");

        let excelContent = "<table border='1' style='border-collapse:collapse;'>";

        let totalCols = rows[0].querySelectorAll("th, td").length - 2;
        excelContent += `<tr>
                        <th colspan="${totalCols}" rowspan="3"
                            style="text-align:center; vertical-align:middle; font-size:28px; font-weight:bold; padding:15px;">
                            Costing
                        </th>
                     </tr>`;
        excelContent += `<tr></tr><tr></tr>`;

        rows.forEach((row, rowIndex) => {
            let cells = row.querySelectorAll("th, td");
            excelContent += "<tr>";

            cells.forEach((cell, colIndex) => {
                if (colIndex >= cells.length - 2) return;
                let tag = (rowIndex === 0) ? "th" : "td";
                excelContent += `<${tag} style="padding:5px;">${cell.innerText.trim()}</${tag}>`;
            });

            excelContent += "</tr>";
        });

        excelContent += "</table>";

        let today = new Date();
        let day = String(today.getDate()).padStart(2, '0');
        let month = String(today.getMonth() + 1).padStart(2, '0');
        let year = today.getFullYear();
        let filename = `Costing_${day}_${month}_${year}_.xls`;

        let downloadLink = document.createElement("a");
        downloadLink.href = 'data:application/vnd.ms-excel,' + encodeURIComponent(excelContent);
        downloadLink.download = filename;
        downloadLink.click();
    }
    // Open modal when status button clicked
    document.querySelectorAll('.status-btn').forEach(button => {
        button.addEventListener('click', function() {
            const costingId = this.dataset.costingId;
            document.getElementById('status_costing_id').value = costingId;

            // Reset form
            document.getElementById('statusForm').reset();

            // Fetch previous LC data
            fetch(`/costing/${costingId}/lc`)
                .then(res => res.json())
                .then(data => {
                    console.log("Previous LC Data:", data); // 🔹 log in console
                    if (data.success && data.lc) {
                        document.querySelector("input[name='lc_name']").value = data.lc.lc_name ?? "";
                        document.querySelector("input[name='lc_number']").value = data.lc.lc_number ?? "";
                    }
                })
                .catch(err => console.error("Error fetching LC:", err));

            // Show modal
            statusModal.show();
        });
    });
</script>



<!--   Edit Modal -->
<div class="modal fade" id="editCostingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="editCostingForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Edit Costing</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" style="border: none;background-color: white;">✖</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit_id">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Exchange Rate</label>
                            <input type="text" name="exchange_rate" id="edit_exchange_rate" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Base Value</label>
                            <input type="text" name="base_value" id="edit_base_value" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Quantity</label>
                            <input type="number" name="qty" id="edit_qty" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Transport</label>
                            <input type="text" name="transport" id="edit_transport" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Arrot</label>
                            <input type="text" name="arrot" id="edit_arrot" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>CNS Charge</label>
                            <input type="text" name="cns_charge" id="edit_cns_charge" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Actual Cost per Kg</label>
                            <input type="text" name="actual_cost_per_kg" id="edit_actual_cost_per_kg" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = new bootstrap.Modal(document.getElementById('editCostingModal'));
        const editForm = document.getElementById('editCostingForm');

        // Inputs
        const costingIdInput = document.getElementById('edit_id');
        const baseInput = document.getElementById('edit_base_value');
        const qtyInput = document.getElementById('edit_qty');
        const exchangeInput = document.getElementById('edit_exchange_rate');
        const transportInput = document.getElementById('edit_transport');
        const arrotInput = document.getElementById('edit_arrot');
        const cnsInput = document.getElementById('edit_cns_charge');
        const actualCostInput = document.getElementById('edit_actual_cost_per_kg');

        // Calculation function
        function calculateAll() {
            // helper that tries id then name, and warns if not found
            const get = (id) => document.getElementById(id) || document.querySelector(`[name="${id}"]`) || null;

            // — INPUT ELEMENTS (adjust ids/names to match your HTML)
            const baseValueInput = get('baseValueInput');
            const qtyInput = get('qtyInput');
            const exchangeInput = get('exchangeInput');
            const actualValueInput = get('actualValueInput');
            const actualRateInput = get('actualRateInput');
            const boxTypeSelect = get('boxTypeSelect'); // <select> or <input>

            // — OUTPUT ELEMENTS (adjust ids/names to match your HTML)
            const totalInput = get('totalInput');
            const totalBdtInput = get('totalBdtInput');
            const insuranceInput = get('insuranceInput');
            const insuranceBdtInput = get('insuranceBdtInput');
            const landingInput = get('landingInput');
            const landingBdtInput = get('landingBdtInput');
            const cdInput = get('cdInput');
            const rdInput = get('rdInput');
            const sdInput = get('sdInput');
            const vatInput = get('vatInput');
            const aitInput = get('aitInput');
            const atInput = get('atInput');
            const atvInput = get('atvInput');
            const totalTaxInput = get('totalTaxInput');
            const transportInput = get('transportInput');
            const arrotInput = get('arrotInput');
            const cnsInput = get('cnsInput');
            const othersTotalInput = get('othersTotalInput');
            const totalTariffLcInput = get('totalTariffLcInput');
            const tariffPerTonLcInput = get('tariffPerTonLcInput');
            const tariffPerKgLcInput = get('tariffPerKgLcInput');
            const actualCostPerKgInput = get('actualCostPerKgInput');
            const totalCostPerKgInput = get('totalCostPerKgInput');
            const totalCostPerBoxInput = get('totalCostPerBoxInput');

            // log missing elements and abort if critical ones are missing
            const required = {
                baseValueInput,
                qtyInput,
                exchangeInput,
                boxTypeSelect
            };
            const missing = Object.keys(required).filter(k => !required[k]);
            if (missing.length) {
                console.error('Missing required inputs:', missing);
                // You can return or continue with defaults (here we return to avoid wrong math)
                return;
            }

            // small fallback formatNumber if you don't already have one
            const formatNumber = window.formatNumber || ((n) => (isFinite(n) ? Number(n.toFixed(3)) : 0));

            // Now parse values safely (use optional chaining)
            const baseValue = parseFloat(baseValueInput.value) || 0;
            const qty = parseFloat(qtyInput.value) || 0;
            const exchange = parseFloat(exchangeInput.value) || 0;
            const actualValue = parseFloat(actualValueInput?.value) || 0;
            const actualRate = parseFloat(actualRateInput?.value) || 0;
            const boxTypeValue = parseFloat(boxTypeSelect.value) || 1;

            // --- Calculation (same logic as your "posting" code) ---
            const total = baseValue * qty;
            if (totalInput) totalInput.value = formatNumber(total);

            const totalBdt = total * exchange;
            if (totalBdtInput) totalBdtInput.value = formatNumber(totalBdt);

            const insurance = total * 0.01;
            if (insuranceInput) insuranceInput.value = formatNumber(insurance);

            const insuranceBdt = insurance * exchange;
            if (insuranceBdtInput) insuranceBdtInput.value = formatNumber(insuranceBdt);

            const landing = (total + insurance) * 0.01;
            if (landingInput) landingInput.value = formatNumber(landing);

            const landingBdt = landing * exchange;
            if (landingBdtInput) landingBdtInput.value = formatNumber(landingBdt);

            const cd = (totalBdt + insuranceBdt + (totalBdt + insuranceBdt) * 0.01) * 0.25;
            if (cdInput) cdInput.value = formatNumber(cd);

            const rd = (totalBdt + insuranceBdt + (totalBdt + insuranceBdt) * 0.01) * 0.20;
            if (rdInput) rdInput.value = formatNumber(rd);

            const sd = (rd + cd + landingBdt + totalBdt + insuranceBdt) * 0.25;
            if (sdInput) sdInput.value = formatNumber(sd);

            const vat = ((totalBdt + insuranceBdt + (totalBdt + insuranceBdt) * 0.01) + cd + rd + sd) * 0.15;
            if (vatInput) vatInput.value = formatNumber(vat);

            const ait = ((totalBdt + insuranceBdt + (totalBdt + insuranceBdt) * 0.01)) * 0.05;
            if (aitInput) aitInput.value = formatNumber(ait);

            const at = parseFloat(atInput?.value) || 0;
            const atv = parseFloat(atvInput?.value) || 0;
            const totalTax = cd + rd + sd + vat + ait + at + atv;
            if (totalTaxInput) totalTaxInput.value = formatNumber(totalTax);

            const transport = parseFloat(transportInput?.value) || 0;
            const arrot = parseFloat(arrotInput?.value) || 0;
            const cns = parseFloat(cnsInput?.value) || 0;
            const othersTotal = transport + arrot + cns;
            if (othersTotalInput) othersTotalInput.value = formatNumber(othersTotal);

            const totalTariffLc = totalBdt + insuranceBdt + landingBdt + totalTax + othersTotal;
            if (totalTariffLcInput) totalTariffLcInput.value = Number(totalTariffLc.toFixed(3));

            let tariffPerKgLc = 0;
            if (qty > 0 && boxTypeValue > 0) {
                const totalTons = (qty * boxTypeValue) / 1000;
                const tariffPerTonLc = totalTariffLc / totalTons;
                if (tariffPerTonLcInput) tariffPerTonLcInput.value = Number(tariffPerTonLc.toFixed(3));
                tariffPerKgLc = tariffPerTonLc / 1000;
                if (tariffPerKgLcInput) tariffPerKgLcInput.value = Number(tariffPerKgLc.toFixed(3));
            } else {
                if (tariffPerTonLcInput) tariffPerTonLcInput.value = 0;
                if (tariffPerKgLcInput) tariffPerKgLcInput.value = 0;
            }

            const actualCostPerKg = (actualValue / boxTypeValue) * actualRate;
            if (actualCostPerKgInput) actualCostPerKgInput.value = Number(actualCostPerKg.toFixed(3));

            const totalCostPerKg = tariffPerKgLc + actualCostPerKg;
            if (totalCostPerKgInput) totalCostPerKgInput.value = Number(totalCostPerKg.toFixed(3));

            const totalCostPerBox = totalCostPerKg * boxTypeValue;
            if (totalCostPerBoxInput) totalCostPerBoxInput.value = Number(totalCostPerBox.toFixed(3));

            // optionally return the computed object for testing
            return {
                total,
                totalBdt,
                insurance,
                insuranceBdt,
                landing,
                landingBdt,
                cd,
                rd,
                sd,
                vat,
                ait,
                totalTax
            };
        }


        // Trigger recalculation on input changes
        [baseInput, qtyInput, exchangeInput, transportInput, arrotInput, cnsInput, actualCostInput].forEach(input => {
            input.addEventListener('input', calculateAll);
        });

        // Populate modal on edit button click
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function() {
                const data = button.dataset;

                costingIdInput.value = data.id;
                baseInput.value = data.base_value;
                qtyInput.value = data.qty;
                exchangeInput.value = data.exchange_rate;
                transportInput.value = data.transport;
                arrotInput.value = data.arrot;
                cnsInput.value = data.cns_charge;
                actualCostInput.value = data.actual_cost_per_kg;

                calculateAll(); // Calculate immediately using DB values
                modal.show();
            });
        });

        // Submit form via AJAX
        editForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(editForm);
            formData.append('_token', document.querySelector('input[name=_token]').value);

            fetch("{{ route('costing.update') }}", {
                    method: "POST",
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        modal.hide();
                        location.reload();
                    } else if (data.errors) {
                        Object.values(data.errors).flat().forEach(err => alert(err));
                    } else {
                        alert("Failed to update costing.");
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert("Something went wrong.");
                });
        });

    });
</script>


<!-- Bootstrap 5 Bundle (includes Popper.js) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- Custom Toast Container -->
<div id="toast-container" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>
@endsection
<style>
    .input {
        box-shadow: rgba(0, 0, 0, 0.15) 1.95px 1.95px 2.6px;
    }

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
