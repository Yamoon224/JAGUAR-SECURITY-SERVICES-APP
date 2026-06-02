<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $appointments = Appointment::all();
        return view('admin.appointments', compact('appointments'));
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
        $data['created_by'] = $data['updated_by'] = auth()->id();
        Appointment::create( $data );

        return back()->with(['message'=>__('locale.save', ['param'=>__('locale.category', ['suffix'=>'', 'prefix'=>''])])]);
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
        $data = $request->except(['_token']);
        $appointment = Appointment::find($id);
        $appointment->update($data);

        return back()->with(['message'=>__('locale.update', ['param'=>__('locale.Appointment', ['suffix'=>'', 'prefix'=>''])])]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $appointment = Appointment::find($id);
        $appointment->delete();

        return back()->with(['message'=>__('locale.delete', ['param'=>__('locale.Appointment', ['suffix'=>'', 'prefix'=>''])])]);
    }
}
