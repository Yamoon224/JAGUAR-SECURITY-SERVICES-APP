<?php

namespace App\Http\Controllers;

use App\Models\Fueling;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FuelingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $fuelings = Fueling::latest('fueled_at')->get();
        return view('admin.fuelings', compact('fuelings'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Fueling::create($this->validated($request));
        return back();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $fueling = Fueling::findOrFail($id);
        $fueling->update($this->validated($request));
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        Fueling::findOrFail($id)->delete();
        return back();
    }

    /**
     * Shared validation rules for store & update.
     */
    private function validated(Request $request): array
    {
        return $request->validate([
            'fueled_at' => ['required', 'date'],
            'volume' => ['required', 'numeric', 'min:0.01'],
            'fuel_type' => ['required', Rule::in(Fueling::FUEL_TYPES)],
            'beneficiary_matricule' => ['required', 'string', 'max:255'],
            'beneficiary_function' => ['required', 'string', 'max:255'],
            'station_name' => ['required', 'string', 'max:255'],
            'vehicle_type' => ['required', Rule::in(Fueling::VEHICLE_TYPES)],
            'voucher_number' => ['nullable', 'string', 'max:255'],
        ]);
    }
}
