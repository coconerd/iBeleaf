<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use Illuminate\Support\Facades\Log;
use App\Providers\DBConnService;

class WishlistController extends Controller
{
	protected DBConnService $dbConnService;

	public function __construct(DBConnService $dbConnService)
	{
		$this->dbConnService = $dbConnService;
	}

	public function index()
	{
		$user = Auth::user();
		$wishlist = $user->wishlist();
		$wishlistProducts = Product::whereIn(
			'products.product_id',
			$wishlist->pluck('wishlists.product_id')
		)->select(
		[
			'product_id',
			'product_id',
			'name',
			'price',
			'code',
			'stock_quantity'
			]
		)->with('product_images', function ($query) {
			$query->where(
				'image_type',
				'=',
				1
			)->select(
					'product_image_url',
					'product_id'
				);
		})->get();
		Log::debug('Wishlist products: ' . json_encode($wishlistProducts));
		return view('wishlist.index', compact('wishlistProducts'));
	}

	public function add(Request $request)
	{
		try {
			$product_id = $request->input(key: 'product_id');
			$user = Auth::user();

			if ($user) {
				$user->wishlist()->syncWithoutDetaching($product_id);
				Log::info('User ' . $user->user_id . ' added product ' . $product_id . ' to wishlist');
				return response()->json(['success' => true]);
			} else {
				return response()->json(['success' => false], 401);
			}
		} catch (\Exception $e) {
			Log::error('Error adding product to wishlist: ' . $e->getMessage());
			return response()->json(['successs' => false], 500);
		}
	}

	public function remove(Request $request)
	{
		$sql = "DELETE FROM wishlists WHERE user_id = ? AND product_id = ?";
		$conn = $this->dbConnService->getDbConn();
		$pstm = $conn->prepare($sql);
		$productId = $request->input(key: 'product_id');

		if (empty($productId)) {
			return response()->json(['success' => false, 'messages' => 'Product ID is required'], 400);
		}

		try {
			$user = Auth::user();

			if (isset($user)) {
				$userId = $user->getKey();
				$pstm->bind_param("is", $userId, $productId);
				$pstm->execute();
				Log::info('User ' . $userId . ' removed product ' . $productId . ' from wishlist');
				return response()->json(['success' => true]);
			} else {
				return response()->json(['success' => false], 401);
			}
		} catch (\Exception $e) {
			Log::error('Error removing product from wishlist: ' . $e->getMessage());
			return response()->json(['success' => false], status: 500);
		}
	}
}