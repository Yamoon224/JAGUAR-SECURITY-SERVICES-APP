<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Suspension
 * 
 * @property int $id
 * @property int $duration
 * @property string $unit
 * @property string $reason
 * @property int $employee_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property Employee $employee
 *
 * @package App\Models
 */
class Suspension extends Model
{
	protected $casts = ['duration' => 'int', 'employee_id' => 'int'];
	protected $guarded = [];

	public function employee()
	{
		return $this->belongsTo(Employee::class);
	}
}
