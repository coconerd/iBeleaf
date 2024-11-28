<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CartItem
 * 
 * @property int $cart_id
 * @property string $product_id
 * @property int|null $quantity
 * @property int|null $unit_price
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class CartItem extends Model
{
	protected $table = 'cart_items';
	public $incrementing = false;

	protected $casts = [
		'cart_id' => 'int',
		'quantity' => 'int',
		'unit_price' => 'int'
	];

	protected $fillable = [
		'quantity',
		'unit_price'
	];
}
