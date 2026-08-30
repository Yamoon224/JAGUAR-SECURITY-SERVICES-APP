<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Leaf
 *
 * @property int $id
 * @property Carbon $begin
 * @property Carbon $end
 * @property string $reason
 * @property string $type
 * @property string|null $destination
 * @property int $employee_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property Leaf $leaf
 * @property Collection|Leaf[] $leaves
 *
 * @package App\Models
 */
class Leaf extends BaseModel
{
	/**
	 * Natures de congé disponibles (clé => libellé).
	 */
	public const TYPES = [
		'annuel' => 'Congé annuel',
		'maladie' => 'Congé maladie',
		'sanitaire' => 'Congé sanitaire',
		'touristique' => 'Congé touristique',
		'exceptionnel' => 'Congé exceptionnel',
	];

	/**
	 * Types de congé pour lesquels la destination (pays ou ville) est obligatoire.
	 */
	public const TYPES_REQUIRING_DESTINATION = ['sanitaire', 'touristique'];

	protected $casts = ['employee_id' => 'int'];

	protected $guarded = [];

	public function employee()
	{
		return $this->belongsTo(Employee::class);
	}

	/**
	 * Libellé lisible de la nature du congé.
	 */
	public function getTypeLabelAttribute(): string
	{
		return self::TYPES[$this->type] ?? $this->type;
	}

	/**
	 * La destination est-elle requise pour ce congé ?
	 */
	public function requiresDestination(): bool
	{
		return in_array($this->type, self::TYPES_REQUIRING_DESTINATION, true);
	}
}
