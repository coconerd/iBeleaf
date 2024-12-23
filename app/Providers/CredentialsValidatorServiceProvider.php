<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\CredentialsValidatorService;
use App\Providers\DBConnService;

class CredentialsValidatorServiceProvider extends ServiceProvider
{
	/**
	 * Register services.
	 */
	public function register(): void
	{
		if (!class_exists(CredentialsValidatorService::class)) {
			throw new \Exception('OrderService class not found!');
		}
		$this->app->bind(CredentialsValidatorService::class, function ($app) {
			return new CredentialsValidatorService($app->make(DBConnService::class));
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
