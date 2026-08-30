<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Leaf;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $this->validated($request);
        Employee::whereKey($data['employee_id'])->update(['isleaved' => 1]);
        Leaf::create($data);
        return back();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $leaf = Leaf::findOrFail($id);
        $leaf->update($this->validated($request));
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Leaf::findOrFail($id)->delete();
        return back();
    }

    /**
     * Règles de validation partagées.
     *
     * Toute demande de congé est formalisée par une lettre d'acceptation
     * (nom, prénom, matricule, motif, période accordée). Pour les congés
     * sanitaires ou touristiques, la destination est obligatoire.
     */
    private function validated(Request $request): array
    {
        return $request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'begin' => ['required', 'date'],
            'end' => ['required', 'date', 'after_or_equal:begin'],
            'reason' => ['required', 'string'],
            'type' => ['required', Rule::in(array_keys(Leaf::TYPES))],
            'destination' => [
                Rule::requiredIf(fn () => in_array($request->input('type'), Leaf::TYPES_REQUIRING_DESTINATION, true)),
                'nullable',
                'string',
                'max:255',
            ],
        ], [
            'destination.required' => 'La destination (pays ou ville) est obligatoire pour un congé sanitaire ou touristique.',
            'end.after_or_equal' => 'La date de fin doit être postérieure ou égale à la date de début.',
        ]);
    }
}
