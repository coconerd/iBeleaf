<?php

namespace App\Http\Controllers;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

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
}