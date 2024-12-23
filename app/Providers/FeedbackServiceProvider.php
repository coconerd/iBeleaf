<?php

namespace App\Providers;

use App\Services\FeedbackService;
use Illuminate\Support\ServiceProvider;

class FeedbackServiceProvider extends ServiceProvider
{
	/**
	 * Register services.
	 */
	public function register(): void
	{
		if (!class_exists(FeedbackService::class)) {
			throw new \Exception('FeedbackService class not found!');
		}

		$this->app->bind(FeedbackService::class, function ($app) {
			return new FeedbackService();
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