<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FeedbackService;
use App\Models\ProductFeedback;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FeedbackController extends Controller
{
	protected FeedbackService $feedbackService;

	public function __construct(FeedbackService $feedbackService)
	{
		$this->feedbackService = $feedbackService;
	}


	public function index($product_id)
	{
		$feedbacks = ProductFeedback::with(
			[
				'user' => function ($query) {
					$query->select('user_id', 'full_name');
				},
				'feedback_images' => function ($query) {
					$query->select('product_feedback_id', 'feedback_image');
				}
			]
		)
		->where('product_id', $product_id)
		->orderBy('created_at', 'desc')
		->get()
		->map(function ($feedback) {
			$feedback->feedback_images->map(function ($image) {
				$image->feedback_image = base64_encode($image->feedback_image);
				return $image;
			});
			return $feedback;
		});

		Log::debug('Feedbacks retrieved: ', $feedbacks->toArray());

		return response()->json($feedbacks);
	}
}