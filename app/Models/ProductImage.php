<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ProductImage
 * 
 * @property int $product_image_id
 * @property string|null $product_id
 * @property string|null $product_image_name
 * @property string|null $product_image
 * @property string|null $product_image_url
 * @property int|null $image_type
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class ProductImage extends Model
{
	protected $table = 'product_images';
	protected $primaryKey = 'product_image_id';
	public $incrementing = false;
	protected $casts = [
		'product_image_id' => 'int',
		'image_type' => 'int'
	];

	protected $fillable = [
		'product_id',
		'product_image_name',
		'product_image',
		'product_image_url',
		'image_type'
	];

	public function product()
	{
		return $this->belongsTo(Product::class, 'product_id', 'product_id');
	}
}
