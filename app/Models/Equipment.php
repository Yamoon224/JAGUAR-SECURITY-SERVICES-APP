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
	// "equipment" is an uncountable noun, so make the table name explicit
	// instead of relying on Eloquent's pluralization guess.
	protected $table = 'equipment';

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

	/**
	 * La gestion des catégories a été retirée : tout équipement est rattaché
	 * à une catégorie technique unique, pour respecter la contrainte de clé
	 * étrangère de la table `equipment`.
	 */
	public static function defaultCategoryId(): int
	{
		return Category::withoutGlobalScopes()->firstOrCreate(['name' => 'Général'])->id;
	}

	public function dotations()
	{
		return $this->hasMany(Dotation::class);
	}

	public function purchases()
	{
		return $this->hasMany(Purchase::class);
	}

	public function stockMovements()
	{
		return $this->hasMany(StockMovement::class);
	}

	public function getAvailableQtyAttribute()
	{
		return $this->qty - $this->dotations->sum('qty') - ($this->deteriorated_qty ?? 0);
	}

	/**
	 * L'équipement est épuisé lorsqu'il n'y a plus aucune unité disponible.
	 */
	public function getIsDepletedAttribute(): bool
	{
		return $this->available_qty <= 0;
	}
}
