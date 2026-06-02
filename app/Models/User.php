<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class User
 * 
 * @property int $id
 * @property string $email
 * @property string $phone
 * @property int $group_id
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $deleted
 * 
 * @property Group $group
 *
 * @package App\Models
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;	

	protected $casts = [
		'group_id' => 'int',
		'email_verified_at' => 'datetime',
		'deleted' => 'int'
	];

	protected $hidden = [
		'password',
		'remember_token'
	];

	protected $guarded = [];

	public function group()
	{
		return $this->belongsTo(Group::class);
	}
}
