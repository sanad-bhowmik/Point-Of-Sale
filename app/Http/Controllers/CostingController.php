<?php

namespace App\Http\Controllers;

use App\Models\Costing;
use Illuminate\Http\Request;

class CostingController extends Controller
{
    public function addCosting()
    {
        // return the view
        return view('costing.addCosting');
    }
    public function storeCosting(Request $request)
    {
        // Validation
        $request->validate([
            'supplier_id' => 'required',
            'product_id'  => 'required',
            'box_type'    => 'required',
            'size'        => 'required',
            'currency'    => 'required',
        ]);

        // Create costing
        $costing = new Costing();
        $costing->supplier_id        = $request->supplier_id;
        $costing->product_id         = $request->product_id;
        $costing->box_type           = $request->box_type;
        $costing->size               = $request->size;
        $costing->currency           = $request->currency;
        $costing->exchange_rate      = $request->exchange_rate;
        $costing->base_value         = $request->base_value;
        $costing->qty                = $request->qty;
        $costing->total              = $request->total;
        $costing->total_tk           = $request->total_tk;
        $costing->insurance          = $request->insurance;
        $costing->insurance_tk       = $request->insurance_tk;
        $costing->landing_charge     = $request->landing_charge;
        $costing->landing_charge_tk  = $request->landing_charge_tk;
        $costing->cd                 = $request->cd;
        $costing->rd                 = $request->rd;
        $costing->sd                 = $request->sd;
        $costing->vat                = $request->vat;
        $costing->ait                = $request->ait;
        $costing->at                 = $request->at;
        $costing->atv                = $request->atv;
        $costing->total_tax          = $request->total_tax;
        $costing->transport          = $request->transport;
        $costing->arrot              = $request->arrot;
        $costing->cns_charge         = $request->cns_charge;
        $costing->others_total       = $request->others_total;

        // ✅ New fields
        $costing->total_tariff_lc    = $request->total_tariff_lc;
        $costing->tariff_per_ton_lc  = $request->tariff_per_ton_lc;
        $costing->tariff_per_kg_lc   = $request->tariff_per_kg_lc;
        $costing->actual_cost_per_kg = $request->actual_cost_per_kg;
        $costing->total_cost_per_kg  = $request->total_cost_per_kg;
        $costing->total_cost_per_box = $request->total_cost_per_box;

        $costing->save();

        return redirect()->back()->with('success', '✔ Costing saved successfully!');
    }

    public function viewCosting()
    {
        $costings = \App\Models\Costing::with(['supplier', 'product'])->get();
        return view('costing.viewCosting', compact('costings'));
    }
    public function destroy($id)
    {
        $costing = \App\Models\Costing::find($id);

        if (!$costing) {
            return redirect()->back()->with('error', 'Costing not found!');
        }

        $costing->delete();

        return redirect()->back()->with('success', 'Costing deleted successfully!');
    }
}
