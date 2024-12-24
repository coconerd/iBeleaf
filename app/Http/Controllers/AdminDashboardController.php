<?php

namespace App\Http\Controllers;
use App\Models\Order;
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
			->whereBetween('created_at', [Carbon::now()->subDays(30)->startOfDay(), Carbon::now()->endOfDay()])
			->groupBy('date')
			->orderBy('date')
			->get();

		// Prepare data arrays
		$labels = [];
		$counts = [];
		
		// Fill missing dates
		for ($i = 30; $i >= 0; $i--) {
			$date = Carbon::now()->subDays($i)->format('Y-m-d');
			$count = 0;
			
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
}