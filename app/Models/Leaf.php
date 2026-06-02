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
 * @property int $employee_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property Leaf $leaf
 * @property Collection|Leaf[] $leaves
 *
 * @package App\Models
 */
class Leaf extends Model
{
	protected $casts = ['employee_id' => 'int'];

	protected $guarded = [];

	public function employee()
	{
		return $this->belongsTo(Employee::class);
	}
}
