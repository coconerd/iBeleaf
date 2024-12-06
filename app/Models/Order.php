<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Order
 * 
 * @property int $order_id
 * @property int|null $user_id
 * @property string|null $voucher_id
 * @property int|null $provisional_price
 * @property int|null $deliver_cost
 * @property int|null $total_price
 * @property Carbon|null $payment_date
 * @property string|null $payment_method
 * @property int|null $is_paid
 * @property int|null $is_delivered
 * @property string|null $additional_note
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property User|null $user
 * @property Voucher|null $voucher
 * @property Collection|OrderItem[] $order_items
 *
 * @package App\Models
 */
class Order extends Model
{
	protected $table = 'orders';
	protected $primaryKey = 'order_id';
	public $incrementing = false;

	protected $casts = [
		'order_id' => 'int',
		'user_id' => 'int',
		'provisional_price' => 'int',
		'deliver_cost' => 'int',
		'total_price' => 'int',
		'payment_date' => 'datetime',
		'is_paid' => 'int',
		'is_delivered' => 'int'
	];

	protected $fillable = [
		'user_id',
		'voucher_id',
		'provisional_price',
		'deliver_cost',
		'total_price',
		'payment_date',
		'payment_method',
		'is_paid',
		'is_delivered',
		'additional_note'
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function voucher()
	{
		return $this->belongsTo(Voucher::class);
	}

	public function order_items()
	{
		return $this->hasMany(OrderItem::class);
	}
}
