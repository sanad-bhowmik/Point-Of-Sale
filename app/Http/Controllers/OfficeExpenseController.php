<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OfficeExpense;

class OfficeExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query = OfficeExpense::query();

        // ✅ Employee filter
        if ($request->filled('employee_name')) {
            $query->where('employee_name', 'like', '%' . $request->employee_name . '%');
        }

        // ✅ Date range filter
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('date', [$request->from_date, $request->to_date]);
        } elseif ($request->filled('from_date')) {
            $query->whereDate('date', '>=', $request->from_date);
        } elseif ($request->filled('to_date')) {
            $query->whereDate('date', '<=', $request->to_date);
        }

        $expenses = $query->orderBy('date', 'desc')->paginate(10);

        return view('officeExpnese.viewOfficeExpense', compact('expenses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('officeExpnese.createOfficeExpense');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->merge(['expense_category' => 'Office']); // fixed category

        $validated = $request->validate([
            'expense_category' => 'required|string|max:255',
            'employee_name'    => 'required|string|max:255',
            'amount'           => 'required|numeric',
            'date'             => 'required|date',
            'note'             => 'nullable|string',
        ]);

        try {
            OfficeExpense::create($validated);

            return redirect()->route('office_expense.view')
                ->with('success', 'Office Expense added successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to add Office Expense: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $expense = OfficeExpense::findOrFail($id);
        return view('officeExpnese.editOfficeExpense', compact('expense'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'employee_name'    => 'required|string|max:255',
            'amount'           => 'required|numeric',
            'date'             => 'required|date',
            'note'             => 'nullable|string',
        ]);

        try {
            $expense = OfficeExpense::findOrFail($id);
            $expense->update($validated);

            return redirect()->route('office_expense.view')
                ->with('success', 'Office Expense updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update Office Expense: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $expense = OfficeExpense::findOrFail($id);
            $expense->delete();

            return redirect()->route('office_expense.view')
                ->with('success', 'Office Expense deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete Office Expense: ' . $e->getMessage());
        }
    }
}
