<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class RefundReturnImage
 * 
 * @property int $refund_return_image_id
 * @property string|null $refund_return_image
 * @property int|null $return_refund_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property ReturnRefundItem|null $return_refund_item
 *
 * @package App\Models
 */
class RefundReturnImage extends Model
{
	protected $table = 'refund_return_images';
	protected $primaryKey = 'refund_return_image_id';

	protected $casts = [
		'return_refund_id' => 'int'
	];

	protected $fillable = [
		'refund_return_image',
		'return_refund_id'
	];

	public function return_refund_item()
	{
		return $this->belongsTo(ReturnRefundItem::class, 'return_refund_id');
	}
}
