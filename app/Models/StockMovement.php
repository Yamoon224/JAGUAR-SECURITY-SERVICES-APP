<?php

namespace App\Models;

use Carbon\Carbon;

/**
 * Class StockMovement
 *
 * Une ligne d'archive logistique : un mouvement de stock d'équipement,
 * enregistré automatiquement par App\Services\StockLedger.
 *
 * @property int $id
 * @property int $equipment_id
 * @property string $direction
 * @property string $reason
 * @property float $quantity
 * @property float $stock_before
 * @property float $stock_after
 * @property int|null $employee_id
 * @property int|null $user_id
 * @property string|null $note
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property int $deleted
 *
 * @property Equipment $equipment
 * @property Employee|null $employee
 *
 * @package App\Models
 */
class StockMovement extends BaseModel
{
    public const IN = 'in';
    public const OUT = 'out';

    public const REASON_OPENING = 'ouverture';
    public const REASON_SUPPLY = 'approvisionnement';
    public const REASON_SUPPLY_CANCEL = 'annulation_approvisionnement';
    public const REASON_DOTATION = 'dotation';
    public const REASON_RESTITUTION = 'restitution';
    public const REASON_DETERIORATION = 'deterioration';
    public const REASON_REPAIR = 'reparation';
    public const REASON_ADJUSTMENT = 'ajustement';
    public const REASON_DEPLETION = 'epuisement';

    /** Libellés lisibles des motifs. */
    public const REASON_LABELS = [
        self::REASON_OPENING => "Solde d'ouverture",
        self::REASON_SUPPLY => 'Approvisionnement',
        self::REASON_SUPPLY_CANCEL => "Annulation d'approvisionnement",
        self::REASON_DOTATION => 'Dotation matérielle',
        self::REASON_RESTITUTION => 'Restitution de dotation',
        self::REASON_DETERIORATION => 'Détérioration',
        self::REASON_REPAIR => 'Réparation / remise en service',
        self::REASON_ADJUSTMENT => 'Ajustement de stock',
        self::REASON_DEPLETION => 'Épuisement de stock',
    ];

    protected $casts = [
        'equipment_id' => 'int',
        'employee_id' => 'int',
        'user_id' => 'int',
        'quantity' => 'float',
        'stock_before' => 'float',
        'stock_after' => 'float',
        'deleted' => 'int',
    ];

    protected $guarded = [];

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getReasonLabelAttribute(): string
    {
        return self::REASON_LABELS[$this->reason] ?? ucfirst(str_replace('_', ' ', $this->reason));
    }

    public function getIsInboundAttribute(): bool
    {
        return $this->direction === self::IN;
    }

    public function scopeDepletion($query)
    {
        return $query->where('reason', self::REASON_DEPLETION);
    }
}
