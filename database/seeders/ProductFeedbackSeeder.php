<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductFeedback;
use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ProductFeedbackSeeder extends Seeder
{
	public function run(): void
	{
		$this->seedFeedbackFirstNProducts(30);
		$this->seedFeedbackForBestSellingProducts(15);
	}

	protected function seedFeedbackFirstNProducts($productCount)
	{
		// Get all products and users
		$products = Product::orderBy('product_id', 'asc')
			->limit($productCount)
			->get();
		$this->seedFeedbackForProduct($products);
	}

	protected function seedFeedbackForBestSellingProducts($productCount)
	{
		// Get top N best selling products
		$products = Product::orderBy('total_orders', 'desc')
			->limit($productCount)
			->get();
		$this->seedFeedbackForProduct($products);
	}

	protected function seedFeedbackForProduct($products)
	{
		$faker = Faker::create('vi_VN');
		$users = User::where('role_type', 0)->get();
		if ($users->isEmpty()) {
			// Create some test users if none exist
			for ($i = 0; $i < 5; $i++) {
				$users->push(User::create([
					'full_name' => $faker->name,
					'email' => $faker->email,
					'user_name' => $faker->userName,
					'password' => bcrypt('password'),
					'role_type' => 0,
				]));
			}
		}
		foreach ($products as $product) {
			// Generate 1-5 random feedback per product
			$feedbackCount = rand(1, 5);

			for ($i = 0; $i < $feedbackCount; $i++) {
				$stars = rand(3, 5); // Bias towards positive ratings

				$feedback = ProductFeedback::create([
					'product_id' => $product->product_id,
					'user_id' => $users->random()->user_id,
					'feedback_content' => $faker->realText(200),
					'num_star' => $stars,
				]);

				// 50% chance to add feedback images
				if (rand(0, 1)) {
					$imageCount = rand(1, 2);
					for ($j = 0; $j < $imageCount; $j++) {
						$feedback->feedback_images()->create([
							'feedback_image' => file_get_contents(public_path('images/mock/feedback_' . rand(1, 5) . '.jpg'))
						]);
					}
				}
			}

			// Update product rating statistics
			$feedbacks = $product->product_feedbacks;
			$product->update([
				'total_ratings' => $feedbacks->count(),
				'overall_stars' => $feedbacks->avg('num_star')
			]);
		}
	}
}
