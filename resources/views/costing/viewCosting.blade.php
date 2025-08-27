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
                                    <td>
                                    <form action="{{ route('costing.destroy', $costing->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                    </td>

                                </tr>
                                @empty
                                <tr>
                                    <td colspan="28" class="text-center">No costings found.</td>
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

<!-- Custom Toast Container -->
<div id="toast-container" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>

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
