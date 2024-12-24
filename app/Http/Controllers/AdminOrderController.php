<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminOrderController extends Controller
{
	public function showOrdersPage(Request $request)
	{
		$query = Order::query();
		$query->with('user:user_id,full_name');

		if ($request->has('dateFilterType')) {
			if (
				$request->input('dateFilterType') == 'single'
				&& $request->has('single_date')
				&& $request->input('single_date') != ''
			) {
				$query->whereDate('created_at', $request->input('single_date'));
			} elseif (
				$request->input('dateFilterType') == 'range'
				&& $request->has('date_start') && $request->input('date_start') != ''
				&& $request->has('date_end') && $request->input('date_end') != ''
			) {
				$query->whereBetween('created_at', [$request->input('date_start'), $request->input('date_end')]);
			}
		}

		if ($request->has('status') && $request->input('status') != '') {
			$query->where('status', $request->input('status'));
		}

		if ($request->has('is_paid') && $request->input('is_paid') != '') {
			$query->where('is_paid', (int) $request->input('is_paid'));
		}

		if ($request->has('payment_method') && $request->input('payment_method') != '') {
			$query->where('payment_method', $request->input('payment_method'));
		}

		// Add sorting logic with default sort
		$sortColumn = $request->input('sort', 'created_at');
		$direction = $request->input('direction', 'desc');

		if (in_array($sortColumn, ['order_id', 'total_price', 'created_at', 'deliver_date'])) {
			$query->orderBy($sortColumn, $direction);
		}

		$orders = $query->orderBy('created_at', 'desc')->paginate(25)->withQueryString();

		// Update newestOrders to include order_items and products
		$newestOrders = Order::where('status', 'pending')
			->where(function ($query) {
				$query->where('payment_method', 'COD')
					->orWhere(function ($query) {
						$query->where('payment_method', 'Banking')
							->where('is_paid', 1);
					});
			})
			->with([
				'user:user_id,full_name',
				'order_items.product.product_images:product_id,product_image_url',
			])
			->orderBy('created_at', 'desc')
			->take(5)
			->get();
		Log::debug('Newest orders: ', ['newestOrders' => $newestOrders]);

		return view('admin.orders.index', compact('orders', 'newestOrders'));
	}

	public function edit($id)
	{
		$order = Order::findOrFail($id);
		return view('admin.orders.edit', compact('order'));
	}

	public function updateOrderField(Request $request): JsonResponse
	{
		$validator = Validator::make($request->all(), [
			'order_id' => 'required|integer|exists:orders,order_id',
			'field' => 'required|string|in:status,is_paid',
			'value' => 'required'
		]);

		if ($validator->fails()) {
			return response()->json(['success' => false, 'message' => 'Invalid data provided.'], 400);
		}

		$order = Order::findOrFail($request->input('order_id'));

		try {
			if ($request->input('field') === 'status') {
				$allowedStatusValues = ['pending', 'delivering', 'delivered', 'cancelled'];
				if (!in_array($request->input('value'), $allowedStatusValues)) {
					return response()->json(['success' => false, 'message' => 'Invalid status value.'], 400);
				}
				$order->status = $request->input('value');
				if ($request->input('value') === 'delivered') {
					$order->deliver_time = now();
				}
			} elseif ($request->input('field') === 'is_paid') {
				$value = $request->input('value');
				if (!in_array($value, ['0', '1'])) {
					return response()->json(['success' => false, 'message' => 'Invalid is_paid value.'], 400);
				}
				$order->is_paid = $value;
			}
			$order->save();
		} catch (\Exception $e) {
			Log::error('AdminOrderController@updateOrderField: ' . $e->getMessage());
			return response()->json(['success' => false, 'message' => 'Có lỗi xảy ra khi cập nhật đơn hàng'], 500);
		}

		$notifySuccess = true;
		try {
			$field = $request->input('field');
			$value = $request->input('value');
			switch ($field) {
				case 'status':
					if ($value == 'delivering')
						$this->notifyUserOrderFieldChanged(
							$order,
							$request->input('field'),
							$request->input('value')
						);
					break;
				default:
					break;
			}
		} catch (\Exception $e) {
			$notifySuccess = false;
			Log::error('AdminOrderController@updateOrderField: ' . $e->getMessage());
		}

		return response()->json([
			'success' => $notifySuccess,
			'message' => $notifySuccess
				? 'Cập nhật đơn hàng thành công'
				: 'Cập nhật đơn hàng thành công, nhưng không thể gửi thông báo cho người dùng.'
		]);
	}

	public function notifyUserOrderFieldChanged($order, $field, $value)
	{
		$order->user->notify(new \App\Notifications\OrderDeliveringNotification($order));
	}

	public function getOrderDetails($order_id): JsonResponse
	{
		$order = Order::with(relations: [
			'user',
			'voucher',
			'order_items.product.product_images',
		])->find($order_id);

		if (!$order) {
			return response()->json(['success' => false, 'message' => 'Order not found.'], 404);
		}

		return response()->json(['success' => true, 'order' => $order]);
	}

	public function getStatistics(Request $request): JsonResponse
	{
		try {
			$period = $request->input('period', 'week');
			$now = Carbon::now();

			switch ($period) {
				case 'month':
					$startDate = $now->copy()->subDays(30);
					$groupBy = 'DATE(created_at)';
					break;
				case 'year':
					$startDate = $now->copy()->subDays(365);
					$groupBy = 'MONTH(created_at)';
					break;
				default: // week
					$startDate = $now->copy()->subDays(7);
					$groupBy = 'DATE(created_at)';
					break;
			}

			// Get sales data
			$salesData = Order::where('created_at', '>=', $startDate)
				->where('status', '!=', 'cancelled')
				->groupBy(DB::raw($groupBy))
				->select(
					DB::raw($groupBy . ' as date'),
					DB::raw('SUM(total_price) as total')
				)
				->get();

			// Get order status distribution
			$statusData = Order::where('created_at', '>=', $startDate)
				->groupBy('status')
				->select('status', DB::raw('COUNT(*) as count'))
				->pluck('count', 'status')
				->toArray();

			// Format sales data for Chart.js
			$labels = [];
			$values = [];
			foreach ($salesData as $data) {
				$labels[] = $period === 'year'
					? Carbon::createFromFormat('m', $data->date)->format('M')
					: Carbon::parse($data->date)->format('d/m');
				$values[] = $data->total;
			}

			// Format status data for pie chart
			$statusCounts = [
				$statusData['pending'] ?? 0,
				$statusData['delivering'] ?? 0,
				$statusData['delivered'] ?? 0,
				$statusData['cancelled'] ?? 0
			];

			return response()->json([
				'salesData' => [
					'labels' => $labels,
					'values' => $values
				],
				'statusData' => $statusCounts
			]);
		} catch (\Exception $e) {
			Log::error('AdminOrderController@getStatistics: ' . $e->getMessage());
			return response()->json(['success' => false, 'message' => 'Có lỗi xảy ra khi lấy thống kê'], 500);
		}
	}
}