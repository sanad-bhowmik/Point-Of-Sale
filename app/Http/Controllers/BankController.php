<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use Illuminate\Http\Request;

class BankController extends Controller
{

    public function index()
    {
        $banks = Bank::with('transactions')->orderBy('id', 'desc')->get();

        foreach ($banks as $bank) {
            $in  = $bank->transactions->sum('in_amount');
            $out = $bank->transactions->sum('out_amount');

            $bank->current_balance = ($bank->opening_balance ?? 0) + $in - $out;
        }

        return view('bank.bank_list', compact('banks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('bank.bank_create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'institution'   => 'required|string|max:255',
            'account_no'    => 'required|string|max:255',
            'bank_name'     => 'required|string|max:255',
            'branch_name'   => 'required|string|max:255',
            'owner'         => 'required|string|max:255',
            'date'          => 'required|date',
            'opening_balance'  => 'required|numeric',
            'disclaimer'    => 'nullable|string|max:255',
        ]);

        // create new bank record
        Bank::create([
            'institution'  => $request->institution,
            'account_no'   => $request->account_no,
            'bank_name'    => $request->bank_name,
            'branch_name'  => $request->branch_name,
            'owner'        => $request->owner,
            'date'         => $request->date,
            'opening_balance' => $request->opening_balance,
            'disclaimer'   => $request->disclaimer,
        ]);

        // redirect back with success message
        return redirect()->back()->with('success', 'Bank information saved successfully!');
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
    public function edit(Bank $bank)
    {
        return view('bank.bank_edit', compact('bank'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Bank $bank)
    {
        $request->validate([
            'institution'   => 'required|string|max:255',
            'account_no'    => 'required|string|max:255',
            'bank_name'     => 'required|string|max:255',
            'branch_name'   => 'required|string|max:255',
            'owner'         => 'required|string|max:255',
            'date'          => 'required|date',
            'last_balance'  => 'required|numeric',
            'disclaimer'    => 'nullable|string|max:255',
        ]);

        $bank->update([
            'institution'  => $request->institution,
            'account_no'   => $request->account_no,
            'bank_name'    => $request->bank_name,
            'branch_name'  => $request->branch_name,
            'owner'        => $request->owner,
            'date'         => $request->date,
            'disclaimer'   => $request->disclaimer,
        ]);

        return redirect()->route('bank.index')->with('success', 'Bank information updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bank $bank)
    {
        $bank->delete();

        return redirect()->back()->with('success', 'Bank record deleted successfully!');
    }
}
