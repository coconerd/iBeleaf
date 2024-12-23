<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Log;

class OrderService
{
	public function getUserOrders($userId, $filters = [])
	{
		$query = Order::where('orders.user_id', $userId)
			->with([
				'order_items.product' => function ($query) {
					$query->select('product_id', 'code', 'short_description', 'price', 'discount_percentage')
						->with([
							'product_images' => function ($query) {
								$query->where('image_type', 1)
									->select('product_id', 'product_image_url');
							}
						]);
				},
				'voucher' => function ($query) {
					$query->select('voucher_id', 'voucher_name', 'value', 'voucher_type');
				}
			]);

		foreach ($filters as $key => $value) {
			switch ($key) {
				case 'status':
					if (is_array($value)) {
						Log::debug('Status is array: ', $value);
						$query->whereIn($key, $value);
					} else {
						Log::debug('Status is not array: ', $value);
						$query->where($key, $value);
					}
					break;
				case 'payment_method':
				case 'is_paid':
				case 'is_delivered':
					$query->where($key, $value);
					break;
				case 'payment_date':
				case 'created_at':
					$query->whereTime($key, $value);
					break;
				default:
					break;
			}
		}

		$query->orderBy('created_at', 'desc');

		$orders = $query->get();
		// Log::debug('Orders retrieved: ', $orders->toArray());
		return $orders;
	}
}