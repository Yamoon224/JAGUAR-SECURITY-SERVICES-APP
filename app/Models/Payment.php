<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Payment
 * 
 * @property int $id
 * @property float $amount
 * @property float $salary
 * @property float|null $prime
 * @property int $employee_id
 * @property int $month_id
 * @property int $year_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property Employee $employee
 *
 * @package App\Models
 */
class Payment extends Model
{
	protected $casts = [
		'amount' => 'float',
		'salary' => 'float',
		'prime' => 'float',
		'employee_id' => 'int',
		'month_id' => 'int',
		'year_id' => 'int'
	];

	protected $guarded = [];

	public function employee()
	{
		return $this->belongsTo(Employee::class);
	}
}
