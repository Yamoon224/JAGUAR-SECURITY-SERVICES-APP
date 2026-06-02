<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Flow
 * 
 * @property int $id
 * @property string $category
 * @property string $name
 * @property float $amount
 * @property string|null $reason
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Models
 */
class Flow extends Model
{
	protected $casts = [
		'amount' => 'float'
	];

	protected $guarded = [];
}
