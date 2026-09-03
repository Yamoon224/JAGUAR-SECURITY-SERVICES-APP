<?php

namespace App\Http\Controllers;

use App\Models\Dotation;
use App\Models\Employee;
use App\Models\Equipment;
use App\Models\StockMovement;
use App\Services\StockLedger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DotationController extends Controller
{
    /**
     * Liste des employés bénéficiaires de dotation matérielle (vue "cartes"),
     * avec recherche par nom, prénom ou matricule.
     */
    public function index()
    {
        $beneficiaries = Employee::where('deleted', 0)
            ->whereHas('dotations')
            ->withCount('dotations')
            ->orderBy('name')
            ->paginate(16);

        $employees = Employee::where('deleted', 0)->orderBy('name')->get();
        $equipments = $this->availableEquipments();

        return view('admin.dotations', compact('beneficiaries', 'employees', 'equipments'));
    }

    /**
     * Recherche AJAX : retourne le partiel "cartes" filtré.
     */
    public function search(Request $request)
    {
        $key = trim((string) $request->input('search'));

        $beneficiaries = Employee::where('deleted', 0)
            ->whereHas('dotations')
            ->where(function ($query) use ($key) {
                $query->where('name', 'LIKE', "%{$key}%")
                    ->orWhere('firstname', 'LIKE', "%{$key}%")
                    ->orWhere('matricule', 'LIKE', "%{$key}%");
            })
            ->withCount('dotations')
            ->orderBy('name')
            ->get();

        return view('admin.search_dotations', compact('beneficiaries'));
    }

    /**
     * Historique des dotations matérielles d'un employé, dates incluses.
     */
    public function history(int $employee)
    {
        $employee = Employee::where('deleted', 0)->findOrFail($employee);
        $dotations = $employee->dotations()->with('equipment')->latest()->get();
        $equipments = $this->availableEquipments();
        $employees = Employee::where('deleted', 0)->orderBy('name')->get();
        $allEquipments = Equipment::with('dotations')->get();

        return view('admin.dotation', compact('employee', 'dotations', 'equipments', 'employees', 'allEquipments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $this->validated($request);

        DB::transaction(function () use ($data) {
            $dotation = Dotation::create($data);

            StockLedger::record(
                Equipment::findOrFail($data['equipment_id']),
                StockMovement::OUT,
                StockMovement::REASON_DOTATION,
                $data['qty'],
                ['employee_id' => $data['employee_id'], 'note' => "Dotation #{$dotation->id}"]
            );
        });

        return back();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $dotation = Dotation::findOrFail($id);
        $data = $this->validated($request, $dotation);

        DB::transaction(function () use ($dotation, $data) {
            $formerEquipmentId = $dotation->equipment_id;
            $formerQty = (float) $dotation->qty;
            $formerEmployeeId = $dotation->employee_id;

            $dotation->update($data);

            // Restitution du matériel de l'ancienne ligne, puis nouvelle sortie.
            StockLedger::record(
                Equipment::findOrFail($formerEquipmentId),
                StockMovement::IN,
                StockMovement::REASON_RESTITUTION,
                $formerQty,
                ['employee_id' => $formerEmployeeId, 'note' => "Correction dotation #{$dotation->id}"]
            );
            StockLedger::record(
                Equipment::findOrFail($data['equipment_id']),
                StockMovement::OUT,
                StockMovement::REASON_DOTATION,
                $data['qty'],
                ['employee_id' => $data['employee_id'], 'note' => "Correction dotation #{$dotation->id}"]
            );
        });

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $dotation = Dotation::findOrFail($id);

        DB::transaction(function () use ($dotation) {
            $equipmentId = $dotation->equipment_id;
            $qty = (float) $dotation->qty;
            $employeeId = $dotation->employee_id;

            $dotation->delete();

            StockLedger::record(
                Equipment::findOrFail($equipmentId),
                StockMovement::IN,
                StockMovement::REASON_RESTITUTION,
                $qty,
                ['employee_id' => $employeeId, 'note' => "Restitution dotation #{$dotation->id}"]
            );
        });

        return back();
    }

    /**
     * Règles de validation partagées pour la dotation matérielle.
     *
     * Un équipement épuisé (disponible <= 0) ne peut pas être doté, et la
     * quantité demandée ne peut pas dépasser le stock réellement disponible.
     */
    private function validated(Request $request, ?Dotation $current = null): array
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => ['required', 'exists:employees,id'],
            'equipment_id' => ['required', 'exists:equipment,id'],
            'qty' => ['required', 'numeric', 'min:1'],
        ]);

        $validator->after(function ($validator) use ($request, $current) {
            $equipment = Equipment::with('dotations')->find($request->input('equipment_id'));

            if (! $equipment) {
                return;
            }

            $available = (float) $equipment->available_qty;

            // En édition sur le même équipement, la quantité de la ligne
            // courante est d'abord "restituée".
            if ($current && (int) $current->equipment_id === (int) $equipment->id) {
                $available += (float) $current->qty;
            }

            if ($available <= 0) {
                $validator->errors()->add('equipment_id',
                    "L'équipement « {$equipment->name} » est épuisé : aucune unité disponible pour une dotation.");

                return;
            }

            if ((float) $request->input('qty') > $available) {
                $validator->errors()->add('qty',
                    "Stock insuffisant : {$available} disponible(s) pour « {$equipment->name} ».");
            }
        });

        return $validator->validate();
    }

    /**
     * Équipements encore disponibles au stock (pour les formulaires de dotation).
     */
    private function availableEquipments()
    {
        return Equipment::with('dotations')->get()->filter(fn ($equipment) => $equipment->available_qty > 0)->values();
    }
}
