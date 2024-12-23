<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Category
 * 
 * @property string $category_id
 * @property string $name
 * @property string|null $background_url
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|ProductCategory[] $product_categories
 *
 * @package App\Models
 */
class Category extends Model
{
	protected $table = 'categories';
	protected $primaryKey = 'category_id';
	public $incrementing = false;

	protected $fillable = [
		'name',
		'background_url',
		'description'
	];

	public function product_categories()
	{
		return $this->hasMany(ProductCategory::class);
	}
}
