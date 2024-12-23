<?php

namespace App\Http\Controllers;

use App\Exceptions\ShippingFeeCalculationException;
use App\Services\ShippingService;
use App\Models\CartItem;
use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
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

    public function applyVoucher(Request $request)
    {
        // Validate and store voucher in session
        $voucherData = [
            'id' => $request->voucher_id,
            'discount' => $request->discount,
            'description' => $request->description
        ];
        
        session(['active_voucher' => $voucherData]);
        
        return response()->json([
            'success' => true,
            'message' => 'Voucher applied',
            'data' => session('active_voucher')
        ]);
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
            // 'activeVoucher' =>
        ]);
    }

    private function mappingLocationCodes($provinceName, $districtName, $wardName)
    {
        $data = json_decode(file_get_contents(storage_path('data/provinces.json')), true);
        
        // Find province ID safely
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

        // Find district safely
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
            // Access nested data
            // $data = $request->input('to_district_id');
            Log::debug('See difference in address, calcuating new shipping fee');

            $districtId = $request['to_district_id'] ?? null;
            $wardCode = $request['to_ward_code'] ?? null;

            if (!$districtId || !$wardCode) {
                throw new ValidationException(validator([], []));
            }

            // Convert to integers where needed
            $districtId = (int) $districtId;
            $wardCode = (string) $wardCode;
            $cartId = Auth::user()->cart_id;

            Log::debug('Data input for shipping calculation', [
                'district_id' => $districtId,
                'ward_code' => $wardCode,
                'cart_id' => $cartId
            ]);

            $fee = $this->shippingService->calculateFee(
                $districtId,
                $wardCode,
                $cartId
            );

            Log::debug('Shipping fee calculated', [
                'fee' => $fee,
                'to_district_id' => $districtId,
                'to_ward_code' => $wardCode,
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
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }
            
            $locationCodes = $this->mappingLocationCodes(
                $user->province_city,
                $user->district,
                $user->commune_ward,
            );

            Log::debug('DistrictId: ', ['id'=> $locationCodes['district_id']]);
            Log::debug('Ward code: ', ['code' => $locationCodes['ward_code']]);

            return response()->json([
                'success' => true,
                'fullname' => $user->full_name,
                'phone' => $user->phone_number,
                'province' => $user->province_city,
                'district' => $user->district,
                'ward' => $user->commune_ward,
                'district_id' => $locationCodes['district_id'],
                'ward_code' => $locationCodes['ward_code'],
                'province_id' => $locationCodes['province_id'],
                'address' => $user->address
            ]);

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
					->get();
            if ($cartItems->isEmpty()) {
                throw new Exception('Cart is empty');
            }
            
            $preDeliverCost = $this->getPreviousDeliveryCost();

            Log::debug('Previous Delivery Cost: ', ['cost' => $preDeliverCost]);

			// 1. Create new Order
            $order = $this->createNewOrder(
                $request->voucher_id ?? null,
                $request->provisional_price,
                $preDeliverCost,
                $request->total_price,
                $validated['address'],
                $request->payment_method ?? 'COD',
                $request->additional_note ?? null,

                $request->deliver_address
            );

			// 2. Transfer Cart items to Order items
			foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->getAttribute('order_id'),
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'total_price' => $item->discounted_price,
                    'discounted_amount' => $item->discounted_amount ?? 0
                ]);

                Log::debug('Check order items: ', [
                    'quantity', $item->quantity,
                    'total_price', $item->discounted_price,
                    'discounted_amount' => $item->discounted_amount ?? 0
                ]);

                // Update product stock quantity
                $product = Product::find($item->product_id);
                $product->decrement('stock_quantity', $item->quantity);
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

    public function createNewOrder(
        $voucherId,
        $provisionalPrice,
        $deliveryCost,
        $totalPrice,
        $address,
        $paymentMethod,
        $additionalNote)
    {
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
            $this->calculatingShippingFee($address) :
            $deliveryCost;
        
        Log::debug('Order data: ', [
            'additional_note' => $additionalNote,
            'delivery_cost' => $finalDeliverCost
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
	}

    public function showSuccessPage($orderId){
        $order = Order::findOrFail($orderId);
        return view('orders.success', [
            'order' => $order
        ]);
    }
}