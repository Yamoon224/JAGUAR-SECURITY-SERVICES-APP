<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Mail
 * 
 * @property int $id
 * @property string $category
 * @property string $name
 * @property string|null $expeditor
 * @property string|null $destinator
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Models
 */
class Appointment extends Model
{
	protected $guarded = [];
}
