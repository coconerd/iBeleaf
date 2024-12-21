<?php

namespace App\Services;

use App\Exceptions\ShippingFeeCalculationException;
use App\Models\CartItem;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class ShippingService
{
    private string $apiToken;
	private string $shopId;
    private const ITEM_WEIGHT = 3000;
    private const SERVICE_ID = 53321;
    private const SERVICE_TYPE_ID = 2;
    private const FROM_DISTRICT_ID = 3695;
    private const FROM_WARD_CODE = "90737";

	private const MAX_RETRIES = 3;
	private const BASE_TIMEOUT = 30;
	private const API_ENDPOINT = 'https://dev-online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/fee';

    public function __construct()
    {
        $this->apiToken = env('GHN_API_TOKEN') ?? config('services.ghn.token');
		$this->shopId = env('GHN_SHOP_ID') ?? config('services.ghn.shop_id');

        if (empty($this->apiToken) || empty($this->shopId)) {
            throw new ShippingFeeCalculationException('GHN API token is not configured');
        }
    }

	private function makeApiCall($payload, $attempt = 1)
	{
		try {
			$timeout = self::BASE_TIMEOUT * pow(2, $attempt - 1);
			
			return Http::timeout($timeout)
				->withHeaders([
					'Token' => $this->apiToken,
					'ShopId' => $this->shopId,
					'Content-Type' => 'application/json',
				])
				->post(self::API_ENDPOINT, $payload);
				
		} catch (Exception $e) {
			if ($attempt < self::MAX_RETRIES) {
				Log::warning("API call attempt {$attempt} failed, retrying...", [
					'error' => $e->getMessage()
				]);
				sleep($attempt); // Exponential backoff
				return $this->makeApiCall($payload, $attempt + 1);
			}
			throw $e;
		}
	}
    public function calculateFee($toDistrictId, $toWardCode, $cartId)
    {
        try {
            $cartItems = CartItem::with('product')
                ->where('cart_id', $cartId)
                ->get();

            $totalWeight = $cartItems->sum('quantity') * self::ITEM_WEIGHT;

            $items = $cartItems->map(function ($item) {
                return [
                    'name' => $item->product->name,
                    'quantity' => $item->quantity
                ];
            })->toArray();

            $requestPayload = [
                'from_district_id' => self::FROM_DISTRICT_ID,
                'from_ward_code' => self::FROM_WARD_CODE,
                'service_id' => self::SERVICE_ID,
                'service_type_id' => (int)self::SERVICE_TYPE_ID,
                'to_district_id' => (int)$toDistrictId,
                'to_ward_code' => (string)$toWardCode,
                'weight' => $totalWeight,
                'insurance_value' => 0,
                'cod_failed_amount' => 0,
                'items' => $items
            ];

			Log::debug('Request payload for shipping fee calculation', [
				'to_district_id' => $toDistrictId,
				'to_ward_code' => $toWardCode,
				'total weight' => $totalWeight,
				'items' => $items
			]);

            try {
				$response = $this->makeApiCall($requestPayload);
				
				if ($response->successful()) {
					$data = $response->json();
					return $data['data']['total'] ?? 0;
				}
				
				throw new ShippingFeeCalculationException('Failed to calculate shipping fee: ' . $response->body());
			} catch (Exception $e) {
				Log::error('Shipping fee calculation failed', [
					'error' => $e->getMessage(),
					'cart_id' => $cartId
				]);
				throw new ShippingFeeCalculationException($e->getMessage());
			}
        } catch (Exception $e) {
            Log::error('Shipping fee calculation failed', [
                'error' => $e->getMessage(),
                'cart_id' => $cartId
            ]);
            throw new ShippingFeeCalculationException($e->getMessage());
        }
    }
}
