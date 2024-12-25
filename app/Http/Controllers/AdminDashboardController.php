<?php

namespace App\Http\Controllers;
use App\Models\Order;
use App\Models\User;
use App\Models\OrderItem;
use App\Models\ProductImage;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
	public function showDashboardPage()
	{
		return view('admin.dashboard.index');
	}

	public function getSalesData()
	{
		// Get last 14 days of data
		$salesData = Order::selectRaw('DATE(created_at) as date, status, COUNT(*) as count')
			->whereBetween('created_at', [Carbon::now()->subDays(13)->startOfDay(), Carbon::now()->endOfDay()])
			->groupBy('date', 'status')
			->orderBy('date')
			->get();

		$labels = [];
		$data = [
			'pending' => [],
			'delivering' => [],
			'delivered' => [],
			'cancelled' => []
		];

		// Fill missing dates for each status
		for ($i = 13; $i >= 0; $i--) {
			$date = Carbon::now()->subDays($i)->format('Y-m-d');
			$labels[] = Carbon::parse($date)->format('M d');

			foreach ($data as $status => &$counts) {
				$dayData = $salesData->first(function($item) use ($date, $status) {
					return $item->date === $date && $item->status === $status;
				});
				$counts[] = $dayData ? $dayData->count : 0;
			}
		}

		return response()->json([
			'labels' => $labels,
			'pending' => $data['pending'],
			'delivering' => $data['delivering'],
			'delivered' => $data['delivered'],
			'cancelled' => $data['cancelled']
		]);
	}

	public function analyzeMetric($metric = 'orders')
	{
		$metrics = [
			'orders' => ['model' => Order::class, 'column' => 'order_id', 'method' => 'count'],
			'sales' => ['model' => Order::class, 'column' => 'total_price', 'method' => 'sum'],
			'customers' => ['model' => User::class, 'column' => 'user_id', 'method' => 'count']
		];

		if (!isset($metrics[$metric])) {
			return response()->json(['error' => 'Invalid metric'], 400);
		}

		$config = $metrics[$metric];
		$model = $config['model'];
		
		$todayValue = $model::whereDate('created_at', Carbon::today())
			->{$config['method']}($config['column']);
		
		$yesterdayValue = $model::whereDate('created_at', Carbon::yesterday())
			->{$config['method']}($config['column']);

		$growthPercent = $yesterdayValue > 0
			? (($todayValue - $yesterdayValue) / $yesterdayValue) * 100
			: 0;

		$formattedGrowth = number_format($growthPercent, 2);
		$trend = $growthPercent >= 0 ? 'increase' : 'decrease';

		return response()->json([
			'todayTotal' => $todayValue,
			'yesterdayTotal' => $yesterdayValue,
			'growth' => $formattedGrowth,
			'trend' => $trend
		]);
	}

	public function topSellingProducts(){
		$twoWeeksAgo = Carbon::now()->subWeeks(2);
		
		// Get top selling products
		$topSellingProducts = OrderItem::join('products', 'order_items.product_id', '=', 'products.product_id')
			->where('order_items.created_at', '>=', $twoWeeksAgo)
			->select(
				'products.product_id',
				'products.name',
				'products.overall_stars',
				'products.total_orders'
			)
			->groupBy('products.product_id', 'products.name', 'products.overall_stars', 'products.total_orders')
			->orderByDesc('products.total_orders')
			->limit(15)
			->get();

		$productIds = $topSellingProducts->pluck('product_id')->toArray();
		$productImages = ProductImage::whereIn('product_id', $productIds)
			->where('image_type', 1)
			->get();

		// Combine results
		$result = $topSellingProducts->map(function($product, $index) use ($productImages) {
			$productImage = $productImages->firstWhere('product_id', $product->product_id);
			return [
				'rank' => $index + 1,
				'name' => $product->name,
				'id' => $product->product_id,
				'quantity' => $product->total_orders,
				'image' => $productImage?->product_image_url,
				'rating' => $product->overall_stars
			];
		});

		return response()->json($result);
	}
}