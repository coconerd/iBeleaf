<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ReturnRefundItem
 * 
 * @property int $return_refund_id
 * @property int|null $order_items_id
 * @property int|null $user_id
 * @property string|null $type
 * @property int|null $quantity
 * @property string|null $reason_tag
 * @property string|null $reason_description
 * @property string|null $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property OrderItem|null $order_item
 * @property User|null $user
 * @property Collection|RefundReturnImage[] $refund_return_images
 *
 * @package App\Models
 */
class ReturnRefundItem extends Model
{
	protected $table = 'return_refund_items';
	protected $primaryKey = 'return_refund_id';

	protected $casts = [
		'order_items_id' => 'int',
		'user_id' => 'int',
		'quantity' => 'int',
		'status' => 'string',
		'reject_reason' => 'string',
	];

	protected $fillable = [
		'order_items_id',
		'user_id',
		'type',
		'quantity',
		'reason_tag',
		'reason_description',
		'status', // one of these: 'pending','accepted','rejected','received','refunded','renewed','completed'
		'reject_reason', // Add this line
	];

	// Add helper constants
	const STATUS_PENDING = 'pending';
	const STATUS_ACCEPTED = 'accepted';
	const STATUS_REJECTED = 'rejected';
	const STATUS_RECEIVED = 'received';
	const STATUS_REFUNDED = 'refunded';    // For completed refund requests
	const STATUS_RENEWED = 'renewed';      // For completed return requests

	public function order_item()
	{
		return $this->belongsTo(OrderItem::class, 'order_items_id');
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id', 'user_id');
	}

	public function refund_return_images()
	{
		return $this->hasMany(RefundReturnImage::class, 'return_refund_id');
	}
}
