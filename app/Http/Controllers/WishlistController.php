<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class WishlistController extends Controller
{
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
		}
	}

	public function remove(Request $request)
	{
		try {
			$product_id = $request->input(key: 'product');
			$user = Auth::user();

			if ($user) {
				$user->wishlist()->detach($product_id);
				Log::info('User ' . $user->user_id . ' removed product ' . $product_id . ' from wishlist');
				return response()->json(['success' => true]);
			} else {
				return response()->json(['success' => false], 401);
			}
		} catch (\Exception $e) {
			Log::error('Error removing product from wishlist: ' . $e->getMessage());
		}
	}
}