<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Licenciement
 * 
 * @property int $id
 * @property string $reason
 * @property int $employee_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property Employee $employee
 *
 * @package App\Models
 */
class Licenciement extends Model
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
