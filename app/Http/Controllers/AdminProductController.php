<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\ProductImage;
use Log;

class AdminProductController extends Controller
{
	public function showProductsPage(Request $request)
	{
		// Get filter parameters
		$category = $request->input('category');
		$stock = $request->input('stock');
		$sort = $request->input('sort', 'created_at');
		$direction = $request->input('direction', 'desc');

		// Base query
		$query = Product::with(['product_images', 'categories']);

		// Apply category filter
		if ($category) {
			$query->whereHas('categories', function ($q) use ($category) {
				$q->where('categories.category_id', $category);
			});
		}

		// Apply stock filter
		if ($stock) {
			switch ($stock) {
				case 'in_stock':
					$query->where('stock_quantity', '>', 10);
					break;
				case 'low_stock':
					$query->whereBetween('stock_quantity', [1, 10]);
					break;
				case 'out_of_stock':
					$query->where('stock_quantity', 0);
					break;
			}
		}

		// Apply search if provided
		if ($request->filled('search')) {
			$searchTerm = $request->input('search');
			$searchType = $request->input('type', 'code');

			$query->where(function ($q) use ($searchTerm, $searchType) {
				if (in_array($searchType, ['code', 'name', 'short_description'])) {
					$q->where($searchType, 'LIKE', "%{$searchTerm}%");
				}
			});
		}

		// Apply sorting
		switch ($sort) {
			case 'code':
				$query->orderBy('code', $direction);
				break;
			case 'name':
				$query->orderBy('short_description', $direction);
				break;
			case 'stock':
				$query->orderBy('stock_quantity', $direction);
				break;
			case 'sold':
				$query->orderBy('total_orders', $direction);
				break;
			case 'rating':
				$query->orderBy('overall_stars', $direction);
				break;
			case 'price':
				$query->orderBy('price', $direction);
				break;
			default:
				$query->orderBy('created_at', $direction);
		}

		// Get paginated results
		$products = $query->paginate(10)->withQueryString();

		// Get all categories for filter dropdown
		$categories = Category::all();

		return view('admin.products.index', compact(
			'products',
			'categories'
		));
	}

	public function getDetails($product_id)
	{
		$product = Product::with(['product_images', 'categories'])
			->findOrFail($product_id);

		// $allCategories = Category::all();

		Log::debug('Product details: ' . json_encode($product));

		return response()->json([
			'success' => true,
			'product' => $product,
			// 'allCategories' => $allCategories,
		]);
	}

	public function updateField(Request $request, $product_id)
	{
		$request->validate([
			'field' => 'required|string',
			'value' => 'required'
		]);

		$product = Product::findOrFail($product_id);
		$field = $request->input('field');
		$value = $request->input('value');

		// Validate and sanitize the field
		switch ($field) {
			case 'detailed_description':
				$value = Str::limit($value, 1000); // Limit description length
				break;
			case 'stock_quantity':
				$value = max(0, intval($value)); // Ensure non-negative integer
				break;
			case 'price':
				$value = max(0, intval($value)); // Ensure non-negative integer
				break;
			default:
				return response()->json([
					'success' => false,
					'message' => 'Invalid field'
				], 400);
		}

		try {
			$product->update([$field => $value]);

			return response()->json([
				'success' => true,
				'message' => 'Updated successfully'
			]);
		} catch (\Exception $e) {
			return response()->json([
				'success' => false,
				'message' => 'Update failed'
			], 500);
		}
	}

	public function update(Request $request, $product_id)
	{
		try {
			$request->validate([
				'name' => 'nullable|string',
				'short_description' => 'nullable|string',
				'detailed_description' => 'nullable|string',
				'discount_percentage' => 'numeric',
				'price' => 'required|numeric',
				'stock_quantity' => 'required|integer',
				'categories' => 'string',
				// 'categories.*' => 'string|exists:categories,category_id'
			]);
		} catch (\Exception $e) {
			Log::error('AdminProductController@update: validation error: ' . $e->getMessage());
			Log::debug('Request data: ', ['data' => $request->all()]);
			return redirect()->back()->with('error', 'invalid_input');
		}

		$product = Product::findOrFail($product_id);
		$data = $request->all();
		Log::debug('Request data ' . json_encode($data));

		DB::beginTransaction();
		try {
			// Update product
			$product->update([
				'name' => $data['name'],
				'short_description' => $data['short_description'],
				'detailed_description' => $data['detailed_description'],
				'discount_percentage' => $data['discount_percentage'] ?? 0,
				'price' => $data['price'],
				'stock_quantity' => $data['stock_quantity'],
				'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
			]);

			// Update categories
			if (isset($data['categories'])) {
				$categories = explode(',', $data['categories']);
				$product->categories()->sync($categories);
			}

			// Update images
			if ($request->hasFile('images')) {
				Log::debug('Request has images');
				$images = $request->file('images');
				$product->product_images()->delete();
				Log::debug('Deleted old images');
				foreach ($images as $image) {
					$path = $image->store('products', 'public');
					ProductImage::create([
						'product_id' => $product->product_id,
						'product_image_url' => '/storage/' . $path,
						'image_type' => 1
					]);
				}
				Log::debug('Added new images');
			}

			DB::commit();
			return redirect()->back()->with('success', 'Cập nhật sản phẩm thành công');
		} catch (\Exception $e) {
			Log::error('AdminProductController@update: update error: ' . $e->getMessage());
			DB::rollBack();
			return redirect()->back()->withErrors( 'Có lỗi khi cập nhật sản phẩm.');
		}
	}
}