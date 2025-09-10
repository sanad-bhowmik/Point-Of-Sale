<?php

namespace Modules\Expense\Http\Controllers;

use Modules\Expense\DataTables\ExpensesDataTable;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;
use Modules\Expense\Entities\Expense;
use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Exp;

class ExpenseController extends Controller
{

    public function index(ExpensesDataTable $dataTable)
    {
        abort_if(Gate::denies('access_expenses'), 403);

        return $dataTable->render('expense::expenses.index');
    }


    public function create()
    {
        abort_if(Gate::denies('create_expenses'), 403);

        return view('expense::expenses.create');
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('create_expenses'), 403);

        $request->validate([
            'date' => 'required|date',
            'reference' => 'required|string|max:255',
            'lc_id' => 'required|exists:lc,id',
            'category_id' => 'required',
            'amount' => 'required|numeric|max:2147483647',
            'cf_agent_fee' => 'nullable|string|max:255',
            'bl_verify' => 'nullable|string|max:255',
            'shipping_charge' => 'nullable|string|max:255',
            'port_bill' => 'nullable|string|max:255',
            'labor_bill' => 'nullable|string|max:255',
            'transport_bill' => 'nullable|string|max:255',
            'other_receipt' => 'nullable|string|max:255',
            'formalin_test' => 'nullable|string|max:255',
            'radiation_cert' => 'nullable|string|max:255',
            'labor_tips' => 'nullable|string|max:255',
            'cf_commission' => 'nullable|string|max:255',
            'ip_absence' => 'nullable|string|max:255',
            'special_delivery' => 'nullable|string|max:255',
            'details' => 'nullable|string|max:1000',
        ]);

        Expense::create([
            'date' => $request->date,
            'reference' => $request->reference,
            'lc_id' => $request->lc_id,
            'category_id' => $request->category_id,
            'amount' => $request->amount,
            'cf_agent_fee' => $request->cf_agent_fee,
            'bl_verify' => $request->bl_verify,
            'shipping_charge' => $request->shipping_charge,
            'port_bill' => $request->port_bill,
            'labor_bill' => $request->labor_bill,
            'transport_bill' => $request->transport_bill,
            'other_receipt' => $request->other_receipt,
            'formalin_test' => $request->formalin_test,
            'radiation_cert' => $request->radiation_cert,
            'labor_tips' => $request->labor_tips,
            'cf_commission' => $request->cf_commission,
            'ip_absence' => $request->ip_absence,
            'special_delivery' => $request->special_delivery,
            'details' => $request->details,
        ]);

        toast('Expense Created!', 'success');

        return redirect()->route('expenses.index');
    }


    public function edit(Expense $expense)
    {
        abort_if(Gate::denies('edit_expenses'), 403);

        return view('expense::expenses.edit', compact('expense'));
    }


    public function update(Request $request, Expense $expense)
    {
        abort_if(Gate::denies('edit_expenses'), 403);

        $request->validate([
            'date' => 'required|date',
            'reference' => 'required|string|max:255',
            'category_id' => 'required',
            'amount' => 'required|numeric|max:2147483647',
            'details' => 'nullable|string|max:1000'
        ]);

        $expense->update([
            'date' => $request->date,
            'reference' => $request->reference,
            'category_id' => $request->category_id,
            'amount' => $request->amount,
            'details' => $request->details
        ]);

        toast('Expense Updated!', 'info');

        return redirect()->route('expenses.index');
    }


    public function destroy(Expense $expense)
    {
        abort_if(Gate::denies('delete_expenses'), 403);

        $expense->delete();

        toast('Expense Deleted!', 'warning');

        return redirect()->route('expenses.index');
    }
}
