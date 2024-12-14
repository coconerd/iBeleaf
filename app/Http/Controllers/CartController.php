<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CartItem;
use Exception;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;

class CartController extends Controller
{
    private function getCartItems(User $user) : array{
        $cartId = $user->getAttribute('cart_id');
        $cartItems = CartItem::with(['product']) // Eloquent ORM: fetches all cart items with associated product
                ->where('cart_id', $cartId) // Condition to ensure only cart items of the user are fetched
                ->get();

        $totalPrice = $cartItems->sum('original_price');
        $totalDiscountAmount = $cartItems->sum('discount_amount');
        $totalDiscountedPrice = $totalPrice - $totalDiscountAmount;
        $totalQuantity = $cartItems->sum('quantity');

        return [
            'cartItems' => $cartItems,
            'totalPrice' => $totalPrice,
            'totalDiscountAmount' => $totalDiscountAmount,
            'totalDiscountedPrice' => $totalDiscountedPrice,
            'totalQuantity' => $totalQuantity
        ];
    }

    public function showCartItems(Request $request)
    {
        try {
            if (Auth::check()) {
                $user = Auth::user();
                if ($user instanceof User) {
                    $cartItems = $this->getCartItems($user);
                    return view('cart.index', $cartItems);
                }
            }
            
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch cart items',
            ], 500);
        }
    }

    public function insertItemToCart(Request $request){
        try {
            $user = Auth::user();
            $productId = $request->input('product_id');
            $quantity = $request->input('quantity', 1);
            $unitPrice = $request->input('unit_price');

            // Retrieve the user's cart or create a new one if it doesnt exist
            $cart = Cart::firstOrCreate(
                ['user_id' => $user->id],
                ['item_count' => 0]
            );
            // Retrieve the cart items if it already exists
            $cartId = $cart->getAttribute('id');
            $cartItem = CartItem::where('cart_id', $cartId)
                                ->where('product_id', $productId);
            
            if ($cartItem){
                $cartItem->quantity += $quantity;
                $cartItem->save();
            }else{
                $cartItem = new CartItem();
                $cartItem->cart_id = $cartId;
                $cartItem->product_id = $productId;
                $cartItem->quantity = $quantity;
                $cartItem->unit_price = $unitPrice;
                $cartItem->save();
            }

            // Update the items count
            $cart->items_count = CartItem::where('cart_id', $cartId)->sum('quantity');
            $cart->save();

            return response()->json([
                'success' => true,
                'message' => 'Item added to cart successfully'
            ], 200);

        } catch (Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Failed to insert item to cart',
            ], 500);
        }
    }

    public function removeItemFromCart(Request $request)
    {
        try {
            $cartId = $request->cart_id;
            $productId = $request->product_id;
            $cartItem = CartItem::where('cart_id', $cartId)
                ->where('product_id', $productId);

            if (!$cartItem) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cart item not found'
                ], 404);
            }

            $cartItem->delete();

            // Update cart counts
            $cart = Cart::find($cartId);
            $cart->items_count = CartItem::where('cart_id', $cartId)->sum('quantity');
            $cart->save();

            return response()->json([
                'success' => true,
                // 'cartTotal' => CartItem::where('cart_id', $cartId)->sum('total_price');
                'cartCount' => $cart->items_count
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error removing item'
            ], 500);
        }
    }
}

