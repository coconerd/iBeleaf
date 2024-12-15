<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ValidateVoucherRequest;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Carbon\Carbon;

class VoucherController extends Controller
{
	public function validateVoucher(Request $request){
		$code = $request->input('code');

		$voucher = Voucher::where('voucher_name', $code)
			->where('voucher_start_date', '<=', Carbon::now()) // Checks if current time is AFTER or EQUAL TO start date
            ->where('voucher_end_date', '>=', Carbon::now())
            ->first();

		if(!$voucher){
			return response()->json([
				'valid' => false,
				'message' => 'Invalid voucher code!'
			]);
		}

		return response()->json([
			'valid' => true,
			'code' => $voucher->voucher_name,
			'type' => $voucher->voucher_type,
			'value' => $voucher->value,
			'message' => 'Voucher code is valid!',
			'description' => $voucher->description
		]);
	}
}