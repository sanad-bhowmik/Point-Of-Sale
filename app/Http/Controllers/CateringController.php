<?php

namespace App\Http\Controllers;

use App\Imports\CateringLunchImport;
use App\Models\CateringLunch;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class CateringController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $lunches = CateringLunch::orderBy('id', 'desc');

        if ($request->filled('date_range')) {
            [$from, $to] = explode(' - ', $request->date_range);
            $lunches->whereBetween('date', [$from, $to]);
        }

        $lunches = $lunches->get();

        return view('cateringLunch.index', compact('lunches'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('cateringLunch.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->hasFile('excel_file')) {
            $request->validate([
                'excel_file' => 'required|mimes:xlsx,xls,csv|max:2048',
            ]);

            try {
                Excel::import(new CateringLunchImport, $request->file('excel_file'));

                 return redirect()->route('catering.index')->with('success', 'Catering lunches imported successfully from Excel!');
            } catch (\Throwable $th) {
                return redirect()->back()->with('error', 'An error occurred while saving the lunch entry: ' . $th->getMessage())->withInput();
            }

        }

        $request->validate([
            'date' => 'required|date',
            'note' => 'nullable|string',
            'quantity' => 'required|string',
            'unit_price' => 'required|string',
            'total' => 'required|numeric|min:1',
        ]);

        try {

            CateringLunch::create([
                'date' => Carbon::parse($request->input('date'))->format('Y-m-d'),
                'note' => $request->input('note'),
                'quantity' => $request->input('quantity'),
                'unit_price' => $request->input('unit_price'),
                'total' => $request->input('total'),
            ]);

            return redirect()->route('catering.index')->with('success', 'Lunch entry added successfully.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'An error occurred while saving the lunch entry: ' . $th->getMessage())->withInput();
        }
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
    public function edit(CateringLunch $lunch)
    {
        return view('cateringLunch.update', compact('lunch'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CateringLunch $lunch)
    {
        $request->validate([
            'date' => 'required|date',
            'note' => 'nullable|string',
            'quantity' => 'required|string|min:0',
            'unit_price' => 'required|string|min:0',
            'total' => 'required|numeric|min:0',
        ]);

        try {

            $lunch->update([
                'date' => Carbon::parse($request->input('date'))->format('Y-m-d'),
                'note' => $request->input('note'),
                'quantity' => $request->input('quantity'),
                'unit_price' => $request->input('unit_price'),
                'total' => $request->input('total'),
            ]);

            return redirect()->route('catering.index')->with('success', 'Lunch entry updated successfully.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'An error occurred while updating the lunch entry: ' . $th->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CateringLunch $lunch)
    {
        try {
            $lunch->delete();
            return redirect()->route('catering.index')->with('success', 'Lunch entry deleted successfully.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'An error occurred while deleting the lunch entry: ' . $th->getMessage());
        }
    }
}
