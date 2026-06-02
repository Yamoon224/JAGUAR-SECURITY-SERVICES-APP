<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Employee;
use App\Models\Affectation;
use App\Http\Requests\StoreAffectationRequest;
use App\Http\Requests\UpdateAffectationRequest;

class AffectationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = Customer::where('deleted', 0)->get();
        $employees = Employee::where(['deleted'=>0])->get();
        $affectations = Affectation::orderByDesc('id')->get();
        return view('admin.affectations', compact('affectations', 'customers', 'employees'));
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
    public function store(StoreAffectationRequest $request)
    {
        $data = $request->except('_token');
        $isAffected = Affectation::firstWhere('employee_id', $data['employee_id']);
        if($isAffected) $isAffected->delete();
        Affectation::create($data);
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Affectation $affectation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Affectation $affectation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAffectationRequest $request, int $id)
    {
        $customer = Affectation::find($id);
        $data = $request->except('_token');
        $customer->update($data);
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $affectation = Affectation::find($id);
        $affectation->delete();
        return back();
    }
}
