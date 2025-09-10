@extends('layouts.app')

@section('title', 'Create Expense')

@section('breadcrumb')
<ol class="breadcrumb border-0 m-0">
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('expenses.index') }}">Expenses</a></li>
    <li class="breadcrumb-item active">Add</li>
</ol>
@endsection

@section('content')
<div class="container-fluid">
    <form id="expense-form" action="{{ route('expenses.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-lg-12">
                @include('utils.alerts')
                <div class="form-group">
                    <button class="btn btn-primary">Create Expense <i class="bi bi-check"></i></button>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <!-- Reference & LC -->
                        <div class="form-row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="reference">Reference <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="reference" required readonly value="EXP">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="lc_id">LC <span class="text-danger">*</span></label>
                                    <select name="lc_id" id="lc_id" class="form-control select2" required>
                                        <option value="" selected>Select LC</option>
                                        @foreach(\App\Models\Lc::all() as $lc)
                                        <option value="{{ $lc->id }}">{{ $lc->lc_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Date -->
                        <div class="form-row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="date">Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="date" required value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="category_id">Category <span class="text-danger">*</span></label>
                                    <select name="category_id" id="category_id" class="form-control" required>
                                        <option value="" selected>Select Category</option>
                                        @foreach(\Modules\Expense\Entities\ExpenseCategory::all() as $category)
                                        <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Amount -->
                        <div class="form-row">
                        </div>

                        <!-- Other Fees and Details -->
                        <div class="form-row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="amount">Amount <span class="text-danger">*</span></label>
                                    <input id="amount" type="text" class="form-control" name="amount" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group"><label for="cf_agent_fee">C&F Agent Fee</label><input type="text" class="form-control" name="cf_agent_fee"></div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group"><label for="bl_verify">B/L Verify</label><input type="text" class="form-control" name="bl_verify"></div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group"><label for="shipping_charge">Shipping/NOC Charge</label><input type="text" class="form-control" name="shipping_charge"></div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group"><label for="port_bill">Port Bill Charge</label><input type="text" class="form-control" name="port_bill"></div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group"><label for="labor_bill">Labor Bill</label><input type="text" class="form-control" name="labor_bill"></div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group"><label for="transport_bill">Transport/Survey Bill</label><input type="text" class="form-control" name="transport_bill"></div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group"><label for="other_receipt">Other Receipt Bill</label><input type="text" class="form-control" name="other_receipt"></div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group"><label for="formalin_test">Formalin Test Purpose</label><input type="text" class="form-control" name="formalin_test"></div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group"><label for="radiation_cert">Radiation Certificate</label><input type="text" class="form-control" name="radiation_cert"></div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group"><label for="labor_tips">Labor Tips</label><input type="text" class="form-control" name="labor_tips"></div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group"><label for="cf_commission">C&F Commission</label><input type="text" class="form-control" name="cf_commission"></div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group"><label for="ip_absence">IP Absence Fee</label><input type="text" class="form-control" name="ip_absence"></div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group"><label for="special_delivery">Special Delivery Fee</label><input type="text" class="form-control" name="special_delivery"></div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group"><label for="details">Details</label><textarea class="form-control" rows="6" name="details"></textarea></div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('page_scripts')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ asset('js/jquery-mask-money.js') }}"></script>
<script>
    $(document).ready(function() {
        // Initialize Select2
        $('#lc_id').select2({
            placeholder: "Select LC",
            allowClear: true,
            width: '100%'
        });

        // MaskMoney for amount
        $('#amount').maskMoney({
            prefix: '{{ settings()->currency->symbol }}',
            thousands: '{{ settings()->currency->thousand_separator }}',
            decimal: '{{ settings()->currency->decimal_separator }}',
        });

        $('#expense-form').submit(function() {
            var amount = $('#amount').maskMoney('unmasked')[0];
            $('#amount').val(amount);
        });
    });
</script>
@endpush
