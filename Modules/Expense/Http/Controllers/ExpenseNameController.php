<?php

namespace Modules\Expense\Http\Controllers;

use App\Models\ExpenseName;
use Modules\Expense\DataTables\ExpenseCategoriesDataTable;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;
use Modules\Expense\DataTables\ExpenseNameDataTable;
use Modules\Expense\Entities\ExpenseCategory;

class ExpenseNameController extends Controller
{

    public function index(ExpenseNameDataTable $dataTable)
    {
        abort_if(Gate::denies('access_expense_categories'), 403);

        return $dataTable->render('expense::expense_name.index');
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('access_expense_categories'), 403);

        $request->validate([
            'expense_name' => 'required|string|max:255|unique:expense_names,expense_name',
            'expense_category_id' => 'required|exists:expense_categories,id',
        ]);

        ExpenseName::create([
            'expense_name' => $request->expense_name,
            'expense_category_id' => $request->expense_category_id,
        ]);

        toast('Expense Name Created!', 'success');

        return redirect()->route('expense-names.index');
    }

    public function edit(ExpenseName $expenseName)
    {
        abort_if(Gate::denies('access_expense_categories'), 403);
        $categories = ExpenseCategory::all();

        return view('expense::expense_name.edit', compact('expenseName', 'categories'));
    }

    public function update(Request $request, ExpenseName $expenseName)
    {
        // Permission check
        abort_if(Gate::denies('access_expense_categories'), 403);

        // Validation
        $request->validate([
            'expense_name' => 'required|string|max:255|unique:expense_names,expense_name,' . $expenseName->id,
            'expense_category_id' => 'required|exists:expense_categories,id',
        ]);

        // Update record
        $expenseName->update([
            'expense_name' => $request->expense_name,
            'expense_category_id' => $request->expense_category_id,
        ]);

        // Success toast
        toast('Expense Name Updated!', 'success');

        // Redirect to Expense Names listing
        return redirect()->route('expense-names.index');
    }

    public function destroy(ExpenseName $expenseName)
    {
        // Permission check
        abort_if(Gate::denies('access_expense_categories'), 403);

        // Check if any expenses are associated with this Expense Name
        if ($expenseName->expenses()->exists()) {
            return back()->withErrors('Cannot delete because there are expenses associated with this name.');
        }

        // Delete the Expense Name
        $expenseName->delete();

        // Success toast
        toast('Expense Name Deleted!', 'success');

        // Redirect to Expense Name listing
        return redirect()->route('expense-names.index');
    }
}
