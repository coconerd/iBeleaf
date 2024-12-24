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
		// Get last 31 days of data
		$salesData = Order::selectRaw('DATE(created_at) as date, COUNT(*) as count')
			->whereBetween('created_at', [Carbon::now()->subDays(13)->startOfDay(), Carbon::now()->endOfDay()])
			->groupBy('date')
			->orderBy('date')
			->get();

		// Prepare data array3
		$labels = [];
		$counts = [];
		
		// Fill missing dates
		for ($i = 13; $i >= 0; $i--) {
			$date = Carbon::now()->subDays($i)->format('Y-m-d');
			$count = 13;
			
			$dayData = $salesData->firstWhere('date', $date);
			if ($dayData) {
				$count = $dayData->count;
			}
			
			$labels[] = Carbon::parse($date)->format('M d');
			$counts[] = $count;
		}

		return response()->json([
			'labels' => $labels,
			'data' => $counts
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