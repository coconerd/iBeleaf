<?php
namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CartItem;
use Exception;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use Illuminate\Support\Facades\Log;
use DB;

class CartController extends Controller
{
	public function getCartItems(User $user): array
	{
		try {
			$cartId = $user->getAttribute('cart_id');
			$cartItems = CartItem::with(['product']) // Eloquent ORM: fetches all cart items with associated product
				->where('cart_id', $cartId) // Condition to ensure only cart items of the user are fetched
				->get();

			$inStockItems = CartItem::with(['product'])
				->whereHas('product', function($query) {
					$query->where('stock_quantity', '>', 0);
				})
				->where('cart_id', $cartId)
				->get();

			$totalDiscountedPrice = $inStockItems->sum('discounted_price');
			$totalQuantity = $inStockItems->sum('quantity');
			$totalPrice = $inStockItems->sum('original_price');
			$totalDiscountAmount = $inStockItems->sum('discount_amount');
			return [
				'cartItems' => $cartItems,
				'instockCartItems' => $inStockItems,
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

	public function updatePrice(Request $request)
	{
		try {
			$user = Auth::user();
			$cartId = $user->cart_id;

			$cartItem = CartItem::whereHas('product', function($query) {
				$query->where('stock_quantity', '>', 0);
			})->where([
				'cart_id' => $cartId,
				'product_id' => $request->product_id
			])->first();

			if ($cartItem) {
				$cartItem->update([
					'quantity' => $request->quantity,
					'original_price' => $request->original_price,
					'discount_amount' => $request->discount_amount,
					// 'final_price' => $request->final_price
				]);

				return response()->json([
					'success' => true,
					'message' => 'Price updated successfully'
				]);
			}

			return response()->json([
				'success' => false,
				'message' => 'Cart item not found'
			], 404);

		} catch (Exception $e) {
			return response()->json([
				'success' => false,
				'message' => 'Failed to update price'
			], 500);
		}
	}

	public function showCartItems(Request $request)
	{
		try {
			if (Auth::check()) {
				$user = Auth::user(); // Gets User model instance
				if ($user instanceof User) {
					$cartItems = $this->getCartItems($user);

					// Store voucher data in session if present
					if ($request->has('voucher_id')) {
						session([
							'voucher_id' => $request->voucher_id
						]);
					}

					 // Add voucher data to view data
					$viewData = array_merge($cartItems, [
						'voucher_id' => session('voucher_id'),
						'voucher_value' => session('voucher_value')
					]);

					return view('cart.items', $viewData);
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
			$quantity = (int)$request->input('quantity', 1);
			$isValid = true;
			$errorMessage = '';
			Log::debug('Request quantity:', ['quantity'=> $quantity]);
			
			// Validate product exists
			$product = Product::find($productId);
			if (!$product) {
				$isValid = false;
				$errorMessage = 'Product not found';
			}
			
			if ($isValid) {
				//Get or create cart in a single query
				$cart = Cart::firstOrCreate(
					['cart_id' => $user->cart_id],
					['items_count' => 0]
				);

				$cartItem = CartItem::with(['product'])
					->where('cart_id', $user->cart_id)
					->where('product_id', $productId)
					->first();
				
				// Stock validation
				$newQuantity = $cartItem ? $cartItem->quantity + $quantity : $quantity;
				if ($product->getAttribute('stock_quantity') < $newQuantity) {
					$isValid = false;
					$errorMessage = 'Insufficient stock available';
				}

				Log::debug('Stock quantity:', ['quantity'=> $newQuantity]);

				if ($isValid) {
					if (!$cartItem) {
						CartItem::create([
							'cart_id' => $cart->cart_id,
							'product_id' => $productId,
							'quantity' => $newQuantity
						]);
					} else {
						CartItem::where([
							'cart_id' => $user->cart_id,
							'product_id' => $productId
						])->update([
							'quantity' => $newQuantity
						]);
					}
					
					// Update the items count
					$cart->items_count = CartItem::where('cart_id', $user->cart_id)
						->sum('quantity');
					$cart->save();

					return response()->json([
						'success' => true,
						'message' => 'Item added to cart successfully',
						'items_count' => $cart->items_count
					]);
				}
			}

			return $this->errorResponse($errorMessage, 400);

		} catch (Exception $e) {
			Log::error('Failed to add item to cart', [
				'user_id' => Auth::id(),
				'product_id' => $productId ?? null,
				'error' => $e->getMessage()
			]);
			return $this->errorResponse('Failed to add item to cart', 500);
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

	public function updateCartItems(Request $request)
	{
		try {
			$user = Auth::user();
			$cartId = $user->cart_id;
			$items = $request->input('items');

			// Store voucher information in session to use later in CheckOut page
			session([
				'voucher_id' => $request->input('voucher_id'),
				'voucher_name' => $request->input('voucher_name'),
				'voucher_discount' => $request->input('voucher_discount')
			]);

			// Validate input format
			if (!is_array($items)) {
				return response()->json([
					'success' => false,
					'message' => 'Invalid input format'
				], 400);
			}

			$inStockItems = [];

			foreach ($items as $item){
				$productId = $item['product_id'];
				$quantity = $item['quantity'];

				$cartItem = CartItem::where('cart_id', $cartId)
					->where('product_id', $productId)
					->first();

            	// Check product stock
				$product = Product::find($productId);
				if ($cartItem && $product && $product->getAttribute('stock_quantity') > 0) {
					$cartItem->quantity = $quantity;
					$cartItem->save();
					$inStockItems[] = $productId;
				}
			}
			$cart = Cart::find($cartId);
			$cart->items_count = count($inStockItems);
			$cart->save();

			return response()->json([
				'success' => true,
				'message' => 'Cart updated successfully',
				'updated_items' => $inStockItems,
				'items_count' => $cart->items_count
        	]);

		} catch (Exception $e) {
			return response()->json([
				'success' => false,
				'message' => 'Failed to update cart and cart items'
			], 500);
		}
	}

	public function showCheckout()
	{
		try {
			if (Auth::check()) {
				$user = Auth::user();
				if ($user instanceof User) {
					$cartItems = $this->getCartItems($user);
					
					// Add voucher data from session to view data
					$viewData = array_merge($cartItems, [
						'voucher_name' => session('voucher_name'),
						'voucher_discount' => session('voucher_discount')
					]);

					return view('cart.checkout', $viewData);
				}
			}
			return redirect()->route('login');
		} catch (Exception $e) {
			return response()->json([
				'success' => false,
				'message' => 'Failed to load checkout page!'
			], 500);
		}
	}
}


