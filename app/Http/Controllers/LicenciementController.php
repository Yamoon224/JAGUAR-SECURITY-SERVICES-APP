<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Affectation;
use App\Models\Licenciement;
use Illuminate\Http\Request;

class LicenciementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employees = Employee::all();
        $licenciements = Licenciement::with('employee')->get();
        return view('admin.licenciements', compact('licenciements', 'employees'));
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
        $employee = Employee::find($data['employee_id']);
        $employee->update(['deleted'=>1]);
        Affectation::where('employee_id', $employee->id)->delete();
        Licenciement::create($data);
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
        $licenciement = Licenciement::find($id);
        $data = $request->except(['_token', 'isleave']);
        if(empty($request->isleave)) 
        {
            $employee = Employee::find($licenciement->employee_id);
            $employee->update(['deleted'=>0]);    
        }
        $licenciement->update($data);
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $licenciement = Licenciement::find($id);
        $licenciement->delete();
        return back();
    }
}
