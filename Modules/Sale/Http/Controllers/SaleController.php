<?php

namespace Modules\Sale\Http\Controllers;

use App\Models\Container;
use Modules\Sale\DataTables\SalesDataTable;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Modules\People\Entities\Customer;
use Modules\Product\Entities\Product;
use Modules\Sale\Entities\Sale;
use Modules\Sale\Entities\SaleDetails;
use Modules\Sale\Entities\SalePayment;
use Modules\Sale\Http\Requests\StoreSaleRequest;
use Modules\Sale\Http\Requests\UpdateSaleRequest;

class SaleController extends Controller
{


    public function index(SalesDataTable $dataTable)
    {
        abort_if(Gate::denies('access_sales'), 403);

        return $dataTable->render('sale::index');
    }


    public function create()
    {
        abort_if(Gate::denies('create_sales'), 403);

        Cart::instance('sale')->destroy();

        return view('sale::create');
    }


    public function store(StoreSaleRequest $request)
    {
        DB::transaction(function () use ($request) {
            $due_amount = $request->total_amount - $request->paid_amount;

            if ($due_amount == $request->total_amount) {
                $payment_status = 'Unpaid';
            } elseif ($due_amount > 0) {
                $payment_status = 'Partial';
            } else {
                $payment_status = 'Paid';
            }

            // Get first cart item for lc_id and container_id
            $firstCartItem = Cart::instance('sale')->content()->first();
            $lc_id = $firstCartItem->options['lc_id'] ?? null;
            $container_id = $firstCartItem->options['container_id'] ?? null;

            // Create Sale (also save lc_id and container_id)
            $sale = Sale::create([
                'date' => $request->date,
                'customer_id' => $request->customer_id,
                'customer_name' => Customer::findOrFail($request->customer_id)->customer_name,
                'tax_percentage' => $request->tax_percentage,
                'discount_percentage' => $request->discount_percentage,
                'shipping_amount' => $request->shipping_amount,
                'paid_amount' => $request->paid_amount,
                'total_amount' => $request->total_amount,
                'due_amount' => $due_amount,
                'status' => $request->status,
                'payment_status' => $payment_status,
                'payment_method' => $request->payment_method,
                'note' => $request->note,
                'tax_amount' => Cart::instance('sale')->tax() * 100,
                'discount_amount' => Cart::instance('sale')->discount() * 100,
                'lc_id' => $lc_id,
                'container_id' => $container_id,
            ]);

            // Track container quantities to update
            $containerQuantities = [];

            foreach (Cart::instance('sale')->content() as $cart_item) {
                SaleDetails::create([
                    'sale_id' => $sale->id,
                    'product_id' => $cart_item->id,
                    'product_name' => $cart_item->name,
                    'product_code' => $cart_item->options->code,
                    'quantity' => $cart_item->qty,
                    'price' => $cart_item->price,
                    'unit_price' => $cart_item->options->unit_price,
                    'sub_total' => $cart_item->options->sub_total,
                    'product_discount_amount' => $cart_item->options->product_discount * 100,
                    'product_discount_type' => $cart_item->options->product_discount_type,
                    'product_tax_amount' => $cart_item->options->product_tax * 100,
                    'size_id' => $cart_item->options['size_id'] ?? null,
                    'lc_id' => $cart_item->options['lc_id'] ?? null,
                    'container_id' => $cart_item->options['container_id'] ?? null,
                ]);

                // Update container quantity based on the equation: stock - quantity = updated value
                $container_id = $cart_item->options['container_id'] ?? null;
                if ($container_id) {
                    if (!isset($containerQuantities[$container_id])) {
                        $container = Container::find($container_id);
                        if ($container) {
                            $containerQuantities[$container_id] = [
                                'current_qty' => $container->qty,
                                'total_deduct' => 0
                            ];
                        }
                    }

                    if (isset($containerQuantities[$container_id])) {
                        $containerQuantities[$container_id]['total_deduct'] += $cart_item->qty;
                    }
                }

                if ($request->status == 'Shipped' || $request->status == 'Completed') {
                    $product = Product::findOrFail($cart_item->id);
                    $product->update([
                        'product_quantity' => $product->product_quantity - $cart_item->qty
                    ]);
                }
            }

            // Update container quantities in database
            foreach ($containerQuantities as $containerId => $quantityData) {
                $new_qty = $quantityData['current_qty'] - $quantityData['total_deduct'];
                if ($new_qty < 0) {
                    $new_qty = 0; // Prevent negative quantities
                }

                Container::where('id', $containerId)->update(['qty' => $new_qty]);
            }

            Cart::instance('sale')->destroy();

            if ($sale->paid_amount > 0) {
                SalePayment::create([
                    'date' => $request->date,
                    'reference' => 'INV/' . $sale->reference,
                    'amount' => $sale->paid_amount,
                    'sale_id' => $sale->id,
                    'payment_method' => $request->payment_method
                ]);
            }
        });

        toast('Sale Created!', 'success');

        return redirect()->route('sales.index');
    }

