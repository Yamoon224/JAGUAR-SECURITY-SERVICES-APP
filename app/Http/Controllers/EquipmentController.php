<?php

namespace App\Http\Controllers;

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
        // Les derniers approvisionnements en tête ; les équipements jamais
        // achetés (max nul) ferment la liste, par ordre alphabétique.
        $equipments = Equipment::with('dotations')
            ->withMax('purchases', 'purchased_at')
            ->orderByDesc('purchases_max_purchased_at')
            ->orderBy('name')
            ->get();

        return view('admin.equipments', compact('equipments'));
    }

    /**
     * Display the equipments that have a deteriorated quantity.
     */
    public function deteriorated()
    {
        $equipments = Equipment::with('dotations')
            ->where('deteriorated_qty', '>', 0)
            ->orderBy('name')
            ->get();
        return view('admin.equipments-deteriorated', compact('equipments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['category_id'] = Equipment::defaultCategoryId();

        $equipment = Equipment::create($data);

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
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $equipment = Equipment::findOrFail($id);
        $data = $this->validated($request);

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
        Equipment::findOrFail($id)->delete();
        return back();
    }

    /**
     * Champs autorisés pour un équipement.
     */
    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'qty' => ['nullable', 'numeric', 'min:0'],
            'deteriorated_qty' => ['nullable', 'numeric', 'min:0'],
        ]);
    }
}
