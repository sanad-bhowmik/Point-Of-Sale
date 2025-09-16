<?php

namespace Modules\Reports\Http\Controllers;

use App\Models\Container;
use App\Models\Costing;
use App\Models\Lc;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;
use Modules\Expense\Entities\Expense;

class ReportsController extends Controller
{

    public function buyingSelling()
    {
        abort_if(Gate::denies('access_reports'), 403);

        $containerList = Container::whereIn('status', [1, 2])->get();
        $lcList = Lc::all();

        return view('reports::buyingSelling.index', [
            'containerList' => $containerList,
            'lcList' => $lcList,
        ]);
    }

    public function buyingSellingFilter(Request $request)
    {
        $containerList = Container::whereIn('status', [1, 2])->get();
        $lcList = Lc::all();
        $query = Costing::with(['lc', 'supplier', 'product']);
        $container = Container::where('lc_id', $request->lc_id)->first();

        if ($request->lc_id == $container->lc_id) {
            $query->where('lc_id', $request->lc_id);
            $buyingSelling = $query->paginate(10);

             $totalAmount = Expense::where('lc_id', $request->lc_id)
                                    ->where('container_id', $request->container_id)
                                    ->whereHas('category', function ($q) {
                                        $q->where('category_name', '!=', 'Dhaka Expense');
                                    })
                                    ->sum('amount');

             $totalCostAmount = Expense::where('lc_id', $request->lc_id)
                                    ->where('container_id', $request->container_id)
                                    ->sum('amount');
                                    
            return view('reports::buyingSelling.index', [
                            'containerList' => $containerList,
                            'lcList' => $lcList,
                            'totalAmount' => $totalAmount,
                            'totalCostAmoun' => $totalCostAmount,
                            'buyingSelling' => $buyingSelling,
                        ]);
        }
        else{
            return view('reports::buyingSelling.index', [
                'containerList' => $containerList,
                'lcList' => $lcList,
            ]);
        }
    }

    public function profitLossReport()
    {
        abort_if(Gate::denies('access_reports'), 403);

        return view('reports::profit-loss.index');
    }

    public function paymentsReport()
    {
        abort_if(Gate::denies('access_reports'), 403);

        return view('reports::payments.index');
    }

    public function salesReport()
    {
        abort_if(Gate::denies('access_reports'), 403);

        return view('reports::sales.index');
    }

    public function purchasesReport()
    {
        abort_if(Gate::denies('access_reports'), 403);

        return view('reports::purchases.index');
    }

    public function salesReturnReport()
    {
        abort_if(Gate::denies('access_reports'), 403);

        return view('reports::sales-return.index');
    }

    public function purchasesReturnReport()
    {
        abort_if(Gate::denies('access_reports'), 403);

        return view('reports::purchases-return.index');
    }
}