    public function show(Sale $sale)
    {
        abort_if(Gate::denies('show_sales'), 403);

        $customer = Customer::findOrFail($sale->customer_id);

        return view('sale::show', compact('sale', 'customer'));
    }


    public function edit(Sale $sale)
    {
        abort_if(Gate::denies('edit_sales'), 403);

        $sale_details = $sale->saleDetails;

        Cart::instance('sale')->destroy();

        $cart = Cart::instance('sale');

        foreach ($sale_details as $sale_detail) {
            $cart->add([
                'id'      => $sale_detail->product_id,
                'name'    => $sale_detail->product_name,
                'qty'     => $sale_detail->quantity,
                'price'   => $sale_detail->price,
                'weight'  => 1,
                'options' => [
                    'product_discount' => $sale_detail->product_discount_amount,
                    'product_discount_type' => $sale_detail->product_discount_type,
                    'sub_total'   => $sale_detail->sub_total,
                    'code'        => $sale_detail->product_code,
                    'stock'       => Product::findOrFail($sale_detail->product_id)->product_quantity,
                    'product_tax' => $sale_detail->product_tax_amount,
                    'unit_price'  => $sale_detail->unit_price,
                    'lc_id'       => $sale_detail->lc_id,
                    'container_id' => $sale_detail->container_id,
                ]
            ]);
        }

        return view('sale::edit', compact('sale'));
    }

