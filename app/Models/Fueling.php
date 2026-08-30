<?php

namespace App\Models;

use Carbon\Carbon;

/**
 * Class Fueling
 *
 * Carburant alloué à une opération : un ravitaillement effectué au profit
 * d'un bénéficiaire (matricule / fonction) pour un engin donné, auprès d'une
 * station-service, justifié par un bon ou un chèque de carburant.
 *
 * @property int $id
 * @property Carbon $fueled_at
 * @property float $volume
 * @property string $fuel_type
 * @property string $beneficiary_matricule
 * @property string $beneficiary_function
 * @property string $station_name
 * @property string $vehicle_type
 * @property string|null $voucher_number
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property int $deleted
 *
 * @package App\Models
 */
class Fueling extends BaseModel
{
    /** Types de carburant disponibles. */
    public const FUEL_TYPES = ['essence', 'gasoil'];

    /** Types d'engin disponibles. */
    public const VEHICLE_TYPES = ['voiture', 'moto'];

    protected $casts = [
        'fueled_at' => 'datetime',
        'volume' => 'float',
        'deleted' => 'int',
    ];

    protected $guarded = [];
}
