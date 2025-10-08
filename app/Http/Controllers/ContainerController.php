<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContainerController extends Controller
{
    public function view()
    {
        return view('container.addContainer');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'lc_id' => 'required|exists:lc,id',
            'lc_value' => 'nullable|numeric',
            'lc_exchange_rate' => 'nullable|numeric',
            'tt_value' => 'nullable|numeric',
            'tt_exchange_rate' => 'nullable|numeric',
            'name' => 'required|string|max:255',
            'number' => 'required|string|max:100',
            'shipping_date' => 'nullable|date',
            'arriving_date' => 'nullable|date',
            'lc_date' => 'nullable|date',
            'tt_date' => 'nullable|date',
            'status' => 'nullable|numeric|in:0,1,2,3',
            'qty' => 'nullable|numeric',
            'current_qty' => 'nullable|numeric',
        ]);

        // If qty is set, also set current_qty
        if (isset($validated['qty'])) {
            $validated['current_qty'] = $validated['qty'];
        }

        \App\Models\Container::create($validated);

        return redirect()->route('container.view')->with('success', 'Container added successfully!');
    }


    public function containerTbl()
    {
        $containers = \App\Models\Container::with('lc')->get();
        return view('container.viewContainer', compact('containers'));
    }

    public function destroy($id)
    {
        $container = \App\Models\Container::findOrFail($id);
        $container->delete();

        return redirect()->route('container.Tblview')->with('success', 'Container deleted successfully!');
    }

    public function update(Request $request, $id)
    {
        $container = \App\Models\Container::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'number' => 'required|string|max:100',
            'shipping_date' => 'nullable|date',
            'arriving_date' => 'nullable|date',
            'status' => 'required|integer|in:0,1,2',
        ]);

        $container->update($validated);

        return redirect()->back()->with('success', 'Container updated successfully!');
    }
    public function supplierTtLc(Request $request)
    {
        $supplierId = $request->supplier_id;

        $query = \App\Models\Container::with(['lc.costing.supplier', 'lc.costing.product.sizes']);

        if ($supplierId) {
            $query->whereHas('lc.costing.supplier', function ($q) use ($supplierId) {
                $q->where('id', $supplierId);
            });
        }

        $containers = $query->get();

        // Fetch all suppliers for the dropdown
        $suppliers = \App\Models\Supplier::orderBy('supplier_name')->get();

        // Fetch all banks for the payment modals
        $banks = \App\Models\Bank::orderBy('bank_name')->get();

        return view('container.supplierTtLc', compact('containers', 'suppliers', 'supplierId', 'banks'));
    }
    public function ttPayment(Request $request)
    {
        $request->validate([
            'container_id' => 'required|exists:container,id',
            'amount'       => 'required|numeric|min:0',
            'bank_id'      => 'required|exists:banks,id',
            'date'         => 'required|date',
        ]);

        $container = \App\Models\Container::findOrFail($request->container_id);

        // Update the TT paid amount
        $container->tt_paid_amount += $request->amount;
        $container->save();

        // Create transaction record
        \App\Models\Transaction::create([
            'bank_id'    => $request->bank_id,
            'out_amount' => $request->amount,
            'in_amount'  => 0,
            'purpose'    => 'TT Payment',
            'status'     => 'approved',
            'date'       => $request->date,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'TT payment updated successfully',
            'tt_paid_amount' => $container->tt_paid_amount,
        ]);
    }

    public function lcPayment(Request $request)
    {
        $request->validate([
            'container_id' => 'required|exists:container,id',
            'amount'       => 'required|numeric|min:0',
            'bank_id'      => 'required|exists:banks,id',
            'date'         => 'required|date',
        ]);

        $container = \App\Models\Container::findOrFail($request->container_id);

        // Update the LC paid amount
        $container->lc_paid_amount += $request->amount;
        $container->save();

        // Create transaction record
        \App\Models\Transaction::create([
            'bank_id'    => $request->bank_id,
            'out_amount' => $request->amount,
            'in_amount'  => 0,
            'purpose'    => 'LC Payment',
            'status'     => 'approved',
            'date'       => $request->date,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'LC payment updated successfully',
            'lc_paid_amount' => $container->lc_paid_amount,
        ]);
    }
}
