<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ProductFeedback
 * 
 * @property int $product_feedback_id
 * @property string|null $product_id
 * @property int|null $user_id
 * @property string|null $feedback_content
 * @property int $num_star
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Product|null $product
 * @property User|null $user
 * @property Collection|FeedbackImage[] $feedback_images
 *
 * @package App\Models
 */
class ProductFeedback extends Model
{
	protected $table = 'product_feedbacks';
	protected $primaryKey = 'product_feedback_id';

	protected $casts = [
		'user_id' => 'int',
		'num_star' => 'int'
	];

	protected $fillable = [
		'product_id',
		'user_id',
		'feedback_content',
		'num_star'
	];

	public function product()
	{
		return $this->belongsTo(Product::class, 'product_id', 'product_id');
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id', 'user_id');
	}

	public function feedback_images()
	{
		return $this->hasMany(FeedbackImage::class, 'product_feedback_id', 'product_feedback_id');
	}
}
