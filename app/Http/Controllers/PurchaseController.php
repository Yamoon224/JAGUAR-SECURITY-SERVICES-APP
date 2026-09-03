<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\Purchase;
use App\Models\StockMovement;
use App\Services\StockLedger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PurchaseController extends Controller
{
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
     * Enregistre un approvisionnement. Le formulaire ne propose plus de
     * sélectionner un équipement : le nom et l'unité sont toujours saisis.
     * Un nom déjà connu réutilise sa fiche et vient cumuler la quantité ;
     * sinon la fiche est créée.
     */
    public function store(Request $request)
    {
        // Sac d'erreurs dédié : la page porte aussi les formulaires d'édition,
        // le modal « Nouvel Achat » ne doit réagir qu'à sa propre validation.
        $data = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'unit' => ['required', 'string', 'max:30'],
            'qty' => ['required', 'numeric', 'min:0.01'],
            'price' => ['required', 'numeric', 'min:0'],
            'purchased_at' => ['required', 'date'],
        ])->validateWithBag('purchaseAdd');

        DB::transaction(function () use ($data) {
            $equipment = $this->equipmentNamed($data['name']);
            $isNew = $equipment === null;

            if ($isNew) {
                $equipment = Equipment::create([
                    'name' => trim($data['name']),
                    'unit' => $data['unit'],
                    'price' => $data['price'],
                    'qty' => 0,
                    'category_id' => Equipment::defaultCategoryId(),
                ]);
            } elseif (blank($equipment->unit)) {
                // Fiche existante laissée sans unité : on la complète, sans
                // jamais écraser le nom, l'unité ou le prix déjà en place.
                $equipment->update(['unit' => $data['unit']]);
            }

            $purchase = Purchase::create([
                'equipment_id' => $equipment->id,
                'qty' => $data['qty'],
                'price' => $data['price'],
                'purchased_at' => $data['purchased_at'],
            ]);

            Equipment::whereKey($equipment->id)->increment('qty', $data['qty']);

            StockLedger::record(
                $equipment->refresh(),
                StockMovement::IN,
                $isNew ? StockMovement::REASON_OPENING : StockMovement::REASON_SUPPLY,
                $data['qty'],
                ['note' => ($isNew ? 'Nouvel équipement — approvisionnement' : 'Approvisionnement') . " #{$purchase->id}"]
            );
        });

        return back();
    }

    /**
     * Fiche équipement portant ce nom, insensible à la casse et aux espaces
     * de bord, afin qu'un même article ne se dédouble pas à chaque achat.
     */
    private function equipmentNamed(string $name): ?Equipment
    {
        return Equipment::whereRaw('LOWER(TRIM(name)) = ?', [mb_strtolower(trim($name))])->first();
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
