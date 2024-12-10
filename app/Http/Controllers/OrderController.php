<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OrderService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
	protected OrderService $orderService;

	public function __construct(OrderService $orderService)
	{
		$this->orderService = $orderService;
	}

	public function index(Request $request)
	{
		$status = $request->input('status');
		Log::debug('OrderController@index: status: ' . $status);
		if (empty($status)) {
			Log::debug('OrderController@index: status is empty');
		}
		$user = Auth::user();
		Log::debug('OrderController@index: user: ' . $user->user_id);
		$orders = $this->orderService->getUserOrders(
			$user->user_id,
			!empty($status) ? ['status' => $status] : []
		);
		return response()->json([
			'success' => true,
			'html' => view('profile.ordersTab', compact('orders'))->render()
		]);
	}
}
