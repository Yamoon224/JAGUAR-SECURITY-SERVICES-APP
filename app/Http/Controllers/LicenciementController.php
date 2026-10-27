<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Affectation;
use App\Models\Licenciement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $data = $request->validate([
            'employee_ids' => ['required', 'array', 'min:1'],
            'employee_ids.*' => ['exists:employees,id'],
            'reason' => ['required', 'string'],
        ]);

        DB::transaction(function () use ($data) {
            foreach (array_unique($data['employee_ids']) as $employeeId) {
                Employee::whereKey($employeeId)->update(['deleted' => 1]);
                Affectation::where('employee_id', $employeeId)->get()->each->delete();
                Licenciement::create(['employee_id' => $employeeId, 'reason' => $data['reason']]);
            }
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
