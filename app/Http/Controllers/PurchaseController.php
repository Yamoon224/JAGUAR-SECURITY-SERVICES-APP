<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\Purchase;
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
        $purchases = Purchase::with('equipment')->latest('purchased_at')->get();
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
            Purchase::create($data);
            Equipment::whereKey($data['equipment_id'])->increment('qty', $data['qty']);
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
            // Undo the old purchase's effect on the (possibly former) equipment's stock...
            Equipment::whereKey($purchase->equipment_id)->decrement('qty', $purchase->qty);
            $purchase->update($data);
            // ...then apply the new one.
            Equipment::whereKey($data['equipment_id'])->increment('qty', $data['qty']);
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
        });

        return back();
    }
}
