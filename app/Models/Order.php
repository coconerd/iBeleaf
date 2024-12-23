<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Order
 *
 * @property int $order_id
 * @property int|null $user_id
 * @property int|null $voucher_id
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
	use HasFactory;

	protected $table = 'orders';
	protected $primaryKey = 'order_id';

	protected $casts = [
		'user_id' => 'int',
		'voucher_id' => 'int',
		'provisional_price' => 'int',
		'deliver_cost' => 'int',
		'total_price' => 'int',
		'payment_date' => 'datetime',
		'deliver_time' => 'datetime',
		'is_paid' => 'int',
		'status' => 'string',
		'deliver_address' => 'string'
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
		'status',
		'additional_note',
		'deliver_time',
		'deliver_address'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id', 'user_id');
	}

	public function voucher()
	{
		return $this->belongsTo(Voucher::class , 'voucher_id', 'voucher_id');
	}

	public function order_items(): HasMany
	{
		return $this->hasMany(OrderItem::class, 'order_id', 'order_id');
	}
}
