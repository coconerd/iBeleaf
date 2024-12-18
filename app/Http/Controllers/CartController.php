<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CartItem;
use Exception;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use Log;

class CartController extends Controller
{
	private function getCartItems(User $user): array
	{
		try {
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
		} catch (Exception $e) {
			return [
				'cartItems' => [],
				'totalPrice' => 0,
				'totalDiscountAmount' => 0,
				'totalDiscountedPrice' => 0,
				'totalQuantity' => 0,
				'error' => 'Failed to fetch cart items!'
			];
		}
	}

	public function showCartItems(Request $request)
	{
		try {
			if (Auth::check()) {
				$user = Auth::user(); // Gets User model instance
				if ($user instanceof User) {
					$cartItems = $this->getCartItems($user);
					return view('cart.items', $cartItems);
				}
			}
		} catch (Exception $e) {
			return response()->json([
				'success' => false,
				'message' => 'Failed to fetch cart items!',
			], 500);
		}
	}

	public function insertItemToCart(Request $request)
	{
		try {
			$user = Auth::user();
			$productId = $request->input('product_id');
			$quantity = $request->input('quantity', 1);
			$unitPrice = $request->input('unit_price');

			// Retrieve the user's cart or create a new one if it doesnt exist
			$cart = Cart::find($user->cart_id);
			if ($user->cart_id !== null) {
				Log::debug('User has exsiting cart: ', ['cart' => $user->cart()]);
				// $cart = Cart::where('cart_id', '=', $user->cart_id);
				Log::debug('Cart id:', ['id' => $user->cart_id]);
			} else {
				$cart = Cart::create(['items_count' => 0]);
				$user->cart()->update(['cart_id' => $cart->cart_id]);
				Log::debug('User not have exsiting cart, new cart created: ', ['newCart' => $cart]);
			}

			Log::debug('HERE');

			// Retrieve the cart items if it already exists
			// $cartId = $cart->getAttribute('id');
			// $cartItem = CartItem::where('cart_id', $user->cart_id)
			// 	->where('product_id', $productId);

				$cartItem = CartItem::with(['product']) // Eloquent ORM: fetches all cart items with associated product
				->where('cart_id', $user->cart_id) // Condition to ensure only cart items of the user are fetched
				->get()
				->first();
			Log::debug('cartItems is: ' . json_encode($cartItem));

			Log::debug('HERE2');

			if (!$cartItem) {
				CartItem::create([
					'cart_id' => $user->cart_id,
					'product_id' => $productId,
					'quantity' => $quantity,
					'unit_price' => $unitPrice
				]);
			} else {
				Log::debug('HERE22');
				CartItem::where([
					'cart_id' => $user->cart_id,
					'product_id' => $productId
				])->update([
					'quantity' => $cartItem->quantity + $quantity
				]);
					
				
			}

			Log::debug('HERE3');

			// Update the items count
			$cart->items_count = CartItem::where('cart_id', $user->cart_id)
				->sum('quantity');
			$cart->save();

			Log::debug('HERE4');
			$cart->save();
			return response()->json([
				'success' => true,
				'message' => 'Item added to cart successfully!'
			], 200);
		} catch (Exception $e) {
			Log::error('CartController@insertItemToCart: ', ['error' => $e]);
			return response()->json([
				'success' => false,
				'message' => 'Failed to insert item to cart!',
			], 500);
		}
	}

	public function removeCartItem($cartId, $productId)
	{
		try {
			$cartItem = CartItem::where('cart_id', $cartId)
				->where('product_id', $productId);

			if ($cartItem instanceof CartItem) {
				return response()->json([
					'success' => false,
					'message' => 'Cart item not found!'
				], 404);
			}

			$cartItem->delete();

			$cart = Cart::find($cartId);
			$cart->items_count = CartItem::where('cart_id', $cartId)->sum('quantity');
			$cart->save();

			return response()->json([
				'success' => true,
				'message' => 'Item removed successfully!',
				'items count' => $cart->items_count
			]);

		} catch (Exception $e) {
			return response()->json([
				'success' => false,
				'message' => 'Failed to remove item!'
			], 500);
		}
	}
}

