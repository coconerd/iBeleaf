<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
		// Create mock orders
        $this->call([
			VoucherSeeder::class,
            OrderSeeder::class,
            ProductSeeder::class,
            VoucherRuleSeeder::class,
            ReturnRefundItemSeeder::class,
			ProductFeedbackSeeder::class,
			UserSeeder::class,
        ]);
    }
}
