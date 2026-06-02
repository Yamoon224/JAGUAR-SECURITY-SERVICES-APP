<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Applicant
 * 
 * @property int $id
 * @property string $firstname
 * @property string $name
 * @property string $phone
 * @property string $applicationid
 * @property string|null $file
 * @property string $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property int $deleted
 *
 * @package App\Models
 */
class Applicant extends Model
{
	protected $casts = [
		'deleted' => 'int'
	];
	protected $guarded = [];
}
