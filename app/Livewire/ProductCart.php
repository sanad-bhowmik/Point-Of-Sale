<?php

namespace App\Livewire;

use App\Models\Unit;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Request;
use Livewire\Component;
use Modules\Product\Entities\Product;

class ProductCart extends Component
{

    public $listeners = ['productSelected', 'discountModalRefresh'];

    public $cart_instance;
    public $global_discount;
    public $global_tax;
    public $shipping;
    public $quantity;
    public $check_quantity;
    public $discount_type;
    public $item_discount;
    public $unit_price;
    public $data;
    public $cart_units = [];
    private $product;
    public $cart_sizes = [];
    public $cartInstance = 'sale';
    public $has_insufficient_stock = false;

    public function mount($cartInstance, $data = null)
    {
        $this->cart_instance = $cartInstance;

        if ($data) {
            $this->data = $data;

            $this->global_discount = $data->discount_percentage;
            $this->global_tax = $data->tax_percentage;
            $this->shipping = $data->shipping_amount;

            $this->updatedGlobalTax();
            $this->updatedGlobalDiscount();

            $cart_items = Cart::instance($this->cart_instance)->content();

            foreach ($cart_items as $cart_item) {
                $this->check_quantity[$cart_item->id] = [$cart_item->options->stock];
                $this->quantity[$cart_item->id] = $cart_item->qty;
                $this->unit_price[$cart_item->id] = $cart_item->price;
                $this->discount_type[$cart_item->id] = $cart_item->options->product_discount_type;
                if ($cart_item->options->product_discount_type == 'fixed') {
                    $this->item_discount[$cart_item->id] = $cart_item->options->product_discount;
                } elseif ($cart_item->options->product_discount_type == 'percentage') {
                    $this->item_discount[$cart_item->id] = round(100 * ($cart_item->options->product_discount / $cart_item->price));
                }
            }
        } else {
            $this->global_discount = 0;
            $this->global_tax = 0;
            $this->shipping = 0.00;
            $this->check_quantity = [];
            $this->quantity = [];
            $this->unit_price = [];
            $this->discount_type = [];
            $this->item_discount = [];
        }
    }

    public function render()
    {
        $cart_items = Cart::instance($this->cart_instance)->content();

        // Check stock status for all items
        $this->checkAllStockAvailability();

        return view('livewire.product-cart', [
            'cart_items' => $cart_items,
            'has_insufficient_stock' => $this->has_insufficient_stock
        ]);
    }

    public function checkAllStockAvailability()
    {
        $this->has_insufficient_stock = false;
        $cart_items = Cart::instance($this->cart_instance)->content();

        foreach ($cart_items as $cart_item) {
            $container_id = $cart_item->options->container_id ?? null;
            $product_id = $cart_item->id;
            $requested_quantity = $this->quantity[$product_id] ?? $cart_item->qty;

            if ($container_id) {
                $container = \App\Models\Container::find($container_id);
                $container_quantity = $container ? $container->current_qty : 0; // Changed from qty to current_qty

                if ($container_quantity < $requested_quantity) {
                    $this->has_insufficient_stock = true;

                    // ✅ Use Livewire v3 dispatch
                    $this->dispatch('consoleLog', [
                        'message' => "Insufficient stock! Product ID: {$product_id}, Requested: {$requested_quantity}, Available: {$container_quantity}"
                    ]);

                    break;
                }
            }
        }
    }

    public function updateSize($rowId)
    {
        if (isset($this->cart_sizes[$rowId])) {
            $cartItem = Cart::instance($this->cartInstance)->get($rowId);

            // Ensure options is an array
            $options = is_array($cartItem->options) ? $cartItem->options : $cartItem->options->toArray();
            $options['size_id'] = $this->cart_sizes[$rowId];

            Cart::instance($this->cartInstance)->update($rowId, [
                'options' => $options
            ]);
        }
    }

    public function updateUnit($rowId)
    {
        $unitId = $this->cart_units[$rowId] ?? null;

        if ($unitId) {
            $unit = Unit::find($unitId);

            if ($unit) {
                $cart_item = Cart::instance($this->cart_instance)->get($rowId);

                Cart::instance($this->cart_instance)->update($rowId, [
                    'options' => array_merge((array) $cart_item->options->toArray(), [
                        'unit_id'    => $unit->id,
                        'unit_name'  => $unit->name,
                        'unit_short' => $unit->short_name,
                    ])
                ]);
            }
        }

        // Show only unit_id for each cart item
        $unitIds = Cart::instance($this->cart_instance)->content()->mapWithKeys(function ($item) {
            return [$item->rowId => $item->options->unit_id ?? null];
        });

        // dd($unitIds);
    }

