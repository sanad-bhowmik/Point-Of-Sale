<?php

namespace App\Http\Controllers;

use App\Models\Costing;
use App\Models\CostingLog;
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
        $costing->tt_amount          = $request->tt_amount;
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

   public function storeLc(Request $request)
{
    // Validate the request
    try {
        $validated = $request->validate([
            'costing_id'           => 'required|exists:costing,id',
            'lc_name'              => 'required|string|max:255',
            'lc_date'              => 'required|date',
            'lc_number'            => 'required|string|max:255',
            'shipment_date'        => 'required|date',
            'arriving_date'        => 'required|date',
            'dhl_number'           => 'nullable|string|max:255',
            'bl_number'            => 'nullable|string|max:255',
            'doc_status'           => 'nullable|string|max:255',
            'bill_of_entry_amount' => 'nullable|numeric',
            'etd_date'             => 'nullable|date',
            'eta_date'             => 'nullable|date',
            'tt_amount'            => 'nullable|numeric',
        ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        // Return JSON if validation fails
        return response()->json([
            'success' => false,
            'errors' => $e->errors()
        ], 422);
    }

    // Create LC record
    $lc = \App\Models\Lc::create([
        'lc_name'              => $request->lc_name,
        'lc_date'              => $request->lc_date,
        'lc_number'            => $request->lc_number,
        'shipment_date'        => $request->shipment_date,
        'arriving_date'        => $request->arriving_date,
        'dhl_number'           => $request->dhl_number,
        'bl_number'            => $request->bl_number,
        'doc_status'           => $request->doc_status,
        'bill_of_entry_amount' => $request->bill_of_entry_amount,
        'etd_date'             => $request->etd_date,
        'eta_date'             => $request->eta_date,
    ]);

    // Update the costing record with LC ID and TT amount
    $costing = \App\Models\Costing::findOrFail($request->costing_id);
    $costing->lc_id = $lc->id;
    $costing->tt_amount = $request->tt_amount ?? 0; // default to 0 if null
    $costing->save();

    // Return JSON response
    return response()->json([
        'success' => true,
        'message' => '✔ LC saved successfully!',
        'lc_id'   => $lc->id
    ]);
}



    public function updateCosting(Request $request)
    {
        // Validation
        $request->validate([
            'id'                 => 'required|exists:costing,id',
            'exchange_rate'      => 'required|numeric',
            'base_value'         => 'required|numeric',
            'qty'                => 'required|numeric',
            'transport'          => 'nullable|numeric',
            'arrot'              => 'nullable|numeric',
            'cns_charge'         => 'nullable|numeric',
            'actual_cost_per_kg' => 'required|numeric',
        ]);

        // Find the costing
        $costing = Costing::findOrFail($request->id);

        // Log previous state before updating
        CostingLog::create([
            'costing_id'         => $costing->id,
            'supplier_id'        => $costing->supplier_id,
            'product_id'         => $costing->product_id,
            'box_type'           => $costing->box_type,
            'size'               => $costing->size,
            'currency'           => $costing->currency,
            'base_value'         => $costing->base_value,
            'qty'                => $costing->qty,
            'exchange_rate'      => $costing->exchange_rate,
            'total'              => $costing->total,
            'total_tk'           => $costing->total_tk,
            'insurance'          => $costing->insurance,
            'insurance_tk'       => $costing->insurance_tk,
            'landing_charge'     => $costing->landing_charge,
            'landing_charge_tk'  => $costing->landing_charge_tk,
            'cd'                 => $costing->cd,
            'rd'                 => $costing->rd,
            'sd'                 => $costing->sd,
            'vat'                => $costing->vat,
            'ait'                => $costing->ait,
            'at'                 => $costing->at,
            'atv'                => $costing->atv,
            'total_tax'          => $costing->total_tax,
            'transport'          => $costing->transport,
            'arrot'              => $costing->arrot,
            'cns_charge'         => $costing->cns_charge,
            'others_total'       => $costing->others_total,
            'total_tariff_lc'    => $costing->total_tariff_lc,
            'tariff_per_ton_lc'  => $costing->tariff_per_ton_lc,
            'tariff_per_kg_lc'   => $costing->tariff_per_kg_lc,
            'actual_cost_per_kg' => $costing->actual_cost_per_kg,
            'total_cost_per_kg'  => $costing->total_cost_per_kg,
            'total_cost_per_box' => $costing->total_cost_per_box,
            'updated_attempt'    => $costing->updated_attempt ? $costing->updated_attempt + 1 : 1,
        ]);

        // Increment update attempt
        $costing->updated_attempt = $costing->updated_attempt ? $costing->updated_attempt + 1 : 1;

        // Update fields from request
        $costing->exchange_rate      = $request->exchange_rate;
        $costing->base_value         = $request->base_value;
        $costing->qty                = $request->qty;
        $costing->transport          = $request->transport ?? 0;
        $costing->arrot              = $request->arrot ?? 0;
        $costing->cns_charge         = $request->cns_charge ?? 0;
        $costing->actual_cost_per_kg = $request->actual_cost_per_kg;

        // --- CALCULATIONS ---
        $total       = $costing->base_value * $costing->qty;
        $total_tk    = $total * $costing->exchange_rate;
        $insurance   = $total * 0.01;
        $insurance_tk = $insurance * $costing->exchange_rate;
        $landing_charge = ($total + $insurance) * 0.01;
        $landing_charge_tk = $landing_charge * $costing->exchange_rate;

        $cd = ($total_tk + $insurance_tk + ($total_tk + $insurance_tk) * 0.01) * 0.25;
        $rd = ($total_tk + $insurance_tk + ($total_tk + $insurance_tk) * 0.01) * 0.20;
        $sd = ($total_tk + $insurance_tk + ($total_tk + $insurance_tk) * 0.01 + $cd + $rd) * 0.30;
        $vat = ($total_tk + $insurance_tk + ($total_tk + $insurance_tk) * 0.01 + $cd + $rd + $sd) * 0.15;
        $ait = ($total_tk + $insurance_tk + ($total_tk + $insurance_tk) * 0.01) * 0.05;

        $total_tax   = $cd + $rd + $sd + $vat + $ait + $costing->at + $costing->atv;
        $others_total = $costing->transport + $costing->arrot + $costing->cns_charge;

        $total_tariff_lc = $total_tk + $insurance_tk + $landing_charge_tk + $total_tax + $others_total;

        // Box type fallback to 1 if empty
        $boxType = $costing->box_type ?: 1;

        $tariff_per_ton_lc = $total_tariff_lc / 23.72;
        $tariff_per_kg_lc = $tariff_per_ton_lc / 1000;

        $total_cost_per_kg = $tariff_per_kg_lc - ($costing->actual_cost_per_kg / $boxType * $costing->exchange_rate);
        $total_cost_per_box = $total_cost_per_kg * $boxType;

        // --- SAVE CALCULATED VALUES ---
        $costing->total               = $total;
        $costing->total_tk            = $total_tk;
        $costing->insurance           = $insurance;
        $costing->insurance_tk        = $insurance_tk;
        $costing->landing_charge      = $landing_charge;
        $costing->landing_charge_tk   = $landing_charge_tk;
        $costing->cd                  = $cd;
        $costing->rd                  = $rd;
        $costing->sd                  = $sd;
        $costing->vat                 = $vat;
        $costing->ait                 = $ait;
        $costing->total_tax           = $total_tax;
        $costing->others_total        = $others_total;
        $costing->total_tariff_lc     = $total_tariff_lc;
        $costing->tariff_per_ton_lc   = $tariff_per_ton_lc;
        $costing->tariff_per_kg_lc    = $tariff_per_kg_lc;
        $costing->total_cost_per_kg   = $total_cost_per_kg;
        $costing->total_cost_per_box  = $total_cost_per_box;

        $costing->save();

        return response()->json([
            'success' => true,
            'message' => 'Costing updated successfully!',
            'costing' => $costing
        ]);
    }
}
