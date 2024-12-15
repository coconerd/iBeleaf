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
            'description' => 'Nhận được ưu đãi 10% cho đơn hàng đầu tiên',
            'voucher_start_date' => now()->subDays(50),
            'voucher_end_date' => now()->addDays(60),
            'value' => 10,
        ]);

        Voucher::create([
            'voucher_name' => 'SAVE50K',
            'voucher_type' => 'cash',
            'description' => 'Giảm 50,000 VND với đơn trên 500,000 VND',
            'voucher_start_date' => now(),
            'voucher_end_date' => now()->addDays(15),
            'value' => 50000,
        ]);

        Voucher::create([
            'voucher_name' => 'TETDL2025',
            'voucher_type' => 'percentage',
            'description' => 'Tết Dương Lịch 2025 - Giảm giá 25% cho tất cả sản phẩm',
            'voucher_start_date' => now()->addDays(5),
            'voucher_end_date' => now()->addDays(55),
            'value' => 25,
        ]);
        Voucher::create([
            'voucher_name' => 'NOEN24',
            'voucher_type' => 'percentage',
            'description' => 'Mùa đông noel 2024 - sale 24% tất cả sản phẩm',
            'voucher_start_date' => now()->addDays(5),
            'voucher_end_date' => now()->addDays(35),
            'value' => 24,
        ]);
    }
}