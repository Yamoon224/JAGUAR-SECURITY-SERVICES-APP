<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dotation extends Model
{
    use HasFactory;

	protected $guarded = [];

    public function employee()
	{
		return $this->belongsTo(Employee::class);
	}

    public function equipment()
	{
		return $this->belongsTo(Equipment::class);
	}
}
