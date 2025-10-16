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
            'type' => 'required|string|in:normal,damarage',
            'amount' => 'nullable|numeric',
            'damarage_amount' => 'nullable|numeric',
            'description' => 'nullable|string',
            'date' => 'required|date',
        ]);

        // Prepare the data for insertion
        $data = [
            'name' => $request->name,
            'usd_amount' => $request->usd_amount,
            'exchange_rate' => $request->exchange_rate,
            'description' => $request->description,
            'date' => $request->date,
            'status' => 'pending', // you can adjust this if needed
        ];

        if ($request->type === 'damarage') {
            // Save damarage amount
            $data['damarage_amount'] = $request->damarage_amount;
            $data['amount'] = 0;
        } else {
            // Save normal amount
            $data['amount'] = $request->amount;
            $data['damarage_amount'] = 0;
        }

        PartiesPayment::create($data);

        return redirect()->route('partiesPayment.show')
            ->with('success', 'Payment added successfully.');
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'usd_amount' => 'nullable|numeric',
            'exchange_rate' => 'nullable|numeric',
            'amount' => 'nullable|numeric',
            'damarage_amount' => 'nullable|numeric',
            'description' => 'nullable|string',
            'date' => 'required|date',
        ]);

        $payment = PartiesPayment::findOrFail($id);

        // Update the payment with both amount fields
        $payment->update([
            'name' => $request->name,
            'usd_amount' => $request->usd_amount,
            'exchange_rate' => $request->exchange_rate,
            'amount' => $request->amount, // normal amount
            'damarage_amount' => $request->damarage_amount, // damarage amount
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
