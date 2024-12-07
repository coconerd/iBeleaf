
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function add(Request $request)
    {
        $productId = $request->input('product_id');

        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Not authenticated']);
        }

        $user = Auth::user();
        $user->wishlist()->attach($productId);

        return response()->json(['success' => true]);
    }
}