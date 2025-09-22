<?php

namespace Modules\Reports\Http\Controllers;

use App\Models\Container;
use App\Models\Costing;
use App\Models\Lc;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;
use Modules\Expense\Entities\Expense;
use Modules\Sale\Entities\SaleDetails;

class ReportsController extends Controller
{

    public function buyingSelling(Request $request)
    {
        abort_if(Gate::denies('access_reports'), 403);

        $containerList = Container::whereIn('status', [1, 2])->get();
        $lcList = Lc::all();
        $container = Container::with(['lc.costing.product.sizes', 'lc.costing.supplier'])->where('id', $request->container_id)->first();

        if ($request->lc_id == $container?->lc_id) {
            $totalAmount = Expense::where('lc_id', $request->lc_id)
                ->where('container_id', $request->container_id)
                ->whereHas('category', function ($q) {
                    $q->where('category_name', '!=', 'Dhaka Expense');
                })
                ->sum('amount');

            $totalCostAmount = Expense::where('lc_id', $request->lc_id)
                ->where('container_id', $request->container_id)
                ->sum('amount');

            $sales = SaleDetails::where('lc_id', $request->lc_id)
                ->where('container_id', $request->container_id)
                ->with('sale')
                ->get();

            $totalSale = SaleDetails::where('lc_id', $request->lc_id)
                ->where('container_id', $request->container_id)
                ->sum('sub_total');

            return view('reports::buyingSelling.index', [
                'containerList' => $containerList,
                'lcList' => $lcList,
                'totalAmount' => $totalAmount,
                'totalCostAmount' => $totalCostAmount,
                'totalSale' => $totalSale,
                'sales' => $sales,
                'container' => $container ?? null,
            ]);
        }

        return view('reports::buyingSelling.index', [
            'containerList' => $containerList,
            'lcList' => $lcList,
            'container' => $container,
        ]);
    }

    public function getContainer(Request $request)
    {
        $containers = Container::where('lc_id', $request->lc_id)->where('status', 2)->get();
        return response()->json($containers);
    }

    public function stockReport(Request $request)
    {
        $query = Container::with(['lc.costing.product.sizes', 'lc.costing.supplier', 'saleDetails'])
            ->where('status', 2);

        if (isset($request->container_id)) {
            $containerList = $query->where('id', $request->container_id)->get();

            return view('reports::stock-report.index', [
                'containerList' => $containerList,
            ]);
        }

        $containerList = $query->get();

        return view('reports::stock-report.index', [
            'containerList' => $containerList,
        ]);
    }

    public function shipmentStatus(Request $request)
    {
        abort_if(Gate::denies('access_reports'), 403);

        $containerList = Container::whereIn('status', [1, 2])->get();
        $lcList = Lc::all();
        $container = Container::with(['lc.costing.product.sizes', 'lc.costing.supplier'])->where('id', $request->container_id)->first();

        if ($request->lc_id == $container?->lc_id) {
            $totalAmount = Expense::where('lc_id', $request->lc_id)
                ->where('container_id', $request->container_id)
                ->whereHas('category', function ($q) {
                    $q->where('category_name', '!=', 'Dhaka Expense');
                })
                ->sum('amount');

            $totalCostAmount = Expense::where('lc_id', $request->lc_id)
                ->where('container_id', $request->container_id)
                ->sum('amount');

            $sales = SaleDetails::where('lc_id', $request->lc_id)
                ->where('container_id', $request->container_id)
                ->with('sale')
                ->get();

            $totalSale = SaleDetails::where('lc_id', $request->lc_id)
                ->where('container_id', $request->container_id)
                ->sum('sub_total');

            return view('reports::shipmentStatus.index', [
                'containerList' => $containerList,
                'lcList' => $lcList,
                'totalAmount' => $totalAmount,
                'totalCostAmount' => $totalCostAmount,
                'totalSale' => $totalSale,
                'sales' => $sales,
                'container' => $container ?? null,
            ]);
        }

        return view('reports::shipmentStatus.index', [
            'containerList' => $containerList,
            'lcList' => $lcList,
            'container' => $container,
        ]);
    }

    public function cashFlow()
    {
        abort_if(Gate::denies('access_reports'), 403);
        $containers = Container::with(['lc.costing.supplier'])->get();

        return view('reports::cash-flow.index', [
            'containers'  => $containers
        ]);
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
