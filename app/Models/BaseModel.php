<?php

namespace App\Models;

use App\Models\Concerns\HasSoftDeleteFlag;
use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
{
    use HasSoftDeleteFlag;
}
