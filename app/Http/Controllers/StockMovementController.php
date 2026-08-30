<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\StockMovement;
use Illuminate\Http\Request;

class StockMovementController extends Controller
{
    /**
     * Archive logistique : journal des mouvements de stock + suivi des
     * épuisements. La liste est filtrable par équipement et par motif.
     */
    public function index(Request $request)
    {
        $equipments = Equipment::with('category')->orderBy('name')->get();

        $movements = StockMovement::with(['equipment', 'employee'])
            ->when($request->filled('equipment_id'), fn ($q) => $q->where('equipment_id', $request->integer('equipment_id')))
            ->when($request->filled('reason'), fn ($q) => $q->where('reason', $request->input('reason')))
            ->orderByDesc('id')
            ->paginate(25)
            ->withQueryString();

        $depletedEquipments = $equipments->filter(fn ($equipment) => $equipment->is_depleted);

        $stats = [
            'equipments' => $equipments->count(),
            'depleted' => $depletedEquipments->count(),
            'movements_month' => StockMovement::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
        ];

        $reasons = StockMovement::REASON_LABELS;

        return view('admin.stocks', compact('movements', 'equipments', 'depletedEquipments', 'stats', 'reasons'));
    }
}
