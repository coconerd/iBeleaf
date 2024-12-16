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
		$deliverCost = 50000; // fixed, for now
		$totalPrice = 0; // Will be calculated after creating OrderItems

		$prepStatus = $this->faker->randomElement([
			'pending',
			'delivering',
			'delivered',
			'cancelled',
			'returned',
			'refunded',
		]);
		if ($prepStatus === 'delivered' || $prepStatus === 'completed') {
			$prepDeliverTime = $this->faker->dateTimeBetween('-1 month', 'now');
		} elseif ($prepStatus === 'delivering') {
			$prepDeliverTime = $this->faker->dateTimeBetween('+1 day', '+1 week');
		} else {
			$prepDeliverTime = null;
		}

		$isPaidPrep = $prepStatus == 'completed'
			? true
			: $this->faker->boolean(35);

		// One out of 4 products will have a voucher id, voucher id is randomly selected
		$prepVoucherId = $this->faker->boolean(25)
			? Voucher::query()->inRandomOrder()->value('voucher_id')
			: null;

		$prepUserId = User::query()->exists()
			? User::query()->orderBy('user_id')->first()->value('user_id')
			: User::factory();

		return [
			'user_id' => $prepUserId,
			'voucher_id' => $prepVoucherId,
			'provisional_price' => $provisionalPrice,
			'deliver_cost' => $deliverCost,
			'total_price' => $totalPrice,
			'payment_date' => $isPaidPrep ? $this->faker->dateTimeBetween('-1 month', 'now') : null,
			'deliver_time' => $prepDeliverTime,
			'payment_method' => $this->faker->randomElement(['COD', 'Banking']),
			'is_paid' => $isPaidPrep,
			'status' => $prepStatus,
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