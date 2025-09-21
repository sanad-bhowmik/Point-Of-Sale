<?php

namespace App\Http\Controllers;

use App\Models\InputPermit;
use Illuminate\Http\Request;

class InputPermitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = InputPermit::query();

        // Search by reference
        if ($request->filled('reference')) {
            $query->where('reference', 'like', '%' . $request->reference . '%');
        }

        // Search by date
        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        // Order by creation date and paginate (10 per page)
        $inputPermits = $query->orderBy('created_at', 'desc')->paginate(10);

        // Preserve search query in pagination links
        $inputPermits->appends($request->all());

        return view('inputPermit.viewInputPermit', compact('inputPermits'));
    }

    public function create()
    {
        return view('inputPermit.addInputPermit');
    }



    public function store(Request $request)
    {
        // Optional: Basic validation
        $validated = $request->validate([
            'date' => 'nullable|date',
            'quantity' => 'nullable|numeric',
            'status' => 'nullable|in:0,1,2',
        ]);

        // Create new InputPermit record
        $inputPermit = new InputPermit();
        $inputPermit->to_text = $request->to;
        $inputPermit->reference = $request->reference;
        $inputPermit->no = $request->no;
        $inputPermit->date = $request->date;
        $inputPermit->importer_name = $request->importer_name;
        $inputPermit->importer_address = $request->importer_address;
        $inputPermit->means_of_transport = $request->means_of_transport;
        $inputPermit->consignor_name = $request->consignor_name;
        $inputPermit->consignor_address = $request->consignor_address;
        $inputPermit->country_of_origin = $request->country_of_origin;
        $inputPermit->country_of_export = $request->country_of_export;
        $inputPermit->point_of_entry = $request->point_of_entry;
        $inputPermit->plant_name_and_products = $request->plant_name;
        $inputPermit->variety_or_category = $request->variety_category;
        $inputPermit->pack_size = $request->pack_size;
        $inputPermit->quantity = $request->quantity;
        $inputPermit->status = $request->status;

        $inputPermit->save();

        return redirect()->route('input_permit.create')
            ->with('success', 'Input Permit added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'to_text' => 'nullable|string',
            'reference' => 'nullable|string',
            'no' => 'nullable|string',
            'date' => 'nullable|date',
            'importer_name' => 'nullable|string',
            'importer_address' => 'nullable|string',
            'means_of_transport' => 'nullable|string',
            'consignor_name' => 'nullable|string',
            'consignor_address' => 'nullable|string',
            'country_of_origin' => 'nullable|string',
            'country_of_export' => 'nullable|string',
            'point_of_entry' => 'nullable|string',
            'plant_name_and_products' => 'nullable|string',
            'variety_or_category' => 'nullable|string',
            'pack_size' => 'nullable|string',
            'quantity' => 'nullable|numeric',
            'status' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        ]);


        try {
            $permit = \App\Models\InputPermit::find($id);
            if (!$permit) {
                // dd("Input Permit not found for ID: $id");
            }

            $permit->to_text = $request->to;
            $permit->reference = $request->reference;
            $permit->no = $request->no;
            $permit->date = $request->date;
            $permit->importer_name = $request->importer_name;
            $permit->importer_address = $request->importer_address;
            $permit->means_of_transport = $request->means_of_transport;
            $permit->consignor_name = $request->consignor_name;
            $permit->consignor_address = $request->consignor_address;
            $permit->country_of_origin = $request->country_of_origin;
            $permit->country_of_export = $request->country_of_export;
            $permit->point_of_entry = $request->point_of_entry;
            $permit->plant_name_and_products = $request->plant_name;
            $permit->variety_or_category = $request->variety_category;
            $permit->pack_size = $request->pack_size;
            $permit->quantity = $request->quantity;
            $permit->status = $request->status;

            if ($request->hasFile('attachment')) {
                if ($permit->attachment && file_exists(public_path($permit->attachment))) {
                    unlink(public_path($permit->attachment));
                }
                $file = $request->file('attachment');
                $path = $file->store('uploads/input_permits', 'public');
                $permit->attachment = '/storage/' . $path;
            }


            $permit->save();

            return redirect()->route('input_permit.view')->with('success', 'Input permit updated successfully.');
        } catch (\Exception $e) {
            dd('Update Failed', $e->getMessage(), $e->getTraceAsString());
            return redirect()->route('input_permit.view')->with('error', 'Failed to update input permit.');
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $permit = \App\Models\InputPermit::findOrFail($id); // Find the record
            // Optionally, delete the attachment file if exists
            if ($permit->attachment && file_exists(public_path($permit->attachment))) {
                unlink(public_path($permit->attachment));
            }
            $permit->delete(); // Delete the record

            return redirect()->route('input_permit.view')->with('success', 'Input permit deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('input_permit.view')->with('error', 'Failed to delete input permit.');
        }
    }
}
