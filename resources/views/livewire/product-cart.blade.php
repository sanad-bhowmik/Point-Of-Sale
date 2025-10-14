<div>
    <div>
        @if (session()->has('message'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <div class="alert-body">
                <span>{{ session('message') }}</span>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        </div>
        @endif
        <div class="table-responsive position-relative">
            <div wire:loading.flex class="col-12 position-absolute justify-content-center align-items-center" style="top:0;right:0;left:0;bottom:0;background-color: rgba(255,255,255,0.5);z-index: 99;">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-dark">

                        <tr>
                            <th class="align-middle">Product</th>
                            <th class="align-middle text-center">Net Unit Price</th>
                            <th class="align-middle text-center">Stock</th>
                            <th class="align-middle text-center">Quantity</th>
                            <!-- <th class="align-middle text-center">Discount</th> -->
                            <th class="align-middle text-center d-none">Tax</th>
                            <th class="align-middle text-center d-none">Sub Total</th>
                            <th class="align-middle text-center">Size</th>
                            <th class="align-middle text-center">Unit</th>
                            <th class="align-middle text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        @if($cart_items->isNotEmpty())
                        @foreach($cart_items as $cart_item)
                        <tr>
                            <td class="align-middle">
                                {{ $cart_item->name }} <br>
                                <span class="badge badge-success">
                                    {{ $cart_item->options->code }}
                                </span>
                                <!-- @include('livewire.includes.product-cart-modal') -->
                            </td>

                            <td x-data="{ open{{ $cart_item->id }}: false }" class="align-middle text-center">
                                <span x-show="!open{{ $cart_item->id }}" @click="open{{ $cart_item->id }} = !open{{ $cart_item->id }}">
                                    {{ format_currency($cart_item->price) }}
                                </span>
                                <div x-show="open{{ $cart_item->id }}">
                                    @include('livewire.includes.product-cart-price')
                                </div>
                            </td>

                            <td class="align-middle text-center">
                                <span class="badge badge-info">{{ $cart_item->options->stock  }} PC</span>
                            </td>

                            <td class="align-middle text-center">
                                @include('livewire.includes.product-cart-quantity')
                            </td>

                            <td class="align-middle text-center d-none">
                                {{ format_currency($cart_item->options->product_tax) }}
                            </td>

                            <td class="align-middle text-center d-none">
                                {{ format_currency($cart_item->options->sub_total) }}
                            </td>

                            <!-- Size -->
                            <td class="align-middle text-center">
                                @php
                                $sizes = \App\Models\Size::where('product_id', $cart_item->id)->get();
                                @endphp
                                <select class="form-select form-select-sm" wire:model="cart_sizes.{{ $cart_item->rowId }}" wire:change="updateSize('{{ $cart_item->rowId }}')" style="padding: 3px;border: 1px solid #69696963;border-radius: 4px;color: gray;">
                                    <option value="" disabled selected>— Select Size —</option>
                                    @foreach($sizes as $size)
                                    <option value="{{ $size->id }}">{{ $size->size }}</option>
                                    @endforeach
                                </select>
                            </td>

                            <!-- Unit -->
                            <td class="align-middle text-center">
                                <span class="badge badge-success">
                                    {{ $cart_item->options->unit ?? '-' }}
                                </span>
                            </td>

                            <td class="align-middle text-center">
                                <a href="#" wire:click.prevent="removeItem('{{ $cart_item->rowId }}')">
                                    <i class="bi bi-x-circle font-2xl text-danger"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="9" class="text-center">
                                <span class="text-danger">Please search & select products!</span>
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>


        </div>
    </div>

    <div class="row justify-content-md-end">
        <div class="col-md-4">
            <div class="table-responsive">
                <table class="table table-striped">
                    <!-- <tr>
                        <th>Tax ({{ $global_tax }}%)</th>
                        <td>(+) {{ format_currency(Cart::instance($cart_instance)->tax()) }}</td>
                    </tr> -->
                    <!-- <tr>
                        <th>Discount ({{ $global_discount }}%)</th>
                        <td>(-) {{ format_currency(Cart::instance($cart_instance)->discount()) }}</td>
                    </tr> -->
                    <!-- <tr>
                        <th>Shipping</th>
                        <input type="hidden" value="{{ $shipping }}" name="shipping_amount">
                        <td>(+) {{ format_currency($shipping) }}</td>
                    </tr> -->
                    <tr>
                        <th>Grand Total</th>
                        @php
                        $total_with_shipping = Cart::instance($cart_instance)->total() + (float) $shipping
                        @endphp
                        <th>
                            (=) {{ format_currency($total_with_shipping) }}
                        </th>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <input type="hidden" name="total_amount" value="{{ $total_with_shipping }}">

    <div class="form-row">
        <div class="col-lg-6">
            <div class="form-group d-none">
                <label for="tax_percentage">Tax (%)</label>
                <input wire:model.blur="global_tax" type="number" class="form-control" name="tax_percentage" min="0" max="100" value="{{ $global_tax }}" required>
            </div>
        </div>
        <div class="col-lg-4" style="display: none;">
            <div class="form-group">
                <label for="discount_percentage">Discount (%)</label>
                <input wire:model.blur="global_discount" type="number" class="form-control" name="discount_percentage" min="0" max="100" value="{{ $global_discount }}" required>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group d-none">
                <label for="shipping_amount">Shipping</label>
                <input wire:model.blur="shipping" type="number" class="form-control" name="shipping_amount" min="0" value="0" required step="0.01">
            </div>
        </div>
    </div>
</div>
