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
        $containers = Container::with(['lc.costing.product.sizes', 'lc.costing.supplier', 'saleDetails'])
            ->where('status', 2)->get();

        if (isset($request->container_id)) {
            $containerList = $query->where('id', $request->container_id)->get();

            return view('reports::stock-report.index', [
                'containerList' => $containerList,
                'containers' => $containers,
            ]);
        }

        $containerList = $query->get();

        return view('reports::stock-report.index', [
            'containerList' => $containerList,
            'containers' => $containers,
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
        $containers = Container::whereNotIn('status', [3, 4])->where(function ($q) {
            $q->whereColumn('current_qty', '<', 'qty');
        })->with(['lc.costing.supplier', 'lc.costing.product.sizes'])->get();

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

    public function investmentReport()
    {
        abort_if(Gate::denies('access_reports'), 403);

        // ✅ Call all calculation methods
        $totalStorager = $this->calculateStorageCosts();
        $totalLose = $this->totolLose();
        $totalProfit = $this->totalProfit();
        $totalDueAmount = $this->totalDueAmount();
        $calculateUpcoming = $this->calculateUpcoming();
        $totalOpeningBalance = $this->totalOpeningBalance();
        $totalInvestment = $this->totalInvestment();
        $totalInvestmentAmount = $this->totalInvestmentAmount();
        $totalDamagerInvestmentAmount = $this->totalDamagerInvestmentAmount();
        $officeExpense = $this->officeExpense();

        // ✅ Total Get = sum(amount + damarage_amount) - totalInvestmentAmount
        $totalGetValue = \DB::table('parties_payment')
            ->selectRaw('SUM(amount + damarage_amount) - ? as total', [$totalInvestmentAmount])
            ->value('total');

        return view('reports::investment.index', [
            'totalStorager' => $totalStorager,
            'totalLose' => $totalLose,
            'totalProfit' => $totalProfit,
            'totalDueAmount' => $totalDueAmount,
            'calculateUpcoming' => $calculateUpcoming,
            'totalOpeningBalance' => $totalOpeningBalance,
            'totalInvestment' => $totalInvestment,
            'totalInvestmentAmount' => $totalInvestmentAmount,
            'totalDamagerInvestmentAmount' => $totalDamagerInvestmentAmount,
            'officeExpense' => $officeExpense,
            'totalGetValue' => $totalGetValue, // pass to view
        ]);
    }


    public function officeExpense()
    {
        // Sum the 'amount' column from the investments table
        $totalAmount = \App\Models\OfficeExpense::sum('amount');
        return $totalAmount;
    }
    public function totalDamagerInvestmentAmount()
    {
        $totalAmount = \App\Models\PartiesPayment::sum('amount');
        $totalDamarage = \App\Models\PartiesPayment::sum('damarage_amount');

        return $totalAmount + $totalDamarage;
    }

    public function totalInvestmentAmount()
    {
        // Sum the 'amount' column from the investments table
        $totalAmount = \App\Models\PartiesPayment::sum('amount');
        return $totalAmount;
    }
    private function totalInvestment()
    {
        // Using DB Facade to execute the window function query
        $results = \DB::select("
        SELECT
            id,
            investment,
            amount,
            SUM(
                CASE
                    WHEN investment = 'Invest' THEN amount
                    WHEN investment = 'Profit' THEN amount
                    WHEN investment = 'Cash Invest' THEN amount
                    WHEN investment = 'Expense' THEN -amount
                    ELSE 0
                END
            ) OVER (ORDER BY id ROWS BETWEEN UNBOUNDED PRECEDING AND CURRENT ROW) AS cumulative_balance
        FROM investment
        ORDER BY id ASC
    ");

        // If there are results, get the last cumulative_balance
        if (!empty($results)) {
            $lastRow = end($results);
            return $lastRow->cumulative_balance ?? 0;
        }

        return 0;
    }

    private function totalOpeningBalance()
    {
        return \App\Models\Bank::sum('opening_balance');
    }
    private function totalProfit()
    {
        $containers = Container::whereNotIn('status', [3, 4])->where("current_qty", "=", 0)->get();
        $totalLose = 0;

        foreach ($containers as $key => $container) {
            # code...

            $totalCostAmount = \Modules\Expense\Entities\Expense::where('lc_id', $container->lc_id,)->where('container_id', $container->id)->sum('amount');

            $lcCost = $container?->lc_value * $container?->lc_exchange_rate * $container->qty;

            $ttCost = $container?->tt_value * $container?->tt_exchange_rate * $container->qty;

            $totalSale = \Modules\Sale\Entities\SaleDetails::where('lc_id', $container->lc_id)
                ->where('container_id', $container->id)
                ->sum('sub_total');

            $totalCost = $lcCost + $ttCost + $totalCostAmount;

            $profit_loss = $totalSale - $totalCost;

            if ($profit_loss > 0) {
                $totalLose += $profit_loss;
            } elseif ($profit_loss < 0) {
                $totalLose += 0;
            }
        }
        return $totalLose;
    }
    private function totolLose()
    {
        $containers = Container::where('status', '!=', 3)->where("current_qty", "=", 0)->get();
        $totalLose = 0;

        foreach ($containers as $key => $container) {
            # code...

            $totalCostAmount = \Modules\Expense\Entities\Expense::where('lc_id', $container->lc_id,)->where('container_id', $container->id)->sum('amount');

            $lcCost = $container?->lc_value * $container?->lc_exchange_rate * $container->qty;

            $ttCost = $container?->tt_value * $container?->tt_exchange_rate * $container->qty;

            $totalSale = \Modules\Sale\Entities\SaleDetails::where('lc_id', $container->lc_id)
                ->where('container_id', $container->id)
                ->sum('sub_total');

            $totalCost = $lcCost + $ttCost + $totalCostAmount;

            $profit_loss = $totalSale - $totalCost;

            if ($profit_loss > 0) {
                $totalLose += 0;
            } elseif ($profit_loss < 0) {
                $totalLose += abs($profit_loss);
            }
        }
        return $totalLose;
    }

    // ✅ Define the function below
    private function calculateUpcoming()
    {
        $containers = Container::where('status', 3)->get();
        $result = 0;

        foreach ($containers as $container) {
            // Step 2: Calculate total LC amount, total TT amount, and total expense cost
            $totalLcAmount = $container->lc_value *  $container->lc_exchange_rate * $container->qty;
            $totalTtAmount = $container->tt_value * $container->tt_exchange_rate * $container->qty;

            $totalExpenseCost = $container->load("expenses")->expenses->sum('amount');  // Sum of all related expenses

            // Step 3: Calculate total sales for the container
            $totalSales = $container->load("saleDetails")->saleDetails->sum('sub_total'); // Sum of all related sale details

            // Step 4: Calculate total cost
            $totalCost = $totalLcAmount + $totalTtAmount + $totalExpenseCost;
            $storageCost = 0;

            // Step 5: If total cost is greater than total sales, calculate storage cost
            if ($totalCost > $totalSales) {
                $storageCost = $totalCost - $totalSales;
            }

            // Add to result array
            $result += $storageCost;
        }

        return $result;
    }
    private function calculateStorageCosts()
    {
        $containers = Container::whereNotIn('status', [3, 4])->where("current_qty", ">", 0)->get();

        $result = 0;

        foreach ($containers as $container) {
            // Step 2: Calculate total LC amount, total TT amount, and total expense cost
            $totalLcAmount = $container->lc_value *  $container->lc_exchange_rate * $container->qty;
            $totalTtAmount = $container->tt_value * $container->tt_exchange_rate * $container->qty;

            $totalExpenseCost = $container->load("expenses")->expenses->sum('amount');  // Sum of all related expenses

            // Step 3: Calculate total sales for the container
            $totalSales = $container->load("saleDetails")->saleDetails->sum('sub_total'); // Sum of all related sale details

            // Step 4: Calculate total cost
            $totalCost = $totalLcAmount + $totalTtAmount + $totalExpenseCost;
            $storageCost = 0;

            // Step 5: If total cost is greater than total sales, calculate storage cost
            if ($totalCost > $totalSales) {
                $storageCost = $totalCost - $totalSales;
            }
            logger($storageCost);

            // Add to result array
            $result += $storageCost;
        }

        return $result;
    }
    private function totalDueAmount()
    {
        // Sum the due_amount column from the sales table
        $totalDue = \Modules\Sale\Entities\Sale::sum('due_amount');

        return $totalDue;
    }
}