    public function productSelected($payload)
    {
        // Extract product, LC, and container from payload
        $product = is_object($payload['product']) ? (array) $payload['product'] : $payload['product'];
        $lc_id = $payload['lc_id'] ?? null;
        $container_id = $payload['container_id'] ?? null;

        $cart = Cart::instance($this->cart_instance);

        // Check if product already in cart
        $exists = $cart->search(function ($cartItem, $rowId) use ($product) {
            return $cartItem->id == $product['id'];
        });

        if ($exists->isNotEmpty()) {
            session()->flash('message', 'Product exists in the cart!');
            return;
        }

        $this->product = $product;

        // Get container quantity instead of product quantity
        $container_quantity = 0;
        if ($container_id) {
            $container = \App\Models\Container::find($container_id);
            $container_quantity = $container ? $container->current_qty : 0; // Changed from qty to current_qty
        }

        // Add product to cart including LC and container IDs
        $cart->add([
            'id'      => $product['id'],
            'name'    => $product['product_name'],
            'qty'     => 1,
            'price'   => $this->calculate($product)['price'],
            'weight'  => 1,
            'options' => [
                'product_discount'      => 0.00,
                'product_discount_type' => 'fixed',
                'sub_total'             => $this->calculate($product)['sub_total'],
                'code'                  => $product['product_code'],
                'stock'                 => $container_quantity, // Use container quantity instead of product quantity
                'unit'                  => $product['product_unit'] ?? null,
                'product_tax'           => $this->calculate($product)['product_tax'],
                'unit_price'            => $this->calculate($product)['unit_price'],
                'lc_id'                 => $lc_id,
                'container_id'          => $container_id,
            ]
        ]);

        $this->check_quantity[$product['id']] = $container_quantity; // Use container quantity for validation
        $this->quantity[$product['id']] = 1;
        $this->discount_type[$product['id']] = 'fixed';
        $this->item_discount[$product['id']] = 0;
        $this->checkAllStockAvailability();
    }

    public function removeItem($row_id)
    {
        $cart_item = Cart::instance($this->cart_instance)->get($row_id);
        Cart::instance($this->cart_instance)->remove($row_id);

        // Remove from local arrays
        if (isset($this->check_quantity[$cart_item->id])) {
            unset($this->check_quantity[$cart_item->id]);
        }
        if (isset($this->quantity[$cart_item->id])) {
            unset($this->quantity[$cart_item->id]);
        }

        $this->checkAllStockAvailability(); // Update the stock status after removal
    }

    public function updatedGlobalTax()
    {
        Cart::instance($this->cart_instance)->setGlobalTax((int)$this->global_tax);
    }

    public function updatedGlobalDiscount()
    {
        Cart::instance($this->cart_instance)->setGlobalDiscount((int)$this->global_discount);
    }

    public function updateQuantity($row_id, $product_id)
    {
        // Get the cart item to access container_id
        $cart_item = Cart::instance($this->cart_instance)->get($row_id);
        $container_id = $cart_item->options->container_id ?? null;

        // Get container quantity for validation
        $container_quantity = 0;
        if ($container_id) {
            $container = \App\Models\Container::find($container_id);
            $container_quantity = $container ? $container->current_qty : 0; // Changed from qty to current_qty
        }

        if ($this->cart_instance == 'sale' || $this->cart_instance == 'purchase_return') {
            if ($container_quantity < $this->quantity[$product_id]) {
                session()->flash('message', 'The requested quantity is not available in container stock.');
                $this->checkAllStockAvailability(); // Update the stock status
                return;
            }
        }

        Cart::instance($this->cart_instance)->update($row_id, $this->quantity[$product_id]);

        // Update the stock value in cart options to reflect current container quantity
        Cart::instance($this->cart_instance)->update($row_id, [
            'options' => array_merge((array)$cart_item->options, [
                'sub_total'             => $cart_item->price * $cart_item->qty,
                'code'                  => $cart_item->options->code,
                'stock'                 => $container_quantity,
                'unit'                  => $cart_item->options->unit,
                'product_tax'           => $cart_item->options->product_tax,
                'unit_price'            => $cart_item->options->unit_price,
                'product_discount'      => $cart_item->options->product_discount,
                'product_discount_type' => $cart_item->options->product_discount_type,
                'lc_id'                 => $cart_item->options->lc_id ?? null,
                'container_id'          => $cart_item->options->container_id ?? null,
            ])
        ]);

        $this->check_quantity[$product_id] = $container_quantity;
        $this->checkAllStockAvailability(); // Update the stock status after quantity change
    }

    public function updatedDiscountType($value, $name)
    {
        $this->item_discount[$name] = 0;
    }

