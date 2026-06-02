<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Rest
 * 
 * @property int $id
 * @property string $type
 * @property string $reason
 * @property int $employee_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property Employee $employee
 *
 * @package App\Models
 */
class Leave extends Model
{
	protected $casts = [
		'employee_id' => 'int'
	];

	protected $guarded = [];

	public function employee()
	{
		return $this->belongsTo(Employee::class);
	}
}
