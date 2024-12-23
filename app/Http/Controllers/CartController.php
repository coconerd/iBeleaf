<?php
namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CartItem;
use Exception;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Voucher;
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

			$inStockItems = $cartItems->filter(function($item) {
            	return $item->product->stock_quantity > 0;
        	});

			$totalPrice = $inStockItems->sum('original_price');
			$totalDiscountAmount = $inStockItems->sum('discount_amount');
			$totalDiscountedPrice = $totalPrice - $totalDiscountAmount;
			$totalQuantity = $inStockItems->sum('quantity');

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
				
				// if (!$user->cart_id) {
				// 	$user->cart_id = $cart->cart_id;
				// 	$user->save();
				// }

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
	// public function insertItemsToOrder(Request $request){
	// 	try{
	// 		$user = Auth::user();
			
	// 		$quantity = $request->input('quantity');
	// 		$totalUnitPrice = $request->input('total_uprice');
	// 		$discountedAmount = $request->input('discount_amount');

	// 		$voucherId = Voucher::where('voucher_name', $request->input('voucher_name'))->first()->voucher_id;
	// 		$cartItems = CartItem::with(['product'])
	// 				->where('cart_id', $user->cart_id)
	// 				->get();
			
	// 		// 1. Create new Order
	// 		$order = Order::create([
	// 			'user_id' => $user->id,
	// 			'voucher_id' => $voucherId,
	// 			'total_price' => 0,
	// 			'provisional_price' => 0
	// 		]);
	// 		Log::debug('Order is created');

	// 		// 2. Transfer Cart items to Order items
	// 		$orderItems = [];
	// 		foreach($cartItems as $item){
	// 			$orderItems[] = OrderItem::create([
	// 				'order_id' => $order->order_id,
	// 				'product_id' => $item->product_id,
	// 				'quantity' => $quantity,
	// 				'total_price' => $totalUnitPrice,
	// 				'discounted_amount'=> $discountedAmount
	// 			]);
	// 			Log::debug('Transfer cart items to order items: ', ['items' => $orderItems[count($orderItems) - 1]]);
	// 		}

	// 		// 3. Update Order table
	// 		$order->total_price = OrderItem::sum('total_price');
	// 		$order->provisional_price = $order->total_price; // Be updated after user's address is provided
	// 		$order->save();
	// 		Log::debug('Update Order table successfully!');
			
	// 		// 4. Clear Cart items nad update Cart items_count
	// 		CartItem::where('cart_id', $user->cart_id)->delete();
	// 		$cart = Cart::find('$user->cart_id');
	// 		$cart->items_count = CartItem::where('cart_id', $user->cart_id)->sum('quantity');
	// 		$cart->save();
	// 		Log::debug('Cart updated: items_count set to ' . $cart->items_count);

	// 		// Update Product stock
	// 		foreach($orderItems as $item){
	// 			$product = Product::find($item->product_id);

	// 			$currentStock = $product->getAttribute('stock_quantity');
	// 			$product->setAttribute('stock_quantity', $currentStock - $item->quantity);
	// 			$product->save();

	// 			Log::debug('Stock quantity updated', [
	// 				'product_id' => $product->product_id,
	// 				'old_quantity' => $currentStock,
	// 				'new_quantity' => $currentStock - $item->quantity,
	// 				'difference' => $item->quantity
	// 			]);
	// 		}
	// 	}
	// 	catch (Exception $e){
	// 		Log::error('Order creation failed', [
	// 			'error' => $e->getMessage(),
	// 			'user_id' => $user->id
	// 		]);
			
	// 		return response()->json([
	// 			'success' => false,
	// 			'message' => $e->getMessage()
	// 		], 500);
	// 	}
	// }

	// private function createOrder($user){
	// 	$voucherId = Voucher::where('voucher_name', ['voucher_name'])
    //     ->firstOrFail()
    //     ->voucher_id;

	// 	$order = Order::create([
	// 		'user_id' => $user->id,
	// 		'voucher_id' => $voucherId,
	// 		'total_price' => 0,
	// 		'provisional_price' => 0
	// 	]);

	// 	Log::debug('Order created', ['order_id' => $order->order_id]);
	// 	return $order;
	// }
}

