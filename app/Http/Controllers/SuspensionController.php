<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Affectation;
use App\Models\Suspension;
use Illuminate\Http\Request;

class SuspensionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employees = Employee::where('deleted', 0)->get();
        $suspensions = Suspension::with('employee')->get();
        return view('admin.suspensions', compact('suspensions', 'employees'));
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
        Suspension::create($data);
        $affectation = Affectation::firstWhere('employee_id', $data['employee_id']);
        $affectation->delete();
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
        $suspension = Suspension::find($id);
        $data = $request->except('_token');
        $suspension->update($data);
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $suspension = Suspension::find($id);
        $suspension->delete();
        return back();
    }
}
