<?php

namespace App\Http\Controllers;

use App\Exceptions\ShippingFeeCalculationException;
use App\Services\ShippingService;
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

    public function showCheckOutForm()
    {
        return view('cart.checkout');
    }

public function calculatingShippingFee(Request $request){
        try {
            Log::debug('Incoming request data', [
                'District and ward:' => $request->input('to_district_id'),
            ]);

            // Access nested data
            $data = $request->input('to_district_id');
            $districtId = $data['to_district_id'] ?? null;
            $wardCode = $data['to_ward_code'] ?? null;

            if (!$districtId || !$wardCode) {
                throw new ValidationException(validator([], []));
            }

            // Convert to integers where needed
            $districtId = (int) $districtId;
            $wardCode = (int) $wardCode;
            $cartId = Auth::user()->cart_id;
            
            Log::debug('Processing shipping calculation', [
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
}