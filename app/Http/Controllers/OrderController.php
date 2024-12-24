<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Services\OrderService;
use App\Services\FeedbackService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\RefundReturnImage;
use App\Models\ReturnRefundItem;

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
		$queryParams = $request->query();

		// Convert status to array regardlessly, to handle case of multiple status values
		if (isset($queryParams['status'])) {
			$queryParams['status'] = explode(',', $queryParams['status']);
		}

		Log::debug('OrderController@index: queryParams is: ', $queryParams);

		$user = Auth::user();
		$orders = $this->orderService->getUserOrders(
			$user->user_id,
			!empty($queryParams) ? $queryParams : []
		);

		Log::debug('Retrieved orders: ', $orders->toArray());
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

	public function submitRefundReturn(Request $request)
	{
		$request->validate([
			// 'order_id' => 'required|exists:orders,order_id',
			'request_type' => 'required|in:return,refund',
			'order_id' => 'required|exists:orders,order_id',
			'items' => 'required|array',
			'items.*.order_items_id' => 'required|exists:order_items,order_items_id',
			'items.*.quantity' => 'required|integer|min:1',
			'reason_tag' => 'required|string|max:255',
			'reason_description' => 'required|string',
			'images.*' => 'nullable|image|max:2048',
		], [
			// Custom error messages
		]);

		DB::beginTransaction();

		try {
			// Logic validation
			$user = Auth::user();
			$order = Order::findOrFail($request->input('order_id'));
			$items = $request->input('items');
			$products = Product::whereIn(
				'product_id',
				OrderItem::whereIn('order_items_id', array_column($items, 'order_items_id'))
					->pluck('product_id')
			)->get();
			Log::debug('Products for checking: ', ['products' => $products]);
			if ($order->status !== 'delivered') {
				return redirect()->back()->with('error', 'Chỉ có thể yêu cầu đổi trả cho đơn hàng đã giao hàng.');
			}
			foreach ($products as $product) {
				Log::debug("checking claim date for product " . $product->name);
				$claimDaysDuration = $product->getClaimsDurationDays();
				$deliverDate = $order->deliver_time;
				Log::debug('Claim days duration: ', ['claimDaysDuration' => $claimDaysDuration]);
				Log::debug('Deliver date: ', ['deliverDate' => $deliverDate]);

				$isClaimable = date_diff(now(), $deliverDate)->days <= $claimDaysDuration;
				Log::debug('Is claimable: ', ['isClaimable' => $isClaimable]);
				if (!$isClaimable) {
					return redirect()->back()->with('error', 'Đã hết thời hạn đổi trả cho sản phẩm ' . $product->name);
				}
			}

$dbItems = [];
			foreach ($items as $item) {
				$returnRefundItem = ReturnRefundItem::create([
					'order_items_id' => $item['order_items_id'],
					'user_id' => $user->user_id,
					'type' => $request->input('request_type'),
					'quantity' => $item['quantity'],
					'reason_tag' => $request->input('reason_tag'),
					'reason_description' => $request->input('reason_description'),
					'status' => 'pending',
				]);
array_push($dbItems, $returnRefundItem);

				// Handle images
				if ($request->hasFile('images')) {
					foreach ($request->file('images') as $image) {
						$path = $image->store('refund_return_images', 'public');
						RefundReturnImage::create([
							'refund_return_image' => $path,
							'return_refund_id' => $returnRefundItem->return_refund_id,
						]);
					}
				}
			}
			DB::commit();
			
			// Send notification to admins
			$notifySuccess = true;
			try {
				$adminUsers = User::where('role_type', 1)->get();
				foreach ($adminUsers as $admin) {
					foreach ($dbItems as $dbItem)
						$admin->notify(new NewClaimNotification($dbItem));
				}
			} catch (Exception $e) {
				Log::error('AdminOrderController@updateOrderField: ' . $e->getMessage());
				$notifySuccess = false;
			}

			return redirect()->back()->with(
				$notifySuccess ? 'success': 'error',
				$notifySuccess 
					? 'Yêu cầu của bạn đã được gửi thành công.'
					: 'Yêu cầu của bạn đã được gửi thành công, nhưng không thể gửi thông báo cho quản trị viên.'
);
		} catch (Exception $e) {
			DB::rollBack();
			Log::error('OrderController@submitRefundReturn: ' . $e);
			return redirect()->back()->with('error', 'Có lỗi xảy ra khi gửi yêu cầu.');
		}
	}

	public function showDetail($order_id)
	{
		$order = Order::with(['order_items.product.product_images', 'voucher'])
			->findOrFail($order_id);

		$discount_amount = 0;
		if (!empty($order->voucher)) {
			$discount_amount = $order->voucher->voucher_type === 'cash'
				? $order->voucher->value
				: $order->total_price * $order->voucher->value / 100;
		}

		return view('orders.detail', compact('order', 'discount_amount'));
	}

	public function getClaims($order_id)
	{
		try {
			if (empty($order_id)) {
				Log::error('OrderController@getClaimsStatus: Order ID is required');
				return response()->json([
					'success' => false,
					'message' => 'Order ID is required'

				], 400);
			}

			$order = Order::findOrFail($order_id);
			$orderDate = $order->created_at;
			$orderItems = $order->order_items()->get();
			$claims = [];

			foreach ($orderItems as $item) {
				$product = $item->product()->get()->first();
				$claimDurationDays = $product->getClaimsDurationDays();
				$claimDeadline = $orderDate->addDays($claimDurationDays);

				Log::debug('Product: ', ['product' => $product]);
				Log::debug('claimDays: ', ['claimDays' => $claimDurationDays]);

				$isClaimable = now() <= $claimDeadline;
				array_push($claims, [
					'order_items_id' => $item->order_items_id,
					'product_id' => $product->product_id,
					'isClaimable' => $isClaimable,
					'claimDeadline' => $claimDeadline,
					'claimDurationDays' => $claimDurationDays,
					'reason' => $isClaimable ? 'Còn thời hạn đổi trả' : 'Hết thời hạn đổi trả',
				]);
			}

			return response()->json([
				'success' => true,
				'claims' => $claims
			]);
		} catch (Exception $e) {
			Log::error('OrderController@getClaimsStatus: ' . $e->getMessage());
			return response()->json([
				'success' => false,
				'message' => 'Failed to fetch claims status'
			], 500);
		}
	}
}
