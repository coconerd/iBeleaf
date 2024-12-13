<?php

namespace App\Services;

use App\Models\ProductFeedback;
use App\Models\FeedbackImage;
use Illuminate\Support\Facades\Log;

class FeedbackService
{
	public function store($feedback, $userId): void
	{
		$productFeedback = ProductFeedback::create(attributes: [
			'product_id' => $feedback['product_id'],
			'user_id' => $userId,
			'feedback_content' => $feedback['feedback_content'],
			'num_star' => $feedback['num_star'],
		]);

		if (isset($feedback['images'])) {
			$images = $feedback['images'];
			foreach ($images as $image) {
				FeedbackImage::create([
					'product_feedback_id' => $productFeedback->product_feedback_id,
					'feedback_image' => file_get_contents($image->getRealPath()),
				]);
			}
		}
	}
}