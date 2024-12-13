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

	protected $casts = [
		'product_feedback_id' => 'int',
	];

	protected $fillable = [
		'product_feedback_id',
		'feedback_image', // Make sure this is included
	];

	public function product_feedback()
	{
		return $this->belongsTo(related: ProductFeedback::class);
	}
}
