<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Employee
 * 
 * @property int $id
 * @property string $firstname
 * @property string $name
 * @property string $gender
 * @property string $phone
 * @property string|null $email
 * @property string $position
 * @property string $studygrade
 * @property string $familystatus
 * @property string $contract
 * @property float $salary
 * @property float|null $prime La colonne brute n'est plus alimentée : la valeur exposée est calculée, voir getPrimeAttribute().
 * @property string $emergency_name
 * @property string $emergency_phone
 * @property string $status
 * @property int $isleaved
 * @property int $issuspended
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property int $deleted
 * 
 * @property Collection|Licenciement[] $licenciements
 * @property Collection|Payment[] $payments
 * @property Collection|Suspension[] $suspensions
 *
 * @package App\Models
 */
class Employee extends BaseModel
{
	protected $casts = [
		'salary' => 'float',
		'prime' => 'float',
		'transport_indemnity' => 'float',
		'meal_allowance' => 'float',
		'housing_indemnity' => 'float',
		'punctuality_allowance' => 'float',
		'responsibility_allowance' => 'float',
		'contract_start_at' => 'date',
		'contract_end_at' => 'date',
		'isleaved' => 'int',
		'issuspended' => 'int',
		'deleted' => 'int'
	];
	protected $guarded = [];

	public function licenciement()
	{
		return $this->hasOne(Licenciement::class);
	}

	public function payments()
	{
		return $this->hasMany(Payment::class);
	}

	public function suspensions()
	{
		return $this->hasMany(Suspension::class);
	}

	public function leaves()
	{
		return $this->hasMany(Leaf::class);
	}

	public function dotations()
	{
		return $this->hasMany(Dotation::class);
	}

	public function affectations()
	{
		return $this->hasMany(Affectation::class);
	}
	
	public function currentAffectation()
	{
		return$this->affectations->sortByDesc('updated_at')->first();
	}

	/**
	 * La "prime" globale a été subdivisée en indemnités détaillées
	 * (transport, repas, logement, ponctualité, responsabilité). Ce champ
	 * n'a plus de formulaire pour être saisi directement : partout où le
	 * code lit $employee->prime (paie, CNSS/RTS, bulletins, reçus...), on
	 * expose désormais la somme de ces indemnités à la place.
	 */
	public function getPrimeAttribute()
	{
		return ($this->transport_indemnity ?? 0)
			+ ($this->meal_allowance ?? 0)
			+ ($this->housing_indemnity ?? 0)
			+ ($this->punctuality_allowance ?? 0)
			+ ($this->responsibility_allowance ?? 0);
	}
}
