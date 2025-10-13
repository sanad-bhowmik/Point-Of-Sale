<?php

namespace App\Http\Controllers;

use App\Models\PartiesPayment;
use Illuminate\Http\Request;

class PartiesPaymentController extends Controller
{
    // Show the index page with all payments
    public function index()
    {
        $payments = PartiesPayment::orderBy('date', 'desc')->get();
        return view('partiesPayment.index', compact('payments'));
    }
    public function show()
    {
        $payments = PartiesPayment::orderBy('date', 'desc')->get();
        return view('partiesPayment.show', compact('payments'));
    }

    // Store a new payment
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'usd_amount' => 'required|numeric',
            'exchange_rate' => 'required|numeric',
            'amount' => 'required|numeric',
            'description' => 'nullable|string',
            'date' => 'required|date',
        ]);

        PartiesPayment::create($request->all());

        return redirect()->route('partiesPayment.show')->with('success', 'Payment added successfully.');
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'usd_amount' => 'nullable|numeric',
            'exchange_rate' => 'nullable|numeric',
            'amount' => 'nullable|numeric',
            'description' => 'nullable|string',
            'date' => 'required|date',
        ]);

        $payment = PartiesPayment::findOrFail($id);
        $payment->update([
            'name' => $request->name,
            'usd_amount' => $request->usd_amount,
            'exchange_rate' => $request->exchange_rate,
            'amount' => $request->amount,
            'description' => $request->description,
            'date' => $request->date,
        ]);

        return redirect()->route('partiesPayment.show')->with('success', 'Payment updated successfully.');
    }
    public function destroy($id)
    {
        $payment = PartiesPayment::findOrFail($id);
        $payment->delete();

        return redirect()->route('partiesPayment.index')->with('success', 'Payment deleted successfully.');
    }
}