    public function discountModalRefresh($product_id, $row_id)
    {
        $this->updateQuantity($row_id, $product_id);
    }

    public function setProductDiscount($row_id, $product_id)
    {
        $cart_item = Cart::instance($this->cart_instance)->get($row_id);

        if ($this->discount_type[$product_id] == 'fixed') {
            Cart::instance($this->cart_instance)
                ->update($row_id, [
                    'price' => ($cart_item->price + $cart_item->options->product_discount) - $this->item_discount[$product_id]
                ]);

            $discount_amount = $this->item_discount[$product_id];

            $this->updateCartOptions($row_id, $product_id, $cart_item, $discount_amount);
        } elseif ($this->discount_type[$product_id] == 'percentage') {
            $discount_amount = ($cart_item->price + $cart_item->options->product_discount) * ($this->item_discount[$product_id] / 100);

            Cart::instance($this->cart_instance)
                ->update($row_id, [
                    'price' => ($cart_item->price + $cart_item->options->product_discount) - $discount_amount
                ]);

            $this->updateCartOptions($row_id, $product_id, $cart_item, $discount_amount);
        }

        session()->flash('discount_message' . $product_id, 'Discount added to the product!');
    }

    public function updatePrice($row_id, $product_id)
    {
        $product = Product::findOrFail($product_id);
        $cart_item = Cart::instance($this->cart_instance)->get($row_id);

        Cart::instance($this->cart_instance)->update($row_id, ['price' => $this->unit_price[$product['id']]]);

        $calculated = $this->calculate($product, $this->unit_price[$product['id']]);

        // Preserve all existing options while updating only the necessary fields
        Cart::instance($this->cart_instance)->update($row_id, [
            'options' => array_merge((array)$cart_item->options, [
                'sub_total'             => $calculated['sub_total'],
                'code'                  => $cart_item->options->code,
                'stock'                 => $cart_item->options->stock,
                'unit'                  => $cart_item->options->unit,
                'product_tax'           => $calculated['product_tax'],
                'unit_price'            => $calculated['unit_price'],
                'product_discount'      => $cart_item->options->product_discount,
                'product_discount_type' => $cart_item->options->product_discount_type,
                // Keep existing lc_id and container_id
                'lc_id'                 => $cart_item->options->lc_id ?? null,
                'container_id'          => $cart_item->options->container_id ?? null,
            ])
        ]);
    }

    public function calculate($product, $new_price = null)
    {
        if ($new_price) {
            $product_price = $new_price;
        } else {
            $this->unit_price[$product['id']] = $product['product_price'];
            if ($this->cart_instance == 'purchase' || $this->cart_instance == 'purchase_return') {
                $this->unit_price[$product['id']] = $product['product_cost'];
            }
            $product_price = $this->unit_price[$product['id']];
        }
        $price = 0;
        $unit_price = 0;
        $product_tax = 0;
        $sub_total = 0;

        if ($product['product_tax_type'] == 1) {
            $price = $product_price + ($product_price * ($product['product_order_tax'] / 100));
            $unit_price = $product_price;
            $product_tax = $product_price * ($product['product_order_tax'] / 100);
            $sub_total = $product_price + ($product_price * ($product['product_order_tax'] / 100));
        } elseif ($product['product_tax_type'] == 2) {
            $price = $product_price;
            $unit_price = $product_price - ($product_price * ($product['product_order_tax'] / 100));
            $product_tax = $product_price * ($product['product_order_tax'] / 100);
            $sub_total = $product_price;
        } else {
            $price = $product_price;
            $unit_price = $product_price;
            $product_tax = 0.00;
            $sub_total = $product_price;
        }

        return ['price' => $price, 'unit_price' => $unit_price, 'product_tax' => $product_tax, 'sub_total' => $sub_total];
    }

    public function updateCartOptions($row_id, $product_id, $cart_item, $discount_amount)
    {
        // Preserve all existing options while updating only the necessary fields
        Cart::instance($this->cart_instance)->update($row_id, [
            'options' => array_merge((array)$cart_item->options, [
                'sub_total'             => $cart_item->price * $cart_item->qty,
                'code'                  => $cart_item->options->code,
                'stock'                 => $cart_item->options->stock,
                'unit'                  => $cart_item->options->unit,
                'product_tax'           => $cart_item->options->product_tax,
                'unit_price'            => $cart_item->options->unit_price,
                'product_discount'      => $discount_amount,
                'product_discount_type' => $this->discount_type[$product_id],
                // Keep existing lc_id and container_id
                'lc_id'                 => $cart_item->options->lc_id ?? null,
                'container_id'          => $cart_item->options->container_id ?? null,
            ])
        ]);
    }
}
