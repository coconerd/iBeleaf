<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OrderService;
use App\Services\FeedbackService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
	protected OrderService $orderService;
	protected FeedbackService $feedbackService;

	public function __construct(OrderService $orderService, FeedbackService $feedbackService)
	{
		$this->orderService = $orderService;
		$this->feedbackService = $feedbackService;
	}

	public function index(Request $request)
	{
		if ($request->has('status')) {
			$status = $request->query(key: 'status');
			Log::debug('OrderController@index: status is not empty: ' . $status);
		} else {
			Log::debug('OrderController@index: status is empty');
		}
		$user = Auth::user();
		$orders = $this->orderService->getUserOrders(
			$user->user_id,
			!empty($status) ? ['status' => $status] : []
		);
		return response()->json([
			'success' => true,
			'html' => view('profile.ordersTab', compact('orders'))->render()
		]);
	}

	public function submitFeedback(Request $request)
	{
		$request->validate([
			'feedbacks.*.product_id' => 'required|exists:products,product_id',
			'feedbacks.*.feedback_content' => 'required|string|max:255',
			'feedbacks.*.num_star' => 'required|integer|min:1|max:5',
			'feedbacks.*.images.*' => 'nullable|image|max:2048',
		]);

		Log::debug('OrderController@submitFeedback: Request data: ', $request->all());

		$feedbacks = $request->all('feedbacks')['feedbacks'];
		$user = Auth::user();
		
		DB::beginTransaction();
		try {
			foreach ($feedbacks as $_ => $feedback) {
				$this->feedbackService->store($feedback, $user->user_id);
			}
			DB::commit();
		} catch (\Exception $e) {
			DB::rollBack();
			Log::error('OrderController@submitFeedback: ' . $e->getMessage());
			return redirect()->back()->with('error', 'Có lỗi xảy ra khi gửi đánh giá!');
		}
		return redirect()->back()->with('success', 'Cảm ơn bạn đã gửi đánh giá!');
	}
}
