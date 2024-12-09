<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use App\Models\Cart;
use App\Exceptions\CartCountUpdateException;
use Illuminate\Validation\ValidationException;
use App\Models\CartItem;
use Exception;

//Backend logic: handles db updates and fetches cart items
class CartController extends Controller
{
    public function showCartItems(Request $request)
    {
        try {
            // Eloquent ORM to fetch cart items from db
            $cartItems = CartItem::with('cart')
                ->whereHas('cart') // ensure only cart items associated with a cart are fetched
                ->get();
            
            $totalPrice = $cartItems->sum(fn($cartItems)
                =>$cartItems->unit_price * $cartItems->quantity);
            return view('cart.index', compact('cartItems', 'totalPrice')); // compact() creates an array containing variables and their values

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch cart items',
            ], 500);
        }
    }
}


