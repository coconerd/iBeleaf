<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Voucher;
use App\Models\VoucherRule;

class VoucherRuleSeeder extends Seeder
{
    public function run()
    {
        $voucherRules = [
            'SAVE50K' => [
                'rule_type' => 'min_price',
                'rule_value' => '500000'
            ],
            'FREESHIP300' => [
                'rule_type' => 'min_price',
                'rule_value' => '300000'
            ],
            'WELCOME10' => [
                'rule_type' => 'first_order',
                'rule_value' => '0'
            ]
        ];

        foreach ($voucherRules as $voucherName => $rule) {
            if ($voucher = Voucher::where('voucher_name', $voucherName)->first()) {
                VoucherRule::create([
                    'rule_type' => $rule['rule_type'],
                    'rule_value' => $rule['rule_value'],
                    'voucher_id' => $voucher->voucher_id
                ]);
            }
        }
    }
}