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

	public function submitFeedback(Request $request)
	{
		$feedbacks = $request->input('feedbacks');
		$user = Auth::user();
		foreach ($feedbacks as $product_id => $feedback) {
			try {
				$this->feedbackService->store($request->input('feedbacks'), $user->user_id);
			} catch (\Exception $e) {
				return redirect()->back()->with('error', 'Có lỗi xảy ra khi gửi đánh giá!');
			}
		}
		return redirect()->back()->with('success', 'Cảm ơn bạn đã gửi đánh giá!');
	}

	public function store(Request $request)
	{
		Log::info('User ' . Auth::id() . ' submitted review for product ' . $request->product_id . ' with rating ' . $request->rating . ' and review ' . $request->review);
		$request->validate([
			'rating' => 'required|integer|min:1|max:5',
			'review' => 'required|string|min:10',
			'product_id' => 'required|exists:products,product_id'
		]);
		Log::debug('Request validated');

		try {
			$feedback = new ProductFeedback();
			$feedback->product_id = $request->product_id;
			$feedback->user_id = Auth::id();
			$feedback->feedback_content = $request->review;
			$feedback->num_star = $request->rating;

			// Debug logging
			Log::info('Saving feedback:', [
				'product_id' => $feedback->product_id,
				'user_id' => $feedback->user_id,
				'content' => $feedback->feedback_content,
				'rating' => $feedback->num_star
			]);

			if (!$feedback->save()) {
				Log::error('Failed to save feedback');
				return response()->json(['error' => 'Failed to save feedback'], 422);
			}

			return response()->json(['message' => 'Review saved successfully']);

		} catch (\Exception $e) {
			Log::error('Error saving feedback: ' . $e->getMessage());
			return response()->json(['error' => $e->getMessage()], 422);
		}
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