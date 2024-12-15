<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OrderService;
use App\Services\FeedbackService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Order;

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
			$status = explode(',', $request->query(key: 'status'));
			Log::debug('OrderController@index: status is not empty: ' . json_encode($status));
		} else {
			Log::debug('OrderController@index: status is empty');
		}
		$user = Auth::user();
		$orders = $this->orderService->getUserOrders(
			$user->user_id,
			!empty($status) ? ['status' => $status] : []
		);

		Log::debug('Retrieved orders: ' ,  ($orders->toArray()));
		return response()->json([
			'success' => true,
			'html' => view('profile.ordersTab', compact('orders'))->render()
		]);
	}

	public function submitFeedback(Request $request)
	{
		$request->validate(
			[
				'feedbacks.*.product_id' => 'required|exists:products,product_id',
				'feedbacks.*.feedback_content' => 'required|string|max:255',
				'feedbacks.*.num_star' => 'required|integer|min:1|max:5',
				'feedbacks.*.images.*' => 'nullable|image|max:2048',
			],
			[
				'feedbacks.*.product_id.required' => 'Mã sảp phẩm không được để trống.',
				'feedbacks.*.product_id.exists' => 'Mã sản phẩm không tồn tại.',
				'feedbacks.*.feedback_content.required' => 'Noi dung phản hồi không được để trống.',
				'feedbacks.*.feedback_content.string' => 'Nội dung phản hồi phải là một chuỗi.',
				'feedbacks.*.feedback_content.max' => 'Nội dung phản hồi không được vượt quá 255 ký tự.',
				'feedbacks.*.num_star.required' => 'Sô sao không được để trống.',
				'feedbacks.*.num_star.integer' => 'Số sao phải là một số nguyên.',
				'feedbacks.*.num_star.min' => 'Số sao không được nhỏ hơn 1.',
				'feedbacks.*.num_star.max' => 'Số sao không được lớn hơn 5.',
				'feedbacks.*.images.*.image' => 'Hình ảnh phải là một tệp hình ảnh.',
				'feedbacks.*.images.*.max' => 'Hình ảnh không được vượt quá 2MB.',
			]
		);

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

	public function cancel($orderId) 
	{
		try {
			Log::debug('orderId is ', [$orderId]);
			$order = Order::findOrFail($orderId);
			
			// Check if order belongs to authenticated user
			if ($order->user_id !== Auth::id()) {
				return response()->json([
					'success' => false,
					'message' => 'Unauthorized access'
				], 403);
			}

			// Check if order can be cancelled (only pending orders)
			if ($order->status !== 'pending') {
				return response()->json([
					'success' => false,
					'message' => 'Chỉ có thể hủy đơn hàng đang chờ xử lý'
				], 400);
			}

			$order->status = 'cancelled';
			$order->save();

			return response()->json([
				'success' => true,
				'message' => 'Đã hủy đơn hàng thành công'
			]);
		} catch (\Exception $e) {
			Log::error('OrderControlle@cancel: ' . $e->getMessage());
			return response()->json([
				'success' => false,
				'message' => 'Có lỗi xảy ra khi hủy đơn hàng'
			], 500);
		}
	}
}
