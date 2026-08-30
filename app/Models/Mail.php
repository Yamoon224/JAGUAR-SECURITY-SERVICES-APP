<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class Mail
 *
 * @property int $id
 * @property string $mail_id
 * @property Carbon $mail_datetime
 * @property string $name
 * @property string|null $srce
 * @property string|null $destinator
 * @property string|null $subject
 * @property string|null $observation
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property int $deleted
 *
 * @package App\Models
 */
class Mail extends BaseModel
{
	protected $guarded = [];
}
