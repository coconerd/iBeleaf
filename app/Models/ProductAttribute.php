<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ProductAttribute
 * 
 * @property string $product_id
 * @property string $attribute_id
 * @property string|null $value
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Attribute $attribute
 * @property Product $product
 *
 * @package App\Models
 */
class ProductAttribute extends Model
{
	protected $table = 'product_attributes';
	public $incrementing = false;

	protected $fillable = [
		'value'
	];

	public function attribute()
	{
		return $this->belongsTo(Attribute::class);
	}

	public function product()
	{
		return $this->belongsTo(Product::class);
	}
}
