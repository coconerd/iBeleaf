<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use mysqli;

class DBConnService
{
	// singleton instance of this DBConnService class itself
	private static $instance;

	// DB Connection object
	private $conn;

	public static function initSingleton(): bool
	{
		if (!empty(self::$instance)) {
			return false;
		}

		self::$instance = new DBConnService();
		return true;
	}

	public static function getInstance() {
		return self::$instance;
	}

	public function __construct()
	{
		$host = config(key: 'database.connections.mysql.host');
		$port = config('database.connections.mysql.port');
		$username = config('database.connections.mysql.username');
		$password = config('database.connections.mysql.password');
		$database = config('database.connections.mysql.database');

		$this->conn = new mysqli(
			$host,
			$username,
			$password,
			$database,
			$port
		);

		if ($this->conn->connect_error) {
			die("DB Connection failed due to error: " . $this->conn->connect_error);
		}
	}

	// instance-level func.
	public function getDBConn(): mysqli
	{
		return $this->conn;
	}
}

class DBConnServiceProvider extends ServiceProvider
{
	/**
	 * Register services.
	 */
	public function register(): void
	{
		//
		if (!class_exists(DBConnService::class)) {
			throw new \Exception('DBConnService class not found!');
		}

		$this->app->bind(DBConnService::class, function ($app) {
			return new DBConnService();
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
