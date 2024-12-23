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
		'is_returnable' => 'int',
		'stock_quantity' => 'int',
		'discount_percentage' => 'float',
	];

	protected $fillable = [
		'type',
		'code',
		'name',
		'short_description',
		'detailed_description',
		'discount_percentage',
		'price',
		'total_orders',
		'total_ratings',
		'overall_stars',
		'is_returnable',
		'stock_quantity',
	];

	public function order_items()
	{
		return $this->hasMany(OrderItem::class, 'product_id', 'product_id');
	}

	public function attributes()
	{
		return $this->belongsToMany(Attribute::class, 'product_attributes', 'product_id', 'attribute_id')
			->withPivot('value')
			->withTimestamps();
	}

	public function categories()
	{
		return $this->belongsToMany(Category::class, 'product_categories', 'product_id', 'category_id');
	}

	public function product_categories()
	{
		return $this->hasMany(ProductCategory::class, 'product_id', 'product_id');
	}

	public function product_feedbacks()
	{
		return $this->hasMany(ProductFeedback::class, 'product_id', 'product_id');
	}

	public function wishlist()
	{
		return $this->hasOne(Wishlist::class, 'product_id', 'product_id');
	}

	public function product_images()
	{
		return $this->hasMany(ProductImage::class, 'product_id', 'product_id');
	}

	public function productImages()
	{
		return $this->hasMany(ProductImage::class, 'product_id', 'product_id');
	}

	public function getSoldQuantityAttribute() {
		return $this->order_items->sum('quantity');
	}

	public function getClaimsDurationDays()
	{
		$categories = $this->categories()->pluck('name')->toArray();
		foreach ($categories as $category) {
			$firstLetter = explode(" ", $category)[0];
			return strtolower($firstLetter) === "cây"
				? config('constants.refund_return_policy.duration.plants')
				: config('constants.refund_return_policy.duration.others');
		}
	}
}
