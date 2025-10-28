@extends('layouts.app')

@section('title', 'Edit Product')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></li>
        <li class="breadcrumb-item active">Edit</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid mb-4">
        <form id="product-form" action="{{ route('products.update', $product->id) }}" method="POST">
            @csrf
            @method('patch')
            <div class="row">
                <div class="col-lg-12">
                    @include('utils.alerts')
                    <div class="form-group">
                        <button class="btn btn-primary">Update Product <i class="bi bi-check"></i></button>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-row">
                                <!-- Product Name -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="product_name">Product Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="product_name" required value="{{ $product->product_name }}">
                                    </div>
                                </div>

                                <!-- Category -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="category_id">Category <span class="text-danger">*</span></label>
                                        <select class="form-control" name="category_id" id="category_id" required>
                                            @foreach(\Modules\Product\Entities\Category::all() as $category)
                                                <option {{ $category->id == $product->category->id ? 'selected' : '' }} value="{{ $category->id }}">{{ $category->category_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Hidden/unused fields remain d-none -->
                            <div class="form-row d-none">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="barcode_symbology">Barcode Symbology</label>
                                        <select class="form-control" name="product_barcode_symbology" id="barcode_symbology">
                                            <option value="C128">Code 128</option>
                                            <option value="C39">Code 39</option>
                                            <option value="UPCA">UPC-A</option>
                                            <option value="UPCE">UPC-E</option>
                                            <option value="EAN13">EAN-13</option>
                                            <option value="EAN8">EAN-8</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row d-none">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="product_quantity">Quantity</label>
                                        <input type="number" class="form-control" name="product_quantity" value="{{ $product->product_quantity }}" min="1">
                                    </div>
                                </div>
                            </div>

                            <!-- Unit and Note -->
                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="product_unit">Unit
                                            <i class="bi bi-question-circle-fill text-info" data-toggle="tooltip" title="This short text will be placed after Product Quantity."></i>
                                            <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-control" name="product_unit" id="product_unit" required>
                                            <option value="" selected>Select Unit</option>
                                            @foreach(\Modules\Setting\Entities\Unit::all() as $unit)
                                                <option {{ $product->product_unit == $unit->short_name ? 'selected' : '' }} value="{{ $unit->short_name }}">
                                                    {{ $unit->name . ' | ' . $unit->short_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="product_note">Note</label>
                                        <textarea name="product_note" id="product_note" rows="4" class="form-control">{{ $product->product_note }}</textarea>
                                    </div>
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
<script src="{{ asset('js/jquery-mask-money.js') }}"></script>
<script>
$(document).ready(function () {
    // Prevent digits in Product Name field
    $('input[name="product_name"]').on('input', function () {
        let value = $(this).val();
        // Remove any numbers
        $(this).val(value.replace(/[0-9]/g, ''));
    });
});
</script>
@endpush
