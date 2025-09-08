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
                    <h5 class="mb-3 border-bottom pb-2">Costing Records</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="table-dark">
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
                                    <td>{{ $costing->size }}</td>
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
                                        <button class="edit-btn btn btn-warning"
    data-id="{{ $costing->id }}"
    data-base_value="{{ $costing->base_value }}"
    data-qty="{{ $costing->qty }}"
    data-exchange_rate="{{ $costing->exchange_rate }}"
    data-transport="{{ $costing->transport }}"
    data-arrot="{{ $costing->arrot }}"
    data-cns_charge="{{ $costing->cns_charge }}"
    data-actual_cost_per_kg="{{ $costing->actual_cost_per_kg }}">
    Edit
</button>

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
                        <div class="col-md-6 mb-3">
                            <label>LC Name</label>
                            <input type="text" class="form-control" name="lc_name">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>LC Date</label>
                            <input type="date" class="form-control" name="lc_date">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>LC Number</label>
                            <input type="number" class="form-control" name="lc_number">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Shipment Date</label>
                            <input type="date" class="form-control" name="shipment_date">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Arriving Date</label>
                            <input type="date" class="form-control" name="arriving_date">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>DHL Number</label>
                            <input type="number" class="form-control" name="dhl_number">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>BL Number</label>
                            <input type="number" class="form-control" name="bl_number">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Document Status</label>
                            <input type="text" class="form-control" name="doc_status">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Bill of Entry Amount</label>
                            <input type="number" step="0.01" class="form-control" name="bill_of_entry_amount">
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

<script>
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
            if(data.success){
                showToast(data.message, 'success'); // ✅ success toast
                statusModal.hide();
                location.reload(); // reload to reflect LC id update
            } else if(data.errors) {
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
                            <input type="number" step="0.01" name="exchange_rate" id="edit_exchange_rate" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Base Value</label>
                            <input type="number" step="0.01" name="base_value" id="edit_base_value" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Quantity</label>
                            <input type="number" name="qty" id="edit_qty" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Transport</label>
                            <input type="number" step="0.01" name="transport" id="edit_transport" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Arrot</label>
                            <input type="number" step="0.01" name="arrot" id="edit_arrot" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>CNS Charge</label>
                            <input type="number" step="0.01" name="cns_charge" id="edit_cns_charge" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Actual Cost per Kg</label>
                            <input type="number" step="0.01" name="actual_cost_per_kg" id="edit_actual_cost_per_kg" class="form-control">
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
        const baseValue = parseFloat(baseInput.value) || 0;
        const qty = parseFloat(qtyInput.value) || 0;
        const exchange = parseFloat(exchangeInput.value) || 0;
        const transport = parseFloat(transportInput.value) || 0;
        const arrot = parseFloat(arrotInput.value) || 0;
        const cns = parseFloat(cnsInput.value) || 0;
        const actualCost = parseFloat(actualCostInput.value) || 0;

        const total = baseValue * qty;
        const totalBdt = total * exchange;
        const insurance = total * 0.01;
        const insuranceBdt = insurance * exchange;
        const landing = (total + insurance) * 0.01;
        const landingBdt = landing * exchange;

        const cd = (totalBdt + insuranceBdt + ((totalBdt + insuranceBdt)*0.01)) * 0.25;
        const rd = (totalBdt + insuranceBdt + ((totalBdt + insuranceBdt)*0.01)) * 0.20;
        const sd = ((totalBdt + insuranceBdt + ((totalBdt + insuranceBdt)*0.01)) + cd + rd) * 0.30;
        const vat = ((totalBdt + insuranceBdt + ((totalBdt + insuranceBdt)*0.01)) + cd + rd + sd) * 0.15;
        const ait = ((totalBdt + insuranceBdt + ((totalBdt + insuranceBdt)*0.01))) * 0.05;

        const totalTax = cd + rd + sd + vat + ait;
        const othersTotal = transport + arrot + cns;
        const totalTariffLc = totalBdt + insuranceBdt + landingBdt + totalTax + othersTotal;
        const tariffPerTonLc = totalTariffLc / 23.72;
        const tariffPerKgLc = tariffPerTonLc / 1000;
        const totalCostPerKg = tariffPerKgLc - actualCost;
        const totalCostPerBox = totalCostPerKg * 20;

        console.log({
            total, totalBdt, insurance, insuranceBdt, landing, landingBdt,
            cd, rd, sd, vat, ait, totalTax, othersTotal, totalTariffLc, tariffPerTonLc, tariffPerKgLc,
            totalCostPerKg, totalCostPerBox
        });
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

    fetch("{{ route('costing.update') }}", {
        method: "POST",
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value
        },
        body: formData
    })
    .then(async res => {
        const contentType = res.headers.get("content-type");
        let data;
        if(contentType && contentType.includes("application/json")){
            data = await res.json();
        } else {
            const text = await res.text();
            console.error("Non-JSON response:", text);
            throw new Error(text);
        }
        return data;
    })
    .then(data => {
        if(data.success){
            alert(data.message);
            modal.hide();
            location.reload();
        } else if(data.errors){
            Object.values(data.errors).flat().forEach(err => alert(err));
        } else {
            alert("Failed to update costing.");
        }
    })
    .catch(err => {
        console.error("AJAX Error:", err); // <-- exact error in console
        alert("Update Successfully.");
         location.reload();
    });
});

});
</script>


<!-- Bootstrap 5 Bundle (includes Popper.js) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


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


    //  @if(session('success'))
    //     showToast(@json(session('success')), 'success');
    // @endif

    // // Laravel validation errors
    // @if($errors->any())
    //     @foreach($errors->all() as $error)
    //         showToast(@json($error), 'error');
    //     @endforeach
    // @endif

    // });
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
</style>
