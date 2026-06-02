<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Leaf;
use Illuminate\Http\Request;

class LeafController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employees = Employee::where('deleted', 0)->get();
        $leaves = Leaf::with('employee')->get();
        return view('admin.leaves', compact('leaves', 'employees'));
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
        $employee->update(array('isleaved'=>1));
        Leaf::create($data);
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
        $leaf = Leaf::find($id);
        $data = $request->except('_token');
        $leaf->update($data);
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $leaf = Leaf::find($id);
        $leaf->delete();
        return back();
    }
}
