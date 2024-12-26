<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class VoucherController extends Controller
{
	private const ERROR_CODES = [
        'INVALID' => 'Voucher không hợp lệ!',
        'EXPIRED' => 'Voucher đã hết hạn sử dụng!',
        'FIRST_ORDER' => 'Voucher chỉ áp dụng cho khách hàng mới!',
		'MIN_PRICE' => '', // Hanlde it in JS
		'NOT_FOUND' => 'Voucher không tồn tại!'
    ];

	private function formatResponse($valid, $data = [])
    {
        return response()->json(array_merge(['valid' => $valid], $data));
    }

	public function validateVoucher(Request $request){
		$code = $request->input('voucher_name');
		$cartTotal = $request->input('cart_total');
		$userId = Auth::user()->user_id;

		$voucher = Voucher::with('voucherRules')
			->where('voucher_name', $code)
			->where('voucher_start_date', '<=', Carbon::now())
			->where('voucher_end_date', '>=', Carbon::now())
			->first();

		Log::info('Voucher validation:', [
			'code' => $code,
			'found' => (bool)$voucher
		]);

		if (!$voucher || $voucher->voucher_end_date < Carbon::now()) {
            return $this->formatResponse(false, [
                'ecode' => !$voucher ? 'NOT_FOUND' : 'EXPIRED',
                'message' => !$voucher ? self::ERROR_CODES['NOT_FOUND'] : self::ERROR_CODES['EXPIRED']
            ]);
        }

		// Validate voucher rules
		foreach ($voucher->voucherRules as $rule) {
			$ruleController = new VoucherRuleController($rule->rule_type, $rule->rule_value);
			$validationRule = $ruleController->validateRule($userId, $cartTotal);

			if (!$validationRule['is_valid']) {
				return $this->formatResponse(false, [
					'ecode' => $validationRule['rule_type'],
					'voucher_type' => $voucher->voucher_type,
					'message' => self::ERROR_CODES[$validationRule['rule_type']],
					'cart_total' => $cartTotal,
					'min_price' => $validationRule['min_price'] ?? 0,
					'order_count' => $validationRule['order_count'] ?? 0
				]);
			}
		}

		return $this->formatResponse(true, [
            'voucher_name' => $voucher->voucher_name,
            'voucher_type' => $voucher->voucher_type,
            'voucher_value' => $voucher->value,
            'voucher_description' => $voucher->description,
            'cart_total' => $cartTotal,
        ]);
	}
}