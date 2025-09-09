<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Size;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function createSize()
    {
        $products = Product::all();
        return view('size.createSize', compact('products'));
    }

    public function storeSize(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'size'       => 'required|string|max:255',
            'status'     => 'nullable|in:0,1',
        ]);

        $sizes = explode(',', $request->size);
        $sizes = array_map('trim', $sizes); // remove spaces

        $productId = $request->product_id;
        $addedSizes = [];
        $existingSizes = [];

        foreach ($sizes as $sizeValue) {
            $exists = \App\Models\Size::where('product_id', $productId)
                ->where('size', $sizeValue)
                ->exists();

            if ($exists) {
                $existingSizes[] = $sizeValue;
            } else {
                \App\Models\Size::create([
                    'product_id' => $productId,
                    'size'       => $sizeValue,
                    'status'     => $request->status ?? 1,
                ]);
                $addedSizes[] = $sizeValue;
            }
        }

        $messages = [];
        if ($addedSizes) {
            $messages['success'] = 'Size(s) ' . implode(', ', $addedSizes) . ' added successfully!';
        }
        if ($existingSizes) {
            $messages['warning'] = 'Size(s) ' . implode(', ', $existingSizes) . ' already exist for this product.';
        }

        return redirect()->back()->with($messages);
    }
    public function viewSize()
    {
        // Only get products that have at least one size
        $products = \App\Models\Product::whereHas('sizes')
            ->with('sizes') // eager load sizes
            ->orderBy('id', 'desc')
            ->get();

        return view('size.viewSize', compact('products'));
    }


    public function destroySize($id)
    {
        $size = \App\Models\Size::find($id);

        if (!$size) {
            return redirect()->back()->with('error', 'Size not found.');
        }

        $size->delete();

        return redirect()->back()->with('success', 'Size deleted successfully!');
    }
    public function updateSize(Request $request, $id)
    {
        $request->validate([
            'size' => 'required|string|max:255',
        ]);

        $size = \App\Models\Size::findOrFail($id);
        $size->size = $request->size;
        $size->save();

        return redirect()->back()->with('success', 'Size updated successfully!');
    }
}
