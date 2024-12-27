<?php

namespace App\Http\Controllers;

use App\Exceptions\ShippingFeeCalculationException;
use App\Services\ShippingService;
use App\Models\CartItem;
use App\Models\OrderItem;
use App\Models\Voucher;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Exception;

class CheckOutController extends Controller
{
    private const REQUIRED_STRING_RULE = 'required|string';
    private $shippingService;

    public function __construct(ShippingService $shippingService){
        $this->shippingService = $shippingService;
    }

    public function getCartItems()
    {
        $user = Auth::user();
        if (!$user instanceof \App\Models\User) {
            abort(401);
        }
        $cartController = new CartController();
        $cartData = $cartController->getCartItems($user);

        $allItemsTypeOne = $cartData['instockCartItems']->every(function($item){
            return $item->product->type == 1;
        });

        return view('cart.checkout', [
            'cartItems' => $cartData['instockCartItems'],
            'totalQuantity' => $cartData['totalQuantity'],
            'totalDiscountedPrice' => $cartData['totalDiscountedPrice'],
            'user' => $user,
            'allItemsTypeOne' => $allItemsTypeOne,
            'voucher_id' => session('voucher_id', null),
            'voucher_value' => session('voucher_value', 0)
        ]);
    }

    private function mappingLocationCodes($provinceName, $districtName, $wardName)
    {
        $data = json_decode(file_get_contents(storage_path('data/provinces.json')), true);
        
        $provinceId = null;
        foreach ($data as $id => $province) {
            if (strtolower($province['ProvinceName']) === strtolower($provinceName)) {
                $provinceId = $id;
                break;
            }
        }
        
        if (!$provinceId || !isset($data[$provinceId]['Districts'])) {
            Log::warning('Province not found', ['province' => $provinceName]);
            return null;
        }

        $districts = $data[$provinceId]['Districts'];
        $districtId = null;
        foreach ($districts as $id => $district) {
            if (strtolower($district['DistrictName']) === strtolower($districtName)) {
                $districtId = $id;
                break;
            }
        }

        if (!$districtId || !isset($districts[$districtId]['Wards'])) {
            Log::warning('District not found', ['district' => $districtName]);
        }

        // Find ward safely
        $wards = $districts[$districtId]['Wards'];
        $wardCode = null;
        foreach ($wards as $code => $ward) {
            if (strtolower($ward['WardName']) === strtolower($wardName)) {
                $wardCode = $code;
                break;
            }
        }

        if (!$wardCode) {
            Log::warning('Ward not found', ['ward' => $wardName]);
            return null;
        }

        return [
            'district_id' => $districtId,
            'ward_code' => $wardCode,
            'province_id' => $provinceId
        ];
    }

    public function calculatingShippingFee(Request $request){
        try {

            Log::debug('See difference in address, calcuating new shipping fee');
            
            $toDistrictId = $request->input('to_district_id');
            $toWardCode = $request->input('to_ward_code');
            if (!$toDistrictId || !$toWardCode) {
                throw new ValidationException(validator([], []));
            }
            
            // Convert to integers where needed
            $toDistrictId = (int) $toDistrictId;
            $toWardCode = (string) $toWardCode;
            $cartId = Auth::user()->cart_id;

            Log::debug('Data input for shipping calculation', [
                'district_id' => $toDistrictId,
                'ward_code' => $toWardCode,
                'cart_id' => $cartId
            ]);

            $fee = $this->shippingService->calculateFee(
                $toDistrictId,
                $toWardCode,
                $cartId
            );

            Log::debug('Shipping fee calculated', [
                'fee' => $fee,
                'to_district_id' => $toDistrictId,
                'to_ward_code' => $toWardCode,
                'cart_id' => $cartId
            ]);

            return response()->json([
                'success' => true,
                'shipping_fee' => $fee
            ]);

        } catch (ShippingFeeCalculationException $e) {
            return response()->json([
                'success'=> false,
                'message' => $e->getMessage(),
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'success'=> false,
                'message' => 'An error occurred while calculating shipping fee',
            ], 500);
        }
    }

    public function getUserInfo()
    {
        try {
            $user = Auth::user();
            Log::debug('Getting user info', ['user' => $user->user_id]);
            
            $response = [
                'success' => true,
                'fullname' => $user->full_name ?? null,
                'phone' => $user->phone_number ?? null,
                'address' => $user->address ?? null
            ];

            if ($user->province_city && $user->district && $user->commune_ward) {
                $locationCodes = $this->mappingLocationCodes(
                    $user->province_city,
                    $user->district,
                    $user->commune_ward
                );
                
                $response = array_merge($response, [
                    'province' => $user->province_city,
                    'district' => $user->district,
                    'ward' => $user->commune_ward,
                    'district_id' => $locationCodes['district_id'] ?? null,
                    'ward_code' => $locationCodes['ward_code'] ?? null,
                    'province_id' => $locationCodes['province_id'] ?? null
                ]);
            }

            Log::debug('Sending response:', $response);
            return response()->json($response);

        } catch (Exception $e) {
            Log::error('Failed to load user info', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);
            
            return response()->json([
                'success'=> false,
                'message' => 'An error occurred while getting user shipping info',
            ], 500);
        }
    }

