<?php

namespace App\Http\Controllers;

use App\Models\Dotation;
use App\Models\Employee;
use App\Models\Equipment;
use App\Http\Requests\StoreDotationRequest;
use App\Http\Requests\UpdateDotationRequest;

class DotationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $equipments = Equipment::all();
        $employees = Employee::where('deleted', 0)->get();
        $dotations = Dotation::with('employee', 'equipment')->get();
        return view('admin.dotations', compact('equipments', 'dotations', 'employees'));
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
    public function store(StoreDotationRequest $request)
    {
        $data = $request->except('_token');
        Dotation::create($data);
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Dotation $dotation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Dotation $dotation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDotationRequest $request, int $id)
    {
        $dotation = Dotation::find($id);
        $data = $request->except('_token');
        $dotation->update($data);
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $dotation = Dotation::find($id);
        $dotation->delete();
        return back();
    }
}
