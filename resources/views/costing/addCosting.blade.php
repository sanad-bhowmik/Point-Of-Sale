@extends('layouts.app')

@section('title', 'Create Costing')

@section('breadcrumb')
<ol class="breadcrumb border-0 m-0">
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('costing.addCosting') }}">Costing</a></li>
    <li class="breadcrumb-item active">Add</li>
</ol>
@endsection

@section('content')
<div class="container-fluid mb-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm rounded-3">
                <div class="card-body">
                    <form action="{{ route('costing.storeCosting') }}" method="POST">
                        @csrf

                        <div class="row">
                            <!-- LEFT SIDE -->
                            <div class="col-md-6 p-3" style="background:#f9f9ff; ">
                                <h5 class="mb-3 border-bottom pb-2">Product & Costing Details</h5>

                                <!-- First Row (2 dropdowns per row) -->
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label>Supplier <span class="text-danger">*</span></label>
                                        <select name="supplier_id" class="form-control" required>
                                            <option value="">Select Supplier</option>
                                            @foreach(\App\Models\Supplier::all() as $supplier)
                                            <option value="{{ $supplier->id }}">{{ $supplier->supplier_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>Product Name <span class="text-danger">*</span></label>
                                        <select name="product_id" class="form-control" required>
                                            <option value="">Select Product</option>
                                            @foreach(\App\Models\Product::all() as $product)
                                            <option value="{{ $product->id }}">{{ $product->product_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label>Box Type <span class="text-danger">*</span></label>
                                        <select name="box_type" class="form-control" required>
                                            <option value="">Select Box Type</option>
                                            @foreach(\App\Models\Unit::all() as $unit)
                                            @php
                                            // Keep digits and decimal point only, e.g. "20 KG" -> "20", "23.72 kg" -> "23.72"
                                            $numericName = preg_replace('/[^0-9.]+/', '', $unit->name);
                                            @endphp
                                            <option value="{{ $numericName }}">{{ $unit->name }} ({{ $unit->short_name }})</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>Size <span class="text-danger">*</span></label>
                                        <select name="size" class="form-control" required>
                                            <option value="">Select Size</option>
                                            <option value="32">32</option>
                                            <option value="36">36</option>
                                            <option value="42">42</option>
                                        </select>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label>Currency</label>
                                        <select name="currency" class="form-control" required>
                                            <option value="">Select Currency</option>
                                            <option value="USD">USD</option>
                                            <option value="EUR">EUR</option>
                                            <option value="BDT">BDT</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Exchange Rate</label>
                                        <input type="number" step="0.01" class="form-control" name="exchange_rate" placeholder="0.00" required>
                                    </div>
                                </div>

                                <!-- Rest of Left Fields -->
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label>Base Value</label>
                                        <input type="number" step="0.01" class="form-control" name="base_value" placeholder="0.00" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Quantity</label>
                                        <input type="number" class="form-control" name="qty" placeholder="0.00" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label>Total</label>
                                        <input type="number" step="0.01" class="form-control" name="total" placeholder="0.00" readonly>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Total (BDT)</label>
                                        <input type="number" step="0.01" class="form-control" name="total_tk" placeholder="0.00" readonly>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label>Insurance (%)</label>
                                        <input type="number" step="0.01" class="form-control" name="insurance" placeholder="0.00" readonly>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Insurance (BDT)</label>
                                        <input type="number" step="0.01" class="form-control" name="insurance_tk" placeholder="0.00" readonly>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label>Landing Charge (%)</label>
                                        <input type="number" step="0.01" class="form-control" name="landing_charge" placeholder="0.00" readonly>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Landing Charge (BDT)</label>
                                        <input type="number" step="0.01" class="form-control" name="landing_charge_tk" placeholder="0.00" readonly>
                                    </div>
                                </div>

                            </div>

                            <!-- RIGHT SIDE -->
                            <div class="col-md-6 p-3" style="background:#fffef5; border-left:1px solid #ddd;">
                                <h5 class="mb-3 border-bottom pb-2">Tax Details</h5>

                                <div class="row">
                                    <div class="col-md-6 mb-3"><label>CD</label><input type="number" step="0.01" class="form-control" name="cd" placeholder="0.00" readonly></div>
                                    <div class="col-md-6 mb-3"><label>RD</label><input type="number" step="0.01" class="form-control" name="rd" placeholder="0.00" readonly></div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3"><label>SD</label><input type="number" step="0.01" class="form-control" name="sd" placeholder="0.00" readonly></div>
                                    <div class="col-md-6 mb-3"><label>VAT</label><input type="number" step="0.01" class="form-control" name="vat" placeholder="0.00" readonly></div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3"><label>AIT</label><input type="number" step="0.01" class="form-control" name="ait" placeholder="0.00" readonly></div>
                                    <div class="col-md-6 mb-3"><label>AT</label><input type="number" step="0.01" class="form-control" name="at" placeholder="0.00"></div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3"><label>ATV</label><input type="number" step="0.01" class="form-control" name="atv" placeholder="0.00"></div>
                                    <div class="col-md-6 mb-3"><label>Total Tax</label><input type="number" step="0.01" class="form-control" name="total_tax" placeholder="0.00" readonly></div>
                                </div>
                            </div>
                        </div>

                        <!-- BOTTOM SECTION -->
                        <div class="mt-4 p-3 bg-light border rounded">
                            <h5 class="mb-3 border-bottom pb-2">Others</h5>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label>Transport</label>
                                    <input type="number" step="0.01" class="form-control" name="transport" placeholder="0.00">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label>Arrot</label>
                                    <input type="number" step="0.01" class="form-control" name="arrot" placeholder="0.00">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label>CNS Charge</label>
                                    <input type="number" step="0.01" class="form-control" name="cns_charge" placeholder="0.00">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label>Others Total</label>
                                <input type="number" step="0.01" class="form-control" name="others_total" readonly>
                            </div>
                        </div>
                        <!-- TOTALS SECTION -->
                        <div class="mt-4 p-3 bg-light border rounded">
                            <h5 class="mb-3 border-bottom pb-2">Totals</h5>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label>Total Tariff based on LC</label>
                                    <input type="number" step="0.01" class="form-control" name="total_tariff_lc" placeholder="0.00" readonly>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label>Tariff per Ton based on LC</label>
                                    <input type="number" step="0.01" class="form-control" name="tariff_per_ton_lc" placeholder="0.00" readonly>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label>Tariff per Kg based on LC</label>
                                    <input type="number" step="0.01" class="form-control" name="tariff_per_kg_lc" placeholder="0.00" readonly>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Actual Value (%)</label>
                                    <input type="number" step="0.01" class="form-control" name="actual_value" placeholder="0.00">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Actual Total Cost per Kg</label>
                                    <input type="number" step="0.01" class="form-control" name="actual_cost_per_kg" placeholder="0.00" readonly>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Total Cost per Kg</label>
                                    <input type="number" step="0.01" class="form-control" name="total_cost_per_kg" placeholder="0.00" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Total Cost per Box (20 KG)</label>
                                    <input type="number" step="0.01" class="form-control" name="total_cost_per_box" placeholder="0.00" readonly>
                                </div>
                            </div>

                        </div>

                        <!-- SAVE BUTTON -->
                        <div class="mt-3 text-end">
                            <button type="submit" class="btn btn-primary px-4">Save Costing <i class="bi bi-check"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- DROPDOWNS ---
        const supplierSelect = document.querySelector('select[name="supplier_id"]');
        const productSelect = document.querySelector('select[name="product_id"]');
        const boxTypeSelect = document.querySelector('select[name="box_type"]');
        const sizeSelect = document.querySelector('select[name="size"]');
        const currencySelect = document.querySelector('select[name="currency"]');

        // --- ALL INPUTS ---
        const allInputs = document.querySelectorAll('input.form-control');

        // Disable all except supplier at start
        productSelect.disabled = true;
        boxTypeSelect.disabled = true;
        sizeSelect.disabled = true;
        currencySelect.disabled = true;
        allInputs.forEach(input => {
            if (!input.hasAttribute('readonly')) {
                input.disabled = true;
            }
        });

        // Sequential enabling
        supplierSelect.addEventListener('change', function() {
            if (this.value) productSelect.disabled = false;
        });
        productSelect.addEventListener('change', function() {
            if (this.value) boxTypeSelect.disabled = false;
        });
        boxTypeSelect.addEventListener('change', function() {
            if (this.value) sizeSelect.disabled = false;
        });
        sizeSelect.addEventListener('change', function() {
            if (this.value) currencySelect.disabled = false;
        });
        currencySelect.addEventListener('change', function() {
            if (this.value) {
                allInputs.forEach(input => {
                    if (!input.hasAttribute('readonly')) {
                        input.disabled = false;
                    }
                });
            }
        });

        // --- YOUR INPUTS ---
        const baseValueInput = document.querySelector('input[name="base_value"]');
        const qtyInput = document.querySelector('input[name="qty"]');
        const exchangeInput = document.querySelector('input[name="exchange_rate"]');
        const totalInput = document.querySelector('input[name="total"]');
        const totalBdtInput = document.querySelector('input[name="total_tk"]');
        const insuranceInput = document.querySelector('input[name="insurance"]');
        const insuranceBdtInput = document.querySelector('input[name="insurance_tk"]');
        const landingInput = document.querySelector('input[name="landing_charge"]');
        const landingBdtInput = document.querySelector('input[name="landing_charge_tk"]');
        const cdInput = document.querySelector('input[name="cd"]');
        const rdInput = document.querySelector('input[name="rd"]');
        const sdInput = document.querySelector('input[name="sd"]');
        const vatInput = document.querySelector('input[name="vat"]');
        const aitInput = document.querySelector('input[name="ait"]');
        const atInput = document.querySelector('input[name="at"]');
        const atvInput = document.querySelector('input[name="atv"]');
        const totalTaxInput = document.querySelector('input[name="total_tax"]');

        const transportInput = document.querySelector('input[name="transport"]');
        const arrotInput = document.querySelector('input[name="arrot"]');
        const cnsInput = document.querySelector('input[name="cns_charge"]');
        const othersTotalInput = document.querySelector('input[name="others_total"]');

        const totalTariffLcInput = document.querySelector('input[name="total_tariff_lc"]');
        const tariffPerTonLcInput = document.querySelector('input[name="tariff_per_ton_lc"]');
        const tariffPerKgLcInput = document.querySelector('input[name="tariff_per_kg_lc"]');
        const actualValueInput = document.querySelector('input[name="actual_value"]');
        const actualCostPerKgInput = document.querySelector('input[name="actual_cost_per_kg"]');
        const totalCostPerKgInput = document.querySelector('input[name="total_cost_per_kg"]');
        const totalCostPerBoxInput = document.querySelector('input[name="total_cost_per_box"]');

        // Format numbers without .00 if integer
        function formatNumber(num) {
            return Number.isInteger(num) ? num : parseFloat(num.toFixed(2));
        }

        // --- CALCULATIONS ---
        function calculateAll() {
            const baseValue = parseFloat(baseValueInput.value) || 0;
            const qty = parseFloat(qtyInput.value) || 0;
            const exchange = parseFloat(exchangeInput.value) || 0;
            const actualValue = parseFloat(actualValueInput.value) || 0;
            const boxTypeValue = parseFloat(boxTypeSelect.value) || 1; // fallback to 1 to avoid division by 0

            // 1) Base * Qty = Total
            const total = baseValue * qty;
            totalInput.value = formatNumber(total);

            // 2) Total * Exchange = Total BDT
            const totalBdt = total * exchange;
            totalBdtInput.value = formatNumber(totalBdt);

            // 3) Insurance = 1% of Total (foreign currency)
            const insurance = total * 0.01;
            insuranceInput.value = formatNumber(insurance);

            // 4) Insurance BDT = Insurance * Exchange
            const insuranceBdt = insurance * exchange;
            insuranceBdtInput.value = formatNumber(insuranceBdt);

            // 5) Landing = 1% of (Total + Insurance)
            const landing = (total + insurance) * 0.01;
            landingInput.value = formatNumber(landing);

            // 6) Landing BDT = Landing * Exchange
            const landingBdt = landing * exchange;
            landingBdtInput.value = formatNumber(landingBdt);

            // 7) Custom Duty (CD)
            const result1 = totalBdt + insuranceBdt;
            const result2 = result1 * 0.01;
            const result3 = result1 + result2;
            const cd = result3 * 0.25;
            cdInput.value = formatNumber(cd);

            // 8) Regulatory Duty (RD)
            const rdResult1 = totalBdt + insuranceBdt;
            const rdResult2 = rdResult1 * 0.01;
            const rdResult3 = rdResult1 + rdResult2;
            const rd = rdResult3 * 0.20;
            rdInput.value = formatNumber(rd);

            // 9) SD
            const sdResult1 = totalBdt + insuranceBdt;
            const sdResult2 = sdResult1 * 0.01;
            const sdResult3 = sdResult1 + sdResult2;
            const sd = (sdResult3 + cd + rd) * 0.30;
            sdInput.value = formatNumber(sd);

            // 10) VAT
            const vatResult1 = totalBdt + insuranceBdt;
            const vatResult2 = vatResult1 * 0.01;
            const vatResult3 = vatResult1 + vatResult2;
            const vat = (vatResult3 + cd + rd + sd) * 0.15;
            vatInput.value = formatNumber(vat);

            // 11) AIT
            const aitResult1 = totalBdt + insuranceBdt;
            const aitResult2 = aitResult1 * 0.01;
            const aitResult3 = aitResult1 + aitResult2;
            const ait = aitResult3 * 0.05;
            aitInput.value = formatNumber(ait);

            // Total Tax
            const at = parseFloat(atInput.value) || 0;
            const atv = parseFloat(atvInput.value) || 0;
            const totalTax = cd + rd + sd + vat + ait + at + atv;
            totalTaxInput.value = formatNumber(totalTax);

            // Others Total
            const transport = parseFloat(transportInput.value) || 0;
            const arrot = parseFloat(arrotInput.value) || 0;
            const cns = parseFloat(cnsInput.value) || 0;
            const othersTotal = transport + arrot + cns;
            othersTotalInput.value = formatNumber(othersTotal);

            // Total Tariff LC
            const totalTariffLc = totalBdt + insuranceBdt + landingBdt + totalTax + transport + arrot + cns;
            totalTariffLcInput.value = Number(totalTariffLc.toFixed(3));

            // Tariff per Ton LC
            const tariffPerTonLc = totalTariffLc / 23.72;
            tariffPerTonLcInput.value = Number(tariffPerTonLc.toFixed(3));

            // Tariff per Kg LC
            const tariffPerKgLc = tariffPerTonLc / 1000;
            tariffPerKgLcInput.value = Number(tariffPerKgLc.toFixed(3));

            // ---- ACTUAL TOTAL COST PER KG ----
            const actualCostPerKg = (actualValue / boxTypeValue) * exchange;
            actualCostPerKgInput.value = Number(actualCostPerKg.toFixed(3));

            // ---- TOTAL COST PER KG (DIFFERENCE) ----
            const totalCostPerKg = tariffPerKgLc - actualCostPerKg;
            totalCostPerKgInput.value = Number(totalCostPerKg.toFixed(9));



            // ---- TOTAL COST PER BOX (20 KG) ----
            const totalCostPerBox = totalCostPerKg * 20;
            totalCostPerBoxInput.value = Number(totalCostPerBox.toFixed(3));
        }

        // Trigger calculations on input changes
        [baseValueInput, qtyInput, exchangeInput, actualValueInput, boxTypeSelect,
            atInput, atvInput, transportInput, arrotInput, cnsInput
        ].forEach(input => {
            input.addEventListener('input', calculateAll);
        });
    });
</script>

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

            // Trigger animation
            setTimeout(() => toast.classList.add('show'), 100);

            // Remove after duration
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 500);
            }, duration);
        }

        // Laravel session success
        @if(session('success'))
            showToast(@json(session('success')), 'success');
        @endif

        // Laravel validation errors
        @if($errors->any())
            @foreach($errors->all() as $error)
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
