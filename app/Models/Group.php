<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Group
 * 
 * @property int $id
 * @property string $name
 * @property string|null $description
 * 
 * @property Collection|User[] $users
 *
 * @package App\Models
 */
class Group extends Model
{
	public $timestamps = false;
	protected $guarded = [];


	public function users()
	{
		return $this->hasMany(User::class);
	}
}
