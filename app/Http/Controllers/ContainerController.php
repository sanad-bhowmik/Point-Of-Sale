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
            'status' => 'nullable|numeric|in:0,1,2',
            'qty' => 'nullable|numeric',
        ]);

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
}
