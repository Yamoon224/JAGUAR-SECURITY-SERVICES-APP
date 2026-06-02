<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Logistic
 * 
 * @property int $id
 * @property string $type
 * @property string $transaction
 * @property float $amount
 * @property string|null $reason
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Models
 */
class Logistic extends Model
{
	protected $casts = [
		'amount' => 'float'
	];

	protected $guarded = [];
}
