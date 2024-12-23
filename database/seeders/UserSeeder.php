<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
	public function run()
	{
		// create a user admin
		$hasAdminUser = User::where('role_type', 1)->exists();

		if (!$hasAdminUser) {
			User::create([
				'full_name' => 'Nguyễn Văn Admin',
				'email' => 'admin@example.com',
				'user_name' => 'admin1',
				'password' => bcrypt('password'),
				'role_type' => 1,
			]);
		}
	}
}