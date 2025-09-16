<?php

namespace Modules\Expense\Http\Controllers;

use App\Models\Container;
use App\Models\Costing;
use App\Models\ExpenseName;
use App\Models\Lc;
use Modules\Expense\DataTables\ExpensesDataTable;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;
use Modules\Expense\Entities\Expense;
use Modules\Expense\Entities\ExpenseCategory;
use Modules\Sale\Entities\SaleDetails;
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
        $lcs = Lc::all();
        $containers = Container::whereIn('status', [1, 2])->get();

        return view('expense::expenses.create', compact('lcs', 'containers'));
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('create_expenses'), 403);

        // Validate the request
        $request->validate([
            'category_id' => 'required|exists:expense_categories,id',
            'expense_name_id' => 'required|exists:expense_names,id',
            'lc_id' => 'required',
            'container_id' => 'required',
            'amount' => 'required|numeric',
            'date' => 'required|date',
            'note' => 'nullable|string',
        ]);

        // Create expense
        Expense::create([
            'category_id' => $request->category_id,
            'expense_name_id' => $request->expense_name_id,
            'lc_id' => $request->lc_id,
            'container_id' => $request->container_id,
            'amount' => $request->amount,
            'date' => $request->date,
            'note' => $request->note,
        ]);

        toast('Expense Created!', 'success');

        return redirect()->route('expenses.index');
    }

    public function edit(Expense $expense)
    {
        abort_if(Gate::denies('edit_expenses'), 403);

        $categories = ExpenseCategory::all();
        $expenseNames = ExpenseName::where('expense_category_id', $expense->category_id)->get();
        $lcs = Lc::all();
        $containers = Container::whereIn('status', [1, 2])->get();

        return view('expense::expenses.edit', compact('expense', 'categories', 'expenseNames', 'lcs', 'containers'));
    }

    public function update(Request $request, Expense $expense)
    {
        abort_if(Gate::denies('edit_expenses'), 403);

        // Validate the request
        $request->validate([
            'category_id'      => 'required|exists:expense_categories,id',
            'expense_name_id'  => 'required|exists:expense_names,id',
            'lc_id'            => 'required',
            'container_id'     => 'required',
            'amount'           => 'required|numeric',
            'date'             => 'required|date',
            'note'             => 'nullable|string',
        ]);

        // Update expense
        $expense->update([
            'category_id'      => $request->category_id,
            'expense_name_id'  => $request->expense_name_id,
            'lc_id'            => $request->lc_id,
            'container_id'     => $request->container_id,
            'amount'           => $request->amount,
            'date'             => $request->date,
            'note'             => $request->note,
        ]);

        toast('Expense Updated!', 'success');

        return redirect()->route('expenses.index');
    }

    public function destroy(Expense $expense)
    {
        abort_if(Gate::denies('delete_expenses'), 403);

        $expense->delete();

        toast('Expense Deleted!', 'success');

        return redirect()->route('expenses.index');
    }

    // ExpenseController.php
    public function getExpenseNames($categoryId)
    {
        $expenseNames = ExpenseName::where('expense_category_id', $categoryId)->get();

        return response()->json($expenseNames);
    }

    public function finalReport()
    {
        $lcs = Lc::get();
        $containers = Container::whereIn('status', [1, 2])->get();

        return view('expense::expenses.finalReport', [
            'lcs' => $lcs,
            'containers' => $containers,
            'find_lc' => null,
            'find_container' => null,
        ]);
    }

    public function finalReportFilter(Request $request)
    {
        $request->validate([
            'lc_id' => 'required',
            'container_id' => 'required',
        ]);

        $lc = Lc::find($request->lc_id);
        $container = Container::find($request->container_id);

        if (isset($request->lc_id) && isset($container->lc_id)) {
            $costing = Costing::where('lc_id', $request->lc_id)->first();
            $expenseGroup = Expense::with('category', 'expenseName', 'lc', 'container')
                ->where('lc_id', $request->lc_id)
                ->where('container_id', $request->container_id)
                ->get()
                ->groupBy(function ($item) {
                    return $item->category->category_name ?? 'Unknown';
                });

            $totalSale = SaleDetails::whereHas('sale', function($q) use ($request) {
                                        $q->where('lc_id', $request->lc_id)
                                        ->where('container_id', $request->container_id);
                                    })
                                    ->sum('sub_total');

            return view('expense::expenses.finalReport', [
                'find_lc' => $lc,
                'find_container' => $container->load('lc.costing.product'),
                'lcs' => Lc::get(),
                'containers' => Container::whereIn('status', [1, 2])->get(),
                'costing' => $costing,
                'expenseGroup' => $expenseGroup,
                'totalSale' => $totalSale,
            ]);
        }

        return view('expense::expenses.finalReport', [
            'lcs' => Lc::get(),
            'containers' => Container::whereIn('status', [1, 2])->get(),
            'find_lc' => null,
            'find_container' => null,
        ]);
    }
}
