<?php
namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CartItem;
use Exception;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use Log;
use DB;

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
			$quantity = (int) $request->input('quantity', 1);

			// Validate product exists
			if (!Product::find($productId)) {
				return response()->json([
					'success' => false,
					'message' => 'Product not found'
				], 404);
			}

			// Get or create cart in a single query
			$cart = Cart::firstOrCreate(
				['cart_id' => $user->cart_id],
				['items_count' => 0]
			);

			if (!$user->cart_id) {
				$user->cart_id = $cart->cart_id;
				$user->save();
			}

			// Update or create cart item using updateOrCreate
			$cartItem = CartItem::where('cart_id', $cart->cart_id)
				->where('product_id', $productId)
				->first();

			if ($cartItem) {
				$cartItem->quantity += $quantity;
				$cartItem->save();
			} else {
				CartItem::create([
					'cart_id' => $cart->cart_id,
					'product_id' => $productId,
					'quantity' => $quantity
				]);
			}

			// Update cart items count efficiently
			$cart->items_count = CartItem::where('cart_id', $cart->cart_id)
				->sum('quantity');
			$cart->save();

			return response()->json([
				'success' => true,
				'message' => 'Item added to cart successfully',
				'items_count' => $cart->items_count
			]);

		} catch (Exception $e) {
			Log::error('Failed to add item to cart', [
				'user_id' => Auth::id(),
				'product_id' => $productId ?? null,
				'error' => $e->getMessage()
			]);

			return response()->json([
				'success' => false,
				'message' => 'Failed to add item to cart'
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

