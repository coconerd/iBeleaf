<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class VoucherRuleController extends Controller {
	private const RULE_FIRST_ORDER = 'first_order';
	private const RULE_MIN_PRICE = 'min_price';
	protected $ruleType;
    protected $ruleValue;

    public function __construct($rule_type, $rule_value)
    {
        $this->ruleType = $rule_type;
        $this->ruleValue = $rule_value;
    }

	public function validateRule($userId, $cartTotal){
		return match($this->ruleType) {
			self::RULE_FIRST_ORDER => $this->validateFirstOrder($userId),
			self::RULE_MIN_PRICE => $this->validateMinPrice($cartTotal),
			default => ['is_valid' => false, 'rule_type' => 'INVALID']
    	};
	}

	private function validateFirstOrder($userId){
		$orderCount = Order::where('user_id', $userId)->count();
		
		Log::info('First Order Check', [
			'user_id' => $userId,
			'order_count' => $orderCount
    	]);

		return [
			'is_valid' => $orderCount === 0,
			'rule_type' => "FIRST_ORDER",
			'order_count' => $orderCount
		];
	}

	private function validateMinPrice($cartTotal){
		return [
			'is_valid' => $cartTotal >= $this->ruleValue,
			'rule_type' => "MIN_PRICE",
			'min_price' => $this->ruleValue
		];
	}
}