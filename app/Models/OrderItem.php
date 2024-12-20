<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // Add this

/**
 * Class OrderItem
 * 
 * @property int $order_items_id
 * @property int|null $order_id
 * @property string|null $product_id
 * @property int|null $quantity
 * @property int|null $total_price
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Order|null $order
 * @property Product|null $product
 * @property Collection|ReturnRefundItem[] $return_refund_items
 *
 * @package App\Models
 */
class OrderItem extends Model
{
	use HasFactory;

	protected $table = 'order_items';
	protected $primaryKey = 'order_items_id';

	protected $casts = [
		'order_id' => 'int',
		'quantity' => 'int',
		'total_price' => 'int',
		'discounted_amount' => 'int'
	];

	protected $fillable = [
		'order_id',
		'product_id',
		'quantity',
		'total_price',
		'discounted_amount'
	];

	public function order()
	{
		return $this->belongsTo(Order::class, 'order_id', 'order_id');
	}

	public function product()
	{
		return $this->belongsTo(Product::class, 'product_id', 'product_id');
	}

	public function return_refund_items()
	{
		return $this->hasMany(ReturnRefundItem::class, 'order_items_id');
	}
}
