<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\OrderService;

class OrderServiceProvider extends ServiceProvider
{
	/**
	 * Register services.
	 */
	public function register(): void
	{
		if (!class_exists(OrderService::class)) {
			throw new \Exception('OrderService class not found!');
		}

		$this->app->bind(OrderService::class, function ($app) {
			return new OrderService();
		});
	}

	/**
	 * Bootstrap services.
	 */
	public function boot(): void
	{
		//
	}
}