    public function update(UpdateSaleRequest $request, Sale $sale)
    {
        DB::transaction(function () use ($request, $sale) {

            $due_amount = $request->total_amount - $request->paid_amount;

            if ($due_amount == $request->total_amount) {
                $payment_status = 'Unpaid';
            } elseif ($due_amount > 0) {
                $payment_status = 'Partial';
            } else {
                $payment_status = 'Paid';
            }

            // Restore container quantities before updating
            $containerQuantitiesToRestore = [];
            foreach ($sale->saleDetails as $sale_detail) {
                $container_id = $sale_detail->container_id;
                if ($container_id) {
                    if (!isset($containerQuantitiesToRestore[$container_id])) {
                        $containerQuantitiesToRestore[$container_id] = 0;
                    }
                    $containerQuantitiesToRestore[$container_id] += $sale_detail->quantity;
                }

                if ($sale->status == 'Shipped' || $sale->status == 'Completed') {
                    $product = Product::findOrFail($sale_detail->product_id);
                    $product->update([
                        'product_quantity' => $product->product_quantity + $sale_detail->quantity
                    ]);
                }
                $sale_detail->delete();
            }

            // Restore quantities to containers
            foreach ($containerQuantitiesToRestore as $containerId => $quantityToRestore) {
                $container = Container::find($containerId);
                if ($container) {
                    $new_qty = $container->qty + $quantityToRestore;
                    $container->update(['qty' => $new_qty]);
                }
            }

            $sale->update([
                'date' => $request->date,
                'reference' => $request->reference,
                'customer_id' => $request->customer_id,
                'customer_name' => Customer::findOrFail($request->customer_id)->customer_name,
                'tax_percentage' => $request->tax_percentage,
                'discount_percentage' => $request->discount_percentage,
                'shipping_amount' => $request->shipping_amount * 100,
                'paid_amount' => $request->paid_amount * 100,
                'total_amount' => $request->total_amount * 100,
                'due_amount' => $due_amount * 100,
                'status' => $request->status,
                'payment_status' => $payment_status,
                'payment_method' => $request->payment_method,
                'note' => $request->note,
                'tax_amount' => Cart::instance('sale')->tax() * 100,
                'discount_amount' => Cart::instance('sale')->discount() * 100,
            ]);

            // Track new container quantities to deduct
            $containerQuantitiesToDeduct = [];

            foreach (Cart::instance('sale')->content() as $cart_item) {
                SaleDetails::create([
                    'sale_id' => $sale->id,
                    'product_id' => $cart_item->id,
                    'product_name' => $cart_item->name,
                    'product_code' => $cart_item->options->code,
                    'quantity' => $cart_item->qty,
                    'price' => $cart_item->price * 100,
                    'unit_price' => $cart_item->options->unit_price * 100,
                    'sub_total' => $cart_item->options->sub_total * 100,
                    'product_discount_amount' => $cart_item->options->product_discount * 100,
                    'product_discount_type' => $cart_item->options->product_discount_type,
                    'product_tax_amount' => $cart_item->options->product_tax * 100,
                    'lc_id' => $cart_item->options['lc_id'] ?? null,
                    'container_id' => $cart_item->options['container_id'] ?? null,
                ]);

                // Track container quantities for deduction
                $container_id = $cart_item->options['container_id'] ?? null;
                if ($container_id) {
                    if (!isset($containerQuantitiesToDeduct[$container_id])) {
                        $containerQuantitiesToDeduct[$container_id] = 0;
                    }
                    $containerQuantitiesToDeduct[$container_id] += $cart_item->qty;
                }

                if ($request->status == 'Shipped' || $request->status == 'Completed') {
                    $product = Product::findOrFail($cart_item->id);
                    $product->update([
                        'product_quantity' => $product->product_quantity - $cart_item->qty
                    ]);
                }
            }

            // Deduct quantities from containers
            foreach ($containerQuantitiesToDeduct as $containerId => $quantityToDeduct) {
                $container = Container::find($containerId);
                if ($container) {
                    $new_qty = $container->qty - $quantityToDeduct;
                    if ($new_qty < 0) {
                        $new_qty = 0;
                    }
                    $container->update(['qty' => $new_qty]);
                }
            }

            Cart::instance('sale')->destroy();
        });

        toast('Sale Updated!', 'info');

        return redirect()->route('sales.index');
    }

    public function destroy(Sale $sale)
    {
        abort_if(Gate::denies('delete_sales'), 403);

        DB::transaction(function () use ($sale) {
            // Restore container quantities before deleting
            $containerQuantitiesToRestore = [];
            foreach ($sale->saleDetails as $sale_detail) {
                $container_id = $sale_detail->container_id;
                if ($container_id) {
                    if (!isset($containerQuantitiesToRestore[$container_id])) {
                        $containerQuantitiesToRestore[$container_id] = 0;
                    }
                    $containerQuantitiesToRestore[$container_id] += $sale_detail->quantity;
                }

                // Also restore product quantities if sale was shipped/completed
                if ($sale->status == 'Shipped' || $sale->status == 'Completed') {
                    $product = Product::findOrFail($sale_detail->product_id);
                    $product->update([
                        'product_quantity' => $product->product_quantity + $sale_detail->quantity
                    ]);
                }
            }

            // Restore quantities to containers
            foreach ($containerQuantitiesToRestore as $containerId => $quantityToRestore) {
                $container = Container::find($containerId);
                if ($container) {
                    $new_qty = $container->qty + $quantityToRestore;
                    $container->update(['qty' => $new_qty]);
                }
            }

            $sale->delete();
        });

        toast('Sale Deleted!', 'warning');

        return redirect()->route('sales.index');
    }
}
