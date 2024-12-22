<?php

namespace App\Http\Controllers;

use App\Exceptions\ShippingFeeCalculationException;
use App\Services\ShippingService;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class CheckOutController extends Controller
{
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
        
        return view('cart.checkout', [
            'cartItems' => $cartData['instockCartItems'],
            'totalQuantity' => $cartData['totalQuantity'],
            'totalDiscountedPrice' => $cartData['totalDiscountedPrice']
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

    // public function getInitialShippingFee()
    // {
    //     try {
    //         $user = Auth::user();

    //         if (!$user->province_city || !$user->district || !$user->commune_ward) {
    //             throw new ShippingFeeCalculationException('Missing location data');
    //         }

    //         Log::debug('Prepare for mapping location');

    //         $locationCodes = $this->mappingLocationCodes(
    //             $user->province_city,
    //             $user->district,
    //             $user->commune_ward
    //         );
            
    //         Log::debug('Location codes after mapping successfully: ', $locationCodes);
            
    //         if (!$locationCodes) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Could not map location to valid codes',
    //                 'data' => null
    //             ], 422);
    //         }

    //         Log::debug('Before calculate shipping fee');

    //         $response = $this->shippingService->calculateFee(
    //             $locationCodes['district_id'],
    //             $locationCodes['ward_code'],
    //             $user->cart_id
    //         );
            
    //         Log::debug('Initial shipping fee calculated:', ['fee' => $response['total']]);

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Shipping fee calculated successfully',
    //             'data' => [
    //                 'shipping_fee' => (int) $response['total'],
    //                 'location' => $locationCodes
    //             ]
    //         ]);

    //     } catch (Exception $e) {
    //         Log::error('Shipping fee calculation failed', [
    //             'error' => $e->getMessage(),
    //             'user_id' => $user->id
    //         ]);

    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Failed to calculate shipping fee',
    //             'data' => null
    //         ], 500);
    //     }
    // }

    public function calculatingShippingFee(Request $request){
        try {
            // Access nested data
            // $data = $request->input('to_district_id');
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
                'province_id' => $locationCodes['province_id']
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
}