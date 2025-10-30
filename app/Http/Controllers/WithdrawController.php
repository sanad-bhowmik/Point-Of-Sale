<?php

namespace App\Http\Controllers;

use App\Models\Withdraw;
use Illuminate\Http\Request;

class WithdrawController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('withdraw.index');
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'transaction_type' => 'required|in:cash_in_amount,cash_withdraw_amount',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $withdraw = new Withdraw();
        $withdraw->description = $validated['description'] ?? null;

        // Assign the amount to the correct column
        if ($validated['transaction_type'] === 'cash_in_amount') {
            $withdraw->cash_in_amount = $validated['amount'];
            $withdraw->cash_withdraw_amount = 0;
        } else {
            $withdraw->cash_withdraw_amount = $validated['amount'];
            $withdraw->cash_in_amount = 0;
        }

        // Optional: set status or timestamps automatically
        $withdraw->status = 1; // Example status
        $withdraw->save();

        return redirect()->back()->with('success', 'Transaction saved successfully!');
    }
    public function show()
    {
        $withdraws = Withdraw::orderBy('id', 'desc')->get();
        return view('withdraw.show', compact('withdraws'));
    }
    public function destroy($id)
    {
        $withdraw = Withdraw::findOrFail($id);
        $withdraw->delete();

        return redirect()->back()->with('success', 'Transaction deleted successfully!');
    }
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'cash_in_amount' => 'nullable|numeric|min:0',
            'cash_withdraw_amount' => 'nullable|numeric|min:0',
        ]);

        $withdraw = Withdraw::findOrFail($id);
        $withdraw->cash_in_amount = $validated['cash_in_amount'] ?? 0;
        $withdraw->cash_withdraw_amount = $validated['cash_withdraw_amount'] ?? 0;
        $withdraw->save();

        return redirect()->back()->with('success', 'Transaction updated successfully!');
    }
}
