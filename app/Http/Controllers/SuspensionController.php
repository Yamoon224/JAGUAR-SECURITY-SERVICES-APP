<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Affectation;
use App\Models\Suspension;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $data = $request->validate([
            'employee_ids' => ['required', 'array', 'min:1'],
            'employee_ids.*' => ['exists:employees,id'],
            'reason' => ['required', 'string'],
            'duration' => ['required', 'integer', 'min:1'],
            'unit' => ['required', 'string'],
        ]);

        DB::transaction(function () use ($data) {
            foreach (array_unique($data['employee_ids']) as $employeeId) {
                Suspension::create([
                    'employee_id' => $employeeId,
                    'reason' => $data['reason'],
                    'duration' => $data['duration'],
                    'unit' => $data['unit'],
                ]);
                Affectation::where('employee_id', $employeeId)->get()->each->delete();
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
