<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use App\Models\Cart;
use App\Models\User;
use App\Exceptions\CartCountUpdateException;
use Illuminate\Validation\ValidationException;
use App\Models\CartItem;
use Exception;
use Illuminate\Support\Facades\Auth;

/**
 * Get formatted cart data for the given user
 *
 * @param User $user
 * @return array
 */

//Backend logic: handles db updates and fetches cart items
class CartController extends Controller
{
    private function getCartItems(User $user) : array{
        //eager loading
        $cartItems = CartItem::with(['product'])
                ->where('cart_id', $user->card_id) // ensure only cart items associated with user are fetched
                ->get();

        $totalPrice = $cartItems->sum('original_price');
        $totalDiscountAmount = $cartItems->sum('discount_amount');
        $totalDiscountedPrice = $totalPrice - $totalDiscountAmount;

        return [
            'cartItems' => $cartItems,
            'totalPrice' => $totalPrice,
            'totalDiscountAmount' => $totalDiscountAmount,
            'totalDiscountedPrice' => $totalDiscountedPrice
        ];
    }

    public function showCartItems(Request $request)
    {
        $defaultResponse = [
            'cartItems' => collect(),
            'totalPrice' => 0,
            'totalDiscountAmount' => 0,
            'totalDiscountedPrice' => 0
        ];
        try {
            $response = null;
            $user = Auth::user();
            if(!$user){
                $response = $defaultResponse;
            }
            if ($user instanceof User) {
                $cartItems = $this->getCartItems($user);
                $response = view('cart.index', $cartItems);
            } else {
                $response = $defaultResponse;
            }
            return $response;
            
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch cart items',
            ], 500);
        }
    }

    // public function insertItemToCart(Request $request){
    //     try {

    //     }
    // }
}


