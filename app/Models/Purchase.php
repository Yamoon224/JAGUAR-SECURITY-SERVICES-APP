<?php

namespace App\Models;

use Carbon\Carbon;

/**
 * Class Purchase
 *
 * @property int $id
 * @property int $equipment_id
 * @property float $qty
 * @property float $price
 * @property Carbon $purchased_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property int $deleted
 *
 * @property Equipment $equipment
 *
 * @package App\Models
 */
class Purchase extends BaseModel
{
	protected $casts = [
		'equipment_id' => 'int',
		'qty' => 'float',
		'price' => 'float',
		'purchased_at' => 'date',
		'deleted' => 'int'
	];
	protected $guarded = [];

	public function equipment()
	{
		return $this->belongsTo(Equipment::class);
	}
}
