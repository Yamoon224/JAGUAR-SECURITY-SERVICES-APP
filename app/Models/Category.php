<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Category
 * 
 * @property int $id
 * @property string $name
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property Collection|Equipment[] $equipment
 *
 * @package App\Models
 */
class Category extends Model
{
    use HasFactory;
	protected $guarded = [];
	
	public function equipment()
	{
		return $this->hasMany(Equipment::class);
	}
}
