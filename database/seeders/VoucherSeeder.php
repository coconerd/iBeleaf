<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Voucher;

class VoucherSeeder extends Seeder
{
    public function run()
    {
        // ...existing code...
        Voucher::create([
            'voucher_name' => 'WELCOME10',
            'voucher_type' => 'percentage',
            'description' => 'Giảm ngay 10% cho lần mua sắm đầu tiên',
            'voucher_start_date' => now()->subDays(50),
            'voucher_end_date' => now()->addDays(60),
            'value' => 10,
        ]);

        Voucher::create([
            'voucher_name' => 'SAVE50K',
            'voucher_type' => 'cash',
            'description' => 'Giảm 50.000 ₫ với đơn hàng trên 500.000 ₫',
            'voucher_start_date' => now(),
            'voucher_end_date' => now()->addDays(15),
            'value' => 50000,
        ]);

        Voucher::create([
            'voucher_name' => 'TETDL2025',
            'voucher_type' => 'percentage',
            'description' => 'Tết Dương Lịch 2025 - Giảm sốc 25% cho toàn bộ đơn hàng',
            'voucher_start_date' => now()->addDays(5),
            'voucher_end_date' => now()->addDays(55),
            'value' => 25,
        ]);

        Voucher::create([
            'voucher_name' => 'NOEN24',
            'voucher_type' => 'percentage',
            'description' => 'Noel giảm giá 24% cho mọi đơn hàng!',
            'voucher_start_date' => now()->addDays(5),
            'voucher_end_date' => now()->addDays(35),
            'value' => 24,
        ]);

        Voucher::create([
            'voucher_name' => 'FREESHIP300',
            'voucher_type' => 'free_shipping',
            'description' => 'Miễn phí vận chuyển cho đơn hàng từ 300.000 ₫',
            'voucher_start_date' => now()->addDays(1),
            'voucher_end_date' => now()->addDays(45),
            'value' => 0
        ]);

        Voucher::create([
            'voucher_name' => 'BOGO2025',
            'voucher_type' => 'BOGO',
            'description' => 'Mua 1 tặng 1 mừng xuân Ất Tỵ',
            'voucher_start_date' => now(),
            'voucher_end_date' => now()->addDays(30),
            'value' => 0,
        ]);
    }
}