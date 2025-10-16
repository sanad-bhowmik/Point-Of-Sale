<?php

namespace App\Http\Controllers;

use App\Imports\OfficeExpenseCategoryImport;
use App\Imports\OfficeExpenseImport;
use Illuminate\Http\Request;
use App\Models\OfficeExpense;
use App\Models\OfficeExpenseCategory;
use Maatwebsite\Excel\Facades\Excel;

class OfficeExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query = OfficeExpense::with('category');

        // Category filter
        if ($request->filled('category_id')) {
            $query->where('expense_category_id', $request->category_id);
        }

        // Date range filter
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('date', [$request->from_date, $request->to_date]);
        }

        $expenses = $query->where('status', 'out')->orderBy('date', 'desc')->get();

        $categories = OfficeExpenseCategory::where('category_name', '!=', 'Funds')->get();

        return view('officeExpnese.viewOfficeExpense', compact('expenses', 'categories'));
    }

    public function history(Request $request)
    {
        $expenses = OfficeExpense::with('category')->where('status', 'in');

        // Date range filter
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $expenses->whereBetween('date', [$request->from_date, $request->to_date]);
        }
        $expenses = $expenses->orderBy('date', 'asc')->get();

        return view('officeExpnese.cashInHistory', compact('expenses'));
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
        if ($request->hasFile('excel_file')) {
            $request->validate([
                'excel_file' => 'required|mimes:xlsx,xls,csv',
            ]);

            try {
                Excel::import(new OfficeExpenseImport, $request->file('excel_file'));

                return redirect()->route('office_expense.view')->with('success', 'Office Expense imported successfully from Excel!');

            } catch (\Throwable $th) {
                return back()->with('error', $th->getMessage());
            }

        }

        $validated = $request->validate([
            'category_id'    => 'required|exists:office_expense_categories,id',
            'employee_name'  => 'nullable|string|max:255',
            'quantity'       => 'nullable|string',
            'status'         => 'required|string',
            'amount'         => 'required|string',
            'date'           => 'required|date',
            'note'           => 'nullable|string',
        ]);

        $totalIn  = OfficeExpense::where('status', 'in')->sum('amount');
        $totalOut = OfficeExpense::where('status', 'out')->sum('amount');
        $currentBalance = $totalIn - $totalOut;

        // If Out → Check balance
        if ($validated['status'] === 'out' && $validated['amount'] > $currentBalance) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Insufficient balance! You only have ' . $currentBalance . ' available.');
        }

        // Insert into DB
        $expense = OfficeExpense::create([
            'expense_category_id'       => $validated['category_id'],
            'employee_name'              => $validated['employee_name'] ?? null,
            'quantity'                   => $validated['quantity'] ?? 0,
            'status'                     => $validated['status'],
            'amount'                     => $validated['amount'],
            'date'                       => $validated['date'],
            'note'                       => $validated['note'] ?? null,
        ]);

        if ($validated['status'] === 'in') {
            return redirect()->route('office_expense.history')
                ->with('success', 'Cash In added successfully!');
        }

        return redirect()->route('office_expense.view')
            ->with('success', 'Office Expense added successfully!');
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
            'category_id'    => 'required|exists:office_expense_categories,id',
            'employee_name'  => 'nullable|string|max:255',
            'quantity'       => 'nullable|string',
            'status'         => 'required|string',
            'amount'         => 'required|string',
            'date'           => 'required|date',
            'note'           => 'nullable|string',
        ]);

        try {
            $expense = OfficeExpense::findOrFail($id);

            // Calculate balance excluding current record
            $totalIn  = OfficeExpense::where('status', 'in')->where('id', '!=', $expense->id)->sum('amount');
            $totalOut = OfficeExpense::where('status', 'out')->where('id', '!=', $expense->id)->sum('amount');
            $currentBalance = $totalIn - $totalOut;

            if ($validated['status'] === 'out') {
                if ($validated['amount'] > $currentBalance) {
                    return redirect()->back()->with('error', 'Insufficient balance! You only have ' . $currentBalance . ' available.');
                }
            }

            $expense->update($validated);

            if ($validated['status'] === 'in') {
                return redirect()->route('office_expense.history')
                    ->with('success', 'Cash In updated successfully!');
            }

            return redirect()->route('office_expense.view')
                ->with('success', 'Office Expense updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update Office Expense: ' . $e->getMessage());
        }
    }

    public function ledger(Request $request)
    {
        $categories = OfficeExpenseCategory::where('category_name', '!=', 'Funds')->get();
        $query = OfficeExpense::with('category')->orderBy('date', 'asc');
        $inQuery = OfficeExpense::with('category')->where('status', 'in');

        // Category filter
        if ($request->filled('category_id')) {
            $query->where('expense_category_id', $request->category_id);
        }

        // Date range filter
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('date', [$request->from_date, $request->to_date]);
        }

        $expenses = $inQuery->get()->merge($query->get())->sortBy('date')->values();

        // Running balance calculation
        $balance = 0;
        foreach ($expenses as $expense) {
            if ($expense->status === 'in') {
                $balance += $expense->amount;
                $expense->cash_in_hand_line = $balance;
            } else {
                $balance -= $expense->amount;
                $expense->cash_in_hand_line = $balance;
            }
        }

        $totalIn  = OfficeExpense::where('status', 'in')->sum('amount');
        $totalOut = OfficeExpense::where('status', 'out')->sum('amount');
        $cashInHand = $totalIn - $totalOut;

        return view('officeExpnese.officeExpenseLedger', compact('expenses', 'cashInHand', 'totalIn', 'totalOut', 'categories'));
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

    public function officeExpenseName()
    {
        return view('officeExpnese.officeExpenseName');
    }
    public function storeOfficeExpenseCategory(Request $request)
    {
        if ($request->hasFile('excel_file')) {
            $request->validate([
                'excel_file' => 'required|mimes:xlsx,xls,csv',
            ]);

            try {
                Excel::import(new OfficeExpenseCategoryImport, $request->file('excel_file'));

                return redirect()->route('office_expense.view_names')->with('success', 'Expense Category imported successfully from Excel!');

            } catch (\Throwable $th) {
                return back()->with('error', $th->getMessage());
            }

        }

        $request->validate([
            'category_name' => 'required|string|max:255',
            'category_description' => 'nullable|string|max:255',
        ]);

        OfficeExpenseCategory::create([
            'category_name' => $request->category_name,
            'category_description' => $request->category_description,
        ]);

        return redirect()->route('office_expense.view_names')
            ->with('success', 'Office Expense Category created successfully.');
    }
    public function viewOfficeExpenseName()
    {
        $categories = OfficeExpenseCategory::all(); // Fetch all categories
        return view('officeExpnese.viewOfficeExpenseName', compact('categories'));
    }
}
