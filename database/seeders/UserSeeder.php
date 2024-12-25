<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
	public function run()
	{
		$this->seedAdminUser();
		$this->seedNormalUsers(5);
	}

	protected function seedAdminUser()
	{
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

	protected function seedNormalUsers($count)
	{
		User::factory()->count($count)->create();
	}
}