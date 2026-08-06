<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Equipment
 * 
 * @property int $id
 * @property string $name
 * @property float $price
 * @property float $qty
 * @property float $deteriorated_qty
 * @property string $unit
 * @property int $category_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property Category $category
 *
 * @package App\Models
 */
class Equipment extends BaseModel
{
	protected $casts = [
		'price' => 'float',
		'qty' => 'float',
		'deteriorated_qty' => 'float',
		'category_id' => 'int'
	];

	protected $guarded = [];

	public function category()
	{
		return $this->belongsTo(Category::class);
	}

	public function dotations()
	{
		return $this->hasMany(Dotation::class);
	}

	public function purchases()
	{
		return $this->hasMany(Purchase::class);
	}

	public function getAvailableQtyAttribute()
	{
		return $this->qty - $this->dotations->sum('qty') - ($this->deteriorated_qty ?? 0);
	}
}
