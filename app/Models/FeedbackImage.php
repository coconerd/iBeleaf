<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class FeedbackImage
 * 
 * @property int $feedback_image_id
 * @property int|null $product_feedback_id
 * @property string|null $feedback_image
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property ProductFeedback|null $product_feedback
 *
 * @package App\Models
 */
class FeedbackImage extends Model
{
	protected $table = 'feedback_images';
	protected $primaryKey = 'feedback_image_id';
	public $incrementing = false;

	protected $casts = [
		'feedback_image_id' => 'int',
		'product_feedback_id' => 'int'
	];

	protected $fillable = [
		'product_feedback_id',
		'feedback_image'
	];

	public function product_feedback()
	{
		return $this->belongsTo(ProductFeedback::class);
	}
}
