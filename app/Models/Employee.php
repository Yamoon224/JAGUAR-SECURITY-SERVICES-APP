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
 * @property float|null $prime
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
class Employee extends Model
{
	protected $casts = [
		'salary' => 'float',
		'prime' => 'float',
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
}
