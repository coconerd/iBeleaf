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

//Backend logic: handles db updates and fetches cart items
class CartController extends Controller
{
    public function showCartItems(Request $request)
    {
        try {

            // Check if user is authenticated
            if (!Auth::check()) {
                return redirect()->route('login');
            }
            $userId = Auth::id();
            $user = User::find($userId);

            if (!$userId || !$user->card_id) {
                return view('cart.index', [
                    'cartItems' => collect(),
                    'totalPrice' => 0,
                    'totalDiscountAmount' => 0,
                    'totalDiscountedPrice' => 0
                ]);
            }

            // Eloquent ORM to fetch cart items from db
            $cartItems = CartItem::with(['product']) //eager loading
                ->where('cart_id', $user->card_id) // ensure only cart items associated with cart are fetched
                ->get();
            
            // total original price
            $totalPrice = $cartItems->sum('original_price');

            // Calculate total discounted amount
            $totalDiscountAmount = $cartItems->sum('discount_amount');

            // Calculate final price after discounts
            $totalDiscountedPrice = $totalPrice - $totalDiscountAmount;

            return view('cart.index', compact(
                'cartItems',
                'totalPrice',
                'totalDiscountAmount',
                'totalDiscountedPrice'
            )); // compact() creates an array containing variables and their values

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


