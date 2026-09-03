<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\Purchase;
use App\Models\StockMovement;
use App\Services\StockLedger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    /** Valeur du select équipement pour « créer un nouvel équipement ». */
    private const NEW_EQUIPMENT = '__new__';

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $equipments = Equipment::with('dotations')->orderBy('name')->get();
        $purchases = Purchase::with('equipment')->latest('purchased_at')->paginate(15);
        return view('admin.purchases', compact('purchases', 'equipments'));
    }

    /**
     * Enregistre un approvisionnement : soit sur un équipement existant, soit
     * en créant l'équipement à la volée (formulaire fusionné avec « nouvel
     * équipement »).
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'equipment_id' => ['required', 'string'],
            'new_name' => ['required_if:equipment_id,' . self::NEW_EQUIPMENT, 'nullable', 'string', 'max:255'],
            'new_unit' => ['nullable', 'string', 'max:30'],
            'qty' => ['required', 'numeric', 'min:0.01'],
            'price' => ['required', 'numeric', 'min:0'],
            'purchased_at' => ['required', 'date'],
        ], [
            'equipment_id.required' => "Choisissez un équipement ou « Nouvel Equipement ».",
            'new_name.required_if' => "Saisissez le nom du nouvel équipement.",
        ]);

        $isNew = $data['equipment_id'] === self::NEW_EQUIPMENT;

        if (! $isNew && ! Equipment::whereKey($data['equipment_id'])->exists()) {
            return back()->withErrors(['equipment_id' => "Équipement introuvable."])->withInput();
        }

        DB::transaction(function () use ($data, $isNew) {
            if ($isNew) {
                $equipmentId = Equipment::create([
                    'name' => $data['new_name'],
                    'unit' => $data['new_unit'] ?? null,
                    'price' => $data['price'],
                    'qty' => 0,
                    'category_id' => Equipment::defaultCategoryId(),
                ])->id;
            } else {
                $equipmentId = (int) $data['equipment_id'];
            }

            $purchase = Purchase::create([
                'equipment_id' => $equipmentId,
                'qty' => $data['qty'],
                'price' => $data['price'],
                'purchased_at' => $data['purchased_at'],
            ]);

            Equipment::whereKey($equipmentId)->increment('qty', $data['qty']);

            StockLedger::record(
                Equipment::findOrFail($equipmentId),
                StockMovement::IN,
                $isNew ? StockMovement::REASON_OPENING : StockMovement::REASON_SUPPLY,
                $data['qty'],
                ['note' => ($isNew ? 'Nouvel équipement — approvisionnement' : 'Approvisionnement') . " #{$purchase->id}"]
            );
        });

        return back();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $purchase = Purchase::findOrFail($id);

        $data = $request->validate([
            'equipment_id' => ['required', 'exists:equipments,id'],
            'qty' => ['required', 'numeric', 'min:0.01'],
            'price' => ['required', 'numeric', 'min:0'],
            'purchased_at' => ['required', 'date'],
        ]);

        DB::transaction(function () use ($purchase, $data) {
            $formerEquipmentId = $purchase->equipment_id;
            $formerQty = (float) $purchase->qty;

            Equipment::whereKey($formerEquipmentId)->decrement('qty', $formerQty);
            $purchase->update($data);
            Equipment::whereKey($data['equipment_id'])->increment('qty', $data['qty']);

            StockLedger::record(
                Equipment::findOrFail($formerEquipmentId),
                StockMovement::OUT,
                StockMovement::REASON_ADJUSTMENT,
                $formerQty,
                ['note' => "Correction approvisionnement #{$purchase->id}"]
            );
            StockLedger::record(
                Equipment::findOrFail($data['equipment_id']),
                StockMovement::IN,
                StockMovement::REASON_SUPPLY,
                $data['qty'],
                ['note' => "Correction approvisionnement #{$purchase->id}"]
            );
        });

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $purchase = Purchase::findOrFail($id);

        DB::transaction(function () use ($purchase) {
            Equipment::whereKey($purchase->equipment_id)->decrement('qty', $purchase->qty);
            $purchase->delete();

            StockLedger::record(
                Equipment::findOrFail($purchase->equipment_id),
                StockMovement::OUT,
                StockMovement::REASON_SUPPLY_CANCEL,
                (float) $purchase->qty,
                ['note' => "Annulation approvisionnement #{$purchase->id}"]
            );
        });

        return back();
    }
}
