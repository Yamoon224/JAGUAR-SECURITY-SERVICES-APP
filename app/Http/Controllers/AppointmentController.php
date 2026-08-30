<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource (vue calendrier).
     */
    public function index()
    {
        $appointments = Appointment::orderByDesc('expected_at')->get();

        $events = $appointments->map(function ($item) {
            $date = Carbon::parse($item->expected_at)->format('Y-m-d');
            $start = $item->start_time
                ? $date . 'T' . Carbon::parse($item->start_time)->format('H:i:s')
                : $date;
            $end = $item->end_time
                ? $date . 'T' . Carbon::parse($item->end_time)->format('H:i:s')
                : null;

            return [
                'id' => (string) $item->id,
                'title' => trim($item->visitor . ($item->company ? ' — ' . $item->company : '')),
                'start' => $start,
                'end' => $end,
                'extendedProps' => [
                    'visitor' => $item->visitor,
                    'phone' => $item->phone,
                    'company' => $item->company,
                ],
            ];
        })->values();

        return view('admin.appointments', compact('appointments', 'events'));
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
        $data = $request->except(['_token', '_method']);
        $data['created_by'] = $data['updated_by'] = auth()->id();
        Appointment::create($data);

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
        $data = $request->except(['_token', '_method']);
        $data['updated_by'] = auth()->id();

        $appointment = Appointment::findOrFail($id);
        $appointment->update($data);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['ok' => true]);
        }

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Appointment::findOrFail($id)->delete();

        return back();
    }
}
