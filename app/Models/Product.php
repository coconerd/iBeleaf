<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Product
 * 
 * @property string $product_id
 * @property int|null $type
 * @property string|null $code
 * @property string|null $name
 * @property string|null $short_description
 * @property string|null $detailed_description
 * @property int|null $price
 * @property int|null $total_orders
 * @property int|null $total_ratings
 * @property float|null $overall_stars
 * @property int|null $is_returnable
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|OrderItem[] $order_items
 * @property Collection|Attribute[] $attributes
 * @property Collection|ProductCategory[] $product_categories
 * @property Collection|ProductFeedback[] $product_feedbacks
 * @property Wishlist $wishlist
 *
 * @package App\Models
 */
class Product extends Model
{
	protected $table = 'products';
	protected $primaryKey = 'product_id';
	public $incrementing = false;

	protected $casts = [
		'type' => 'int',
		'price' => 'int',
		'total_orders' => 'int',
		'total_ratings' => 'int',
		'overall_stars' => 'float',
		'is_returnable' => 'int'
	];

	protected $fillable = [
		'type',
		'code',
		'name',
		'short_description',
		'detailed_description',
		'price',
		'total_orders',
		'total_ratings',
		'overall_stars',
		'is_returnable'
	];

	public function order_items()
	{
		return $this->hasMany(OrderItem::class);
	}

	public function attributes()
	{
		return $this->belongsToMany(Attribute::class, 'product_attributes')
					->withPivot('value')
					->withTimestamps();
	}

	public function product_categories()
	{
		return $this->hasMany(ProductCategory::class);
	}

	public function product_feedbacks()
	{
		return $this->hasMany(ProductFeedback::class);
	}

	public function wishlist()
	{
		return $this->hasOne(Wishlist::class);
	}
}
