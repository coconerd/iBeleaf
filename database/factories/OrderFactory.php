<?php
namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use App\Models\Voucher;
use App\Models\OrderItem;
use Faker\Factory as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
	protected $model = Order::class;

	public function definition()
	{
		$faker = Faker::create('vi_VN');
		$createdAt = $this->faker->dateTimeBetween('-2 week', 'now');

		return [
			'created_at' => $createdAt,
			'user_id' => fn() => User::query()->exists()
				? User::query()->where('role_type', 0)->inRandomOrder()->first()->user_id
				: User::factory()->create()->user_id,
			'voucher_id' => fn() => $this->faker->boolean(25)
				? Voucher::query()->inRandomOrder()->first()->voucher_id
				: null,
			'provisional_price' => 0,
			'deliver_cost' => 50000,
			'total_price' => 0,
			'payment_date' => fn(array $attributes) => $attributes['is_paid']
				? $this->faker->dateTimeBetween('-1 month', 'now')
				: null,
			'status' => fn() => $this->faker->randomElement([
				'pending',
				'delivering',
				'delivered',
				'cancelled'
			]),
			'deliver_time' => function (array $attributes) use ($createdAt) {
				return isset($attributes['status']) && $attributes['status'] === 'delivered'
					? $this->faker->dateTimeBetween($createdAt, '+1 week')
					: null;
			},
			'payment_method' => fn() => $this->faker->randomElement(['COD', 'Banking']),
			'is_paid' => fn(array $attributes) => in_array($attributes['status'], ['delivering', 'delivered'])
				? true
				: $this->faker->boolean(35),
			'additional_note' => fn() => $this->faker->optional()->sentence(),
			'deliver_address' => $this->faker->address(),
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

			// Update total_orders of Products
			foreach($orderItems as $orderItem) {
				$orderItem->product->total_orders += $orderItem->quantity;
				$orderItem->product->save();
			}

			// Calculate provisional_price by summing total_price of OrderItems
			$provisionalPrice = $orderItems->sum('total_price');

			// Update the Order with the calculated prices
			$order->provisional_price = $provisionalPrice;
			$order->total_price = $provisionalPrice + $order->deliver_cost;

			// Apply voucher/coupon if available
			if (!empty($order->voucher)) {
				switch ($order->voucher->voucher_type) {
					case 'cash':
						$order->total_price -= $order->voucher->value;
						break;
					default:
						$order->total_price *= 1 - $order->voucher->value / 100;
						break;
				}
			}
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
