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
    private const ITEM_WEIGHT = 3000; // 3kg
    private const SERVICE_ID = 53321;
    private const SERVICE_TYPE_ID = 2;
    private const FROM_DISTRICT_ID = 3695;
    private const FROM_WARD_CODE = "90737";

	private const MAX_RETRIES = 2;
	private const BASE_TIMEOUT = 5;
	private const RETRY_DELAY_MS = 50000;
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
		Log::Debug('Go to makeApiCall function');

		// Cache the response to reduce the number of API calls
		$cacheKey = 'shipping_fee_' . md5(json_encode($payload));
		if (Cache::has($cacheKey)) {
			Log::Debug('Cache successfully');
			return Cache::get($cacheKey);
		}

		try {
			$timeout = self::BASE_TIMEOUT * pow(1, $attempt - 1); // Exponential backoff
			
			return Http::timeout($timeout)
				->withHeaders([
					'Token' => $this->apiToken,
					'ShopId' => $this->shopId,
					'Content-Type' => 'application/json',
				])
				->post(self::API_ENDPOINT, $payload);
		} catch (Exception $e) {
			// Retry if the request failed
			if ($attempt < self::MAX_RETRIES) {
				Log::warning("API call attempt {$attempt} failed, retrying...", [
					'error' => $e->getMessage()
				]);
				usleep(self::RETRY_DELAY_MS); // Pauses execution in microseconds
				return $this->makeApiCall($payload, $attempt + 1);
			}
			throw new ShippingFeeCalculationException('Max retries exceeded');
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

			// Generate cache key based on shipping details
			$cacheKey = 'shipping_fee_' . md5(json_encode([
				'district_id' => $toDistrictId,
				'ward_code' => $toWardCode,
				'items' => $items
			]));

			// Try to get from cache first
			if (Cache::has($cacheKey)) {
                Log::debug('Retrieved shipping fee from cache', ['cache_key' => $cacheKey]);
                return Cache::get($cacheKey);
            }

			Log::debug('Starting fee calculation', [
				'district' => $toDistrictId,
				'ward' => $toWardCode,
				'cart' => $cartId
			]);

            $requestPayload = [
                'from_district_id' => self::FROM_DISTRICT_ID,
                'from_ward_code' => self::FROM_WARD_CODE,
                'service_id' => self::SERVICE_ID,
                'service_type_id' => self::SERVICE_TYPE_ID,
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

			$response = $this->makeApiCall($requestPayload);
			
			if ($response->status() === 200) {
				$data = $response->json();
				$shippingFee = $data['data']['total'] ?? 0;
				// Cache the result for 24 hours
                Cache::put($cacheKey, $shippingFee, now()->addHours(24));
				Log::debug('Calculate shipping fee succesfully');
				return $shippingFee;
			}

			throw new ShippingFeeCalculationException('Failed to calculate shipping fee: ' . $response->body());
			
        } catch (Exception $e) {
            Log::error('Shipping fee calculation failed', [
                'error' => $e->getMessage(),
                'cart_id' => $cartId
            ]);
            throw new ShippingFeeCalculationException($e->getMessage());
        }
    }
}