    public function updateDefaultAddress(Request $request)
    {
        try{
            $validated = $request->validate([
                'address' => self::REQUIRED_STRING_RULE
            ]);

            Log::debug('Validated data', $validated);

            $userId = Auth()->user()->user_id;

            DB::table('users')
            ->where('user_id', $userId)
            ->update([
                'province_city' => request('province'),
                'district' => request('district'),
                'commune_ward' => request('ward'),
                'address' => $validated['address']
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Địa chỉ đã được cập nhật thành công'
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid data',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update address',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function getPreviousDeliveryCost(): ?float
    {
        return Order::where('user_id', Auth::id())
            ->latest()
            ->value('deliver_cost');
    }

    public function submitOrder(Request $request)
    {
        DB::beginTransaction();
		try{
			$user = Auth::user();
			
            // Validate request
            $validated = $request->validate([
                'address' => 'required|array'
            ]);

			$cartItems = CartItem::with(['product'])
                    ->where('cart_id', $user->cart_id)
                    ->whereHas('product', function($query) {
                        $query->where('stock_quantity', '>', 0);
                    })
                    ->get();

            $voucherId = Voucher::where('voucher_name', $request->voucher_name)->first()?->voucher_id;
            
            $preDeliverCost = $this->getPreviousDeliveryCost();

            Log::debug('Previous Delivery Cost: ', ['cost' => $preDeliverCost]);

			// 1. Create new Order
            Log::debug('Pre Deliver Cost: ', ['cost' => $preDeliverCost]);

            $order = $this->createNewOrder(
                $voucherId,
                $request->real_provisional_price,
                $preDeliverCost,
                $request->total_price,
                $validated['address'],
                $request->payment_method ?? 'COD',
                $request->additional_note ?? null
                // $request->deliver_address
            );

			// 2. Transfer Cart items to Order items
			foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->getAttribute('order_id'),
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'total_price' => $item->discounted_price,
                    'discounted_amount' => $item->discount_amount ?? 0
                ]);

                Log::debug('Check order items: ', [
                    'quantity', $item->quantity,
                    'total_price', $item->discounted_price,
                    'discounted_amount' => $item->discount_amount ?? 0
                ]);

                // Update product stock quantity
                $product = Product::find($item->product_id);
                $product->decrement('stock_quantity', $item->quantity);
                $product->total_orders += $item->quantity;
                $product->save();
            }
            
			// 3. Clear Cart items and update Cart items_count
			CartItem::where('cart_id', $user->cart_id)->delete();
			Cart::where('cart_id', $user->cart_id)->update(['items_count' => 0]);
            DB::commit();

			return response()->json([
                'success' => true,
                'order_id' => $order->getAttribute('order_id'),
                'message' => 'Order created successfully'
            ]);
		}
		catch (Exception $e){
			DB::rollBack();
            Log::error('Order creation failed', [
                'error' => $e->getMessage(),
                'user_id' => $user->id ?? null
            ]);
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
	}
    private function isAddressChanged($newProvince, $newDistrict, $newCity)
    {
        $user = Auth::user();
        
        $isDifferent = $user->province_city !== $newProvince ||
            $user->district !== $newDistrict ||
            $user->commune_ward !== $newCity;
            
        Log::debug('Address changed?', ['isDifferent' => $isDifferent]);

        return $user->province_city !== $newProvince ||
            $user->district !== $newDistrict ||
            $user->commune_ward !== $newCity;
    }

    protected function extractShippingFee($response)
    {
        if ($response instanceof JsonResponse) {
            $content = json_decode($response->getContent(), true);
            return isset($content['shipping_fee']) ? (int)$content['shipping_fee'] : 0;
        }
        return (int)$response;
    }

    public function createNewOrder(
        $voucherId,
        $provisionalPrice,
        $deliveryCost,
        $totalPrice,
        $address,
        $paymentMethod,
        $additionalNote)
    {
        try {
            if (!isset($address['province_city']) || !isset($address['district']) || !isset($address['commune_ward'])) {
                throw new Exception('Invalid address format');
            }
            $formattedAddress = implode(', ', [
                $address['address'] ?? '',
                $address['commune_ward'] ?? '',
                $address['district'] ?? '',
                $address['province_city'] ?? '',
            ]);

            $userId = Auth::id();
            Log::debug('User id: ', ['id' => $userId]);
            
            $addressChanged = $this->isAddressChanged(
                $address['province_city'],
                $address['district'],
                $address['commune_ward']
            );

            $finalDeliverCost = $addressChanged ?
                $this->extractShippingFee($this->calculatingShippingFee(new Request($address))) :
                (int)$deliveryCost;

            if (!is_int($finalDeliverCost)) {
                throw new Exception('Invalid delivery cost format');
            }
            
            Log::debug('Order data: ', [
                'additional_note' => $additionalNote,
                'delivery_cost' => $finalDeliverCost
            ]);

            Log::debug('Creating new order step!');
            Log::debug('Creating order with metrics:', [
                'user_id' => $userId,
                'voucher_id' => $voucherId,
                'total_price' => $totalPrice,
                'provisional_price' => $provisionalPrice,
                'deliver_cost' => $finalDeliverCost,
                'payment_method' => $paymentMethod,
                'additional_note' => $additionalNote,
                'deliver_address' => trim($formattedAddress)
            ]);

            return Order::create([
                'user_id' => $userId,
                'voucher_id' => $voucherId,
                'total_price' => $totalPrice,
                'provisional_price' => $provisionalPrice,
                'deliver_cost' => $finalDeliverCost,
                'payment_method' => $paymentMethod,
                'additional_note' => $additionalNote,
                'deliver_address' => trim($formattedAddress)
            ]);

        } catch (Exception $e) {
            Log::error('Failed to create new order', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);
            throw $e;
        }
	}

    public function showSuccessPage(){
        return view('cart.success');
    }
}