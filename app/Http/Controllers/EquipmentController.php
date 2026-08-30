<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Equipment;
use App\Models\StockMovement;
use App\Services\StockLedger;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        $equipments = Equipment::with('category', 'dotations')->get();
        return view('admin.equipments', compact('equipments', 'categories'));
    }

    /**
     * Display the equipments that have a deteriorated quantity.
     */
    public function deteriorated()
    {
        $categories = Category::all();
        $equipments = Equipment::with('category', 'dotations')
            ->where('deteriorated_qty', '>', 0)
            ->get();
        return view('admin.equipments-deteriorated', compact('equipments', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->except('_token');
        $equipment = Equipment::create($data);

        // Solde d'ouverture dans l'archive logistique.
        StockLedger::record(
            $equipment,
            StockMovement::IN,
            StockMovement::REASON_OPENING,
            (float) ($equipment->qty ?? 0),
            ['note' => 'Création de la fiche équipement']
        );

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $equipment = Equipment::findOrFail($id);
        $data = $request->except('_token');

        $formerQty = (float) $equipment->qty;
        $formerDeteriorated = (float) ($equipment->deteriorated_qty ?? 0);

        $equipment->update($data);
        $equipment->refresh();

        // Détérioration / remise en service.
        $deterioratedDelta = (float) ($equipment->deteriorated_qty ?? 0) - $formerDeteriorated;
        if ($deterioratedDelta > 0) {
            StockLedger::record($equipment, StockMovement::OUT, StockMovement::REASON_DETERIORATION, $deterioratedDelta, ['note' => 'Mise à jour de la quantité détériorée']);
        } elseif ($deterioratedDelta < 0) {
            StockLedger::record($equipment, StockMovement::IN, StockMovement::REASON_REPAIR, abs($deterioratedDelta), ['note' => 'Remise en service']);
        }

        // Ajustement manuel du stock total.
        $qtyDelta = (float) $equipment->qty - $formerQty;
        if ($qtyDelta > 0) {
            StockLedger::record($equipment, StockMovement::IN, StockMovement::REASON_ADJUSTMENT, $qtyDelta, ['note' => 'Ajustement manuel du stock']);
        } elseif ($qtyDelta < 0) {
            StockLedger::record($equipment, StockMovement::OUT, StockMovement::REASON_ADJUSTMENT, abs($qtyDelta), ['note' => 'Ajustement manuel du stock']);
        }

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $equipment = Equipment::find($id);
        $equipment->delete();
        return back();
    }
}
