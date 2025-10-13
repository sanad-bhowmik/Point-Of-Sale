<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Investment;

class InvestmentController extends Controller
{
    // Display all investments
    public function index()
    {
        $investments = Investment::all();
        return view('investment.show', compact('investments')); // show.blade.php
    }

    // Show create form
    public function create()
    {
        return view('investment.index');
    }

    // Store new investment
    public function store(Request $request)
    {
        //  dd($request->all());
        $request->validate([
            'lc_number' => 'required|string|max:255',
            'date' => 'required|date',
            'description' => 'nullable|string',
            'investment' => 'required|string|in:Invest,Expense,Profit,Cash Invest',
            'amount' => 'required|numeric',
        ]);

        Investment::create([
            'lc_number'   => $request->lc_number,   // Only LC Number here
            'date'        => $request->date,
            'description' => $request->description, // Goes to description column
            'investment'  => $request->investment,
            'amount'      => $request->amount,
        ]);

        return redirect()->route('investment.index')->with('success', 'Investment added successfully.');
    }



    // Show edit form
    public function edit($id)
    {
        $investment = Investment::findOrFail($id);
        return view('investment.edit', compact('investment'));
    }

    // Update investment
    public function update(Request $request, $id)
    {
        $request->validate([
            'lc_number' => 'required|string|max:255',
            'date' => 'required|date',  // new validation
            'description' => 'required|string',
            'investment' => 'required|string|in:Invest,Expense,Profit,Cash Invest',
            'amount' => 'required|numeric',
        ]);

        $investment = Investment::findOrFail($id);
        $investment->update($request->only('lc_number', 'date', 'description', 'investment', 'amount'));

        return redirect()->route('investment.index')->with('success', 'Investment updated successfully.');
    }


    // Delete investment
    public function destroy($id)
    {
        $investment = Investment::findOrFail($id);
        $investment->delete();

        return redirect()->route('investment.index')->with('success', 'Investment deleted successfully.');
    }
}
