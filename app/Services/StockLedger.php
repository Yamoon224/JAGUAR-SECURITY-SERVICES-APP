<?php

namespace App\Services;

use App\Models\Equipment;
use App\Models\StockMovement;

/**
 * Journal automatisé des mouvements de stock (archive logistique).
 *
 * Le contrôleur applique la mutation métier (achat, dotation, détérioration...)
 * puis appelle StockLedger::record() : le service prend un instantané du stock
 * disponible réel de l'équipement, écrit la ligne d'archive et, si le stock
 * vient de tomber à zéro, enregistre automatiquement un mouvement d'épuisement.
 */
class StockLedger
{
    /** Permet de désactiver ponctuellement l'archivage (imports, seeders...). */
    public static bool $enabled = true;

    /**
     * Enregistre un mouvement de stock pour un équipement.
     *
     * @param  array{employee_id?:int|null, note?:string|null}  $meta
     */
    public static function record(Equipment $equipment, string $direction, string $reason, float $quantity, array $meta = []): ?StockMovement
    {
        if (! self::$enabled) {
            return null;
        }

        $quantity = abs($quantity);
        $after = self::availableStock($equipment);
        $before = $direction === StockMovement::IN ? $after - $quantity : $after + $quantity;

        $movement = StockMovement::create([
            'equipment_id' => $equipment->id,
            'direction' => $direction,
            'reason' => $reason,
            'quantity' => $quantity,
            'stock_before' => $before,
            'stock_after' => $after,
            'employee_id' => $meta['employee_id'] ?? null,
            'user_id' => auth()->id(),
            'note' => $meta['note'] ?? null,
        ]);

        self::archiveDepletion($equipment, $before, $after);

        return $movement;
    }

    /**
     * Exécute un traitement sans alimenter l'archive.
     */
    public static function withoutTracking(callable $callback): mixed
    {
        $previous = self::$enabled;
        self::$enabled = false;

        try {
            return $callback();
        } finally {
            self::$enabled = $previous;
        }
    }

    /**
     * Stock disponible réel, recalculé depuis la base (qty - dotations - détérioré).
     */
    private static function availableStock(Equipment $equipment): float
    {
        $fresh = Equipment::with('dotations')->find($equipment->id);

        return $fresh ? (float) $fresh->available_qty : (float) $equipment->available_qty;
    }

    /**
     * Trace automatiquement l'épuisement quand le disponible passe à <= 0.
     */
    private static function archiveDepletion(Equipment $equipment, float $before, float $after): void
    {
        if ($after > 0 || $before <= 0) {
            return;
        }

        StockMovement::create([
            'equipment_id' => $equipment->id,
            'direction' => StockMovement::OUT,
            'reason' => StockMovement::REASON_DEPLETION,
            'quantity' => 0,
            'stock_before' => $after,
            'stock_after' => $after,
            'user_id' => auth()->id(),
            'note' => 'Stock épuisé : disponible ≤ 0',
        ]);
    }
}
