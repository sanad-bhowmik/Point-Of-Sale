<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Transaction::with('bank');

        if ($request->filled('date_range')) {
            $dates = explode(' to ', $request->date_range);
            if (count($dates) == 2) {
                $query->whereBetween('date', [trim($dates[0]), trim($dates[1])]);
            }
        }

        // Transaction Type Filter
        if ($request->filled('transaction_type')) {
            if ($request->transaction_type == 'in') {
                $query->where('in_amount', '>', 0);
            } elseif ($request->transaction_type == 'out') {
                $query->where('out_amount', '>', 0);
            }
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $transactions = $query->orderBy('id', 'desc')->get();

        return view('transaction.transaction_list', compact('transactions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $banks = Bank::all();
        return view('transaction.transaction_create', compact('banks'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate request
        $request->validate([
            'bank_id'    => 'required|exists:banks,id',
            'in_amount'  => 'nullable|numeric|min:0',
            'out_amount' => 'nullable|numeric|min:0',
            'purpose'    => 'required|string',
            'date'       => 'required|date',
        ]);

        // Default amounts if null
        $inAmount = $request->in_amount ?? 0;
        $outAmount = $request->out_amount ?? 0;

        $transaction = Transaction::create([
            'bank_id'    => $request->bank_id,
            'in_amount'  => $inAmount,
            'out_amount' => $outAmount,
            'purpose'    => $request->purpose,
            'status'     => $request->status ?? 'pending',
            'date'       => Carbon::parse($request->date)->format('Y-m-d'),
        ]);

        return redirect()->back()->with('success', 'Transaction saved and bank balance updated successfully.');
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
    public function edit(Transaction $transaction)
    {
        $banks = Bank::all();
        return view('transaction.transaction_edit', compact('transaction', 'banks'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        $request->validate([
            'bank_id'    => 'required|exists:banks,id',
            'in_amount'  => 'nullable|numeric|min:0',
            'out_amount' => 'nullable|numeric|min:0',
            'purpose'    => 'required|string',
            'date'       => 'required|date',
        ]);

        $newInAmount = $request->in_amount ?? 0;
        $newOutAmount = $request->out_amount ?? 0;

        $transaction->update([
            'bank_id'    => $request->bank_id,
            'in_amount'  => $newInAmount,
            'out_amount' => $newOutAmount,
            'purpose'    => $request->purpose,
            'date'       => Carbon::parse($request->date)->format('Y-m-d'),
        ]);

        return redirect()->route('transaction.index')->with('success', 'Transaction updated and bank balance adjusted successfully.');
    }

    public function updateStatus(Request $request, Transaction $transaction)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $transaction->update([
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Transaction status updated successfully.');
    }

    public function ledger(Request $request)
    {
        $banks = Bank::all();
        $query = Transaction::with('bank')->where('status', 'approved');

        if ($request->bank_id) {
            $query->where('bank_id', $request->bank_id);
            $selectedBank = Bank::find($request->bank_id);
        } else {
            $selectedBank = null;
            return view('transaction.bank_ledger', [
                'transactions' => [],
                'banks' => $banks,
                'selectedBank' => $selectedBank,
                'openingBalance' => 0,
                'request' => $request
            ]);
        }

        $openingBalance = $selectedBank->opening_balance ?? 0;

        // Filter by date range
        if ($request->date_range) {
            $dates = explode(' to ', $request->date_range);
            if (count($dates) === 2) {
                $fromDate = $dates[0];
                $toDate   = $dates[1];

                $previousTransactions = Transaction::where('bank_id', $selectedBank->id)
                    ->where('status', 'approved')
                    ->where('date', '<', $fromDate)
                    ->get();
                    foreach ($previousTransactions as $pt) {
                        $openingBalance += $pt->in_amount - $pt->out_amount;
                    }
                    
                    $query->whereBetween('date', [$fromDate, $toDate]);
                }
        }
            
        $transactions = $query->orderBy('date')->get();

        // Running ledger balance per bank
        $ledger = [];
        foreach ($transactions as $transaction) {
            if (!isset($ledger[$transaction->bank_id])) {
                $ledger[$transaction->bank_id] = $openingBalance;
            }
        }

        return view('transaction.bank_ledger', [
            'transactions' => $transactions,
            'banks' => $banks,
            'selectedBank' => $selectedBank,
            'openingBalance' => $openingBalance,
            'request' => $request,
            'ledger' => $ledger,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        $transaction->delete();

        return redirect()->back()->with('success', 'Transaction deleted successfully.');
    }
}
