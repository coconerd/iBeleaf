<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductFeedback;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
// use Log;

class ReviewController extends Controller
{
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
		$reviews = ProductFeedback::with(
			'user',
			'user:full_name,user_id'
		)
			->where('product_id', $product_id)
			->orderBy('created_at', 'desc')
			->get();

		return response()->json($reviews);
	}
}