<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ValidateVoucherRequest;
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
        'FIRST_USED' => 'Voucher chỉ áp dụng cho đơn hàng đầu tiên!',
        'MIN_PRICE' => 'Voucher chỉ áp dụng cho đơn hàng từ :value đồng trở lên!'
    ];

	private function formatResponse($valid, $data = [])
    {
        return response()->json(array_merge(['valid' => $valid], $data));
    }

	public function validateVoucher(Request $request){
		$code = $request->input('voucher_name');
		$cartTotal = $request->input('cart_total');
		$userId = Auth::id();

		$voucher = Voucher::with('voucherRules')
			->where('voucher_name', $code)
			->where('voucher_start_date', '<=', Carbon::now())
			->where('voucher_end_date', '>=', Carbon::now())
			->first();

		if (!$voucher || $voucher->voucher_end_date < Carbon::now()) {
            return $this->formatResponse(false, [
                'ecode' => !$voucher ? 'INVALID' : 'EXPIRED',
                'message' => !$voucher ? self::ERROR_CODES['INVALID'] : self::ERROR_CODES['EXPIRED']
            ]);
        }
		
		// Get and validate voucher rules
		foreach ($voucher->voucherRules as $rule) {
			try {
				$validationRule = $rule->validateRule($userId, $cartTotal);
				if (!$validationRule['valid']) {
					return $this->formatResponse(false, [
                        'ecode' => 'INVALID',
                        'voucher_type' => $voucher->voucher_type,
                        'message' => self::ERROR_CODES[$validationRule['rule']],
                        'cart_total' => $cartTotal
                    ]);
				}
			} catch (\Exception $e) {
				Log::error('Invalid voucher code: ' . $e->getMessage());
			}
		}

		return $this->formatResponse(true, [
            'voucher_name' => $voucher->voucher_name,
            'voucher_type' => $voucher->voucher_type,
            'voucher_value' => $voucher->value,
            'message' => 'Voucher code is valid!',
            'voucher_description' => $voucher->description,
            'cart_total' => $cartTotal
        ]);
	}
}