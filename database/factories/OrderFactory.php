<?php
namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use App\Models\Voucher;
use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
	protected $model = Order::class;

	public function definition()
	{
		$provisionalPrice = 0; // Will be calculated after creating OrderItems
		$deliverCost = 30000;
		$totalPrice = 0; // Will be calculated after creating OrderItems

		return [
			'user_id' => User::query()->orderBy('user_id')->first()->value('user_id') ?? User::factory(),
			'voucher_id' => null,
			'provisional_price' => $provisionalPrice,
			'deliver_cost' => $deliverCost,
			'total_price' => $totalPrice,
			'payment_date' => $this->faker->dateTimeBetween('-1 month', 'now'),
			'payment_method' => $this->faker->randomElement(['COD', 'Banking']),
			'is_paid' => $this->faker->boolean(),
			'status' => $this->faker->randomElement(['pending', 'delivering', 'delivered']),
			'additional_note' => $this->faker->optional()->sentence()
		];
	}

	public function configure()
	{
		return $this->afterCreating(function (Order $order) {
			// Create 1-3 OrderItems for the Order
			$itemCount = rand(1, 3);
			$orderItems = OrderItem::factory()
				->count($itemCount)
				->forOrder($order)
				->create();

			// Calculate provisional_price by summing total_price of OrderItems
			$provisionalPrice = $orderItems->sum('total_price');

			// Update the Order with the calculated prices
			$deliverCost = 50000;
			$order->provisional_price = $provisionalPrice;
			$order->total_price = $provisionalPrice + $deliverCost;
			$order->save();
		});
	}

	// State modifiers
	public function pending()
	{
		return $this->state(function (array $attributes) {
			return [
				'status' => 'pending',
			];
		});
	}

	public function delivering()
	{
		return $this->state(function (array $attributes) {
			return [
				'status' => 'delivering',
			];
		});
	}

	public function delivered()
	{
		return $this->state(function (array $attributes) {
			return [
				'status' => 'delivered',
				'is_paid' => true
			];
		});
	}
}

// Usage example:
// Order::factory()->count(10)->create(); // Create 10 random orders
// Order::factory()->pending()->create(); // Create pending order
// Order::factory()->delivered()->create(); // Create delivered order