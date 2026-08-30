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
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $equipments = Equipment::all();
        $purchases = Purchase::with('equipment')->latest('purchased_at')->paginate(15);
        return view('admin.purchases', compact('purchases', 'equipments'));
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
        $data = $request->validate([
            'equipment_id' => ['required', 'exists:equipments,id'],
            'qty' => ['required', 'numeric', 'min:0.01'],
            'price' => ['required', 'numeric', 'min:0'],
            'purchased_at' => ['required', 'date'],
        ]);

        DB::transaction(function () use ($data) {
            $purchase = Purchase::create($data);
            Equipment::whereKey($data['equipment_id'])->increment('qty', $data['qty']);

            StockLedger::record(
                Equipment::findOrFail($data['equipment_id']),
                StockMovement::IN,
                StockMovement::REASON_SUPPLY,
                $data['qty'],
                ['note' => "Approvisionnement #{$purchase->id}"]
            );
        });

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

            // Undo the old purchase's effect on the (possibly former) equipment's stock...
            Equipment::whereKey($formerEquipmentId)->decrement('qty', $formerQty);
            $purchase->update($data);
            // ...then apply the new one.
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
