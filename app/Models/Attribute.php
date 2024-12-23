<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Attribute
 * 
 * @property string $attribute_id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|Product[] $products
 *
 * @package App\Models
 */
class Attribute extends Model
{
	protected $table = 'attributes';
	protected $primaryKey = 'attribute_id';
	public $incrementing = false;

	protected $fillable = [
		'name'
	];

	public function products()
	{
		return $this->belongsToMany(Product::class, 'product_attributes')
					->withPivot('value')
					->withTimestamps();
	}
}
