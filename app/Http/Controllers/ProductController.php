<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Providers\DBConnService;
use Log;

class ProductController extends Controller
{
	protected DBConnService $dbConnService;

	public function __construct(DBConnService $dbConnService)
	{
		$this->dbConnService = $dbConnService;
	}

	public function show($product_id)
	{
		$productId = $product_id;

		if (!isset($productId)) {
			return abort(404);
		}

		// Fetch product
		try {
			$product = Product::with(
				'categories'
			)->with([
						'product_images' => function ($query) {
							$query->where('image_type', '=', 1)->select('product_image_url', 'product_images.product_id');
						}
					])->findOrFail($productId);

			Log::debug('Product: ' . json_encode($product));
		} catch (\Exception $e) {
			Log::error('Could not find product with product_id ' . $productId . ': ' . $e->getMessage());
			return abort(404);
		}

		$productCategories = $product->categories->pluck('name')->toArray();
		$productImgs = $product->product_images->pluck('product_image_url')->toArray();

		$isWishlisted = false;
		$user = null;
		$wishlistedIds = [];
		if (Auth::check()) {
			$user = Auth::user();
			// $isWishlisted = $user->wishlist()
			// 	->wherePivot('product_id', $product_id)
			// 	->exists();
			$wishlistedIds = Auth::user()->wishlist()
				->pluck('products.product_id')
				->toArray();
			$product->is_wishlisted = in_array($product_id, $wishlistedIds);
		}

		Log::info('User ' . 'already wishlisted' . ' product ' . $product_id . ': ' . $isWishlisted);

		// Fetch product attributes
		$conn = $this->dbConnService->getDbConn();
		$sql = "SELECT A.name, PA.value
			FROM 
			(
				SELECT value, product_id, attribute_id from product_attributes WHERE product_id = ?
			) AS PA
			JOIN
			(
				SELECT name, attribute_id from attributes
			) AS A
			ON A.attribute_id = PA.attribute_id
		";
		$pstm = $conn->prepare($sql);
		$pstm->bind_param('s', $productId);

		$productAttributes = [];

		try {
			$pstm->execute();
			$res = $pstm->get_result();
			$productAttributes = $res->fetch_all(mode: MYSQLI_ASSOC);
			Log::debug('Product attributes: ' . json_encode($productAttributes));
			$pstm->close();
		} catch (\Exception $e) {
			Log::error('Error fetching product attributes: ' . $e->getMessage());
			$pstm->close();
			return abort(500);
		}

		// Transform product attributes to associative array
		$productAttributes = array_combine(
			array_map('trim', array_column($productAttributes, column_key: 'name')),
			array_map('trim', array_column($productAttributes, 'value'))
		);

		// Attributes post-processing
		foreach ($productAttributes as $key => $value) {
			if ($key === 'QUY CÁCH SẢN PHẨM') {
				$productAttributes[$key] = array_map(
					'trim',
					array_slice(explode('•', string: $value), 1)
				);
				Log::debug('Product attribute post processing: ' . json_encode($productAttributes[$key]));
			}
		}

		// Fetch related products based on same categories
		$relatedProducts = Product::whereHas('categories', function ($query) use ($product) {
			$query->whereIn('categories.category_id', $product->categories->pluck('category_id'));
		})->where(
				'product_id',
				'!=',
				$product->product_id
			)->with([
					'product_images' => function ($query) {
						$query->where('image_type', '=', 1)->select('product_image_url', 'product_id');
					}
				])->take(18)->get()->map(function ($product) use ($wishlistedIds) {
					return (object) [
						'product_id' => $product->product_id,
						'title' => $product->name,
						'price' => number_format($product->price, 0, '.', ',') . '₫',
						'img_url' => $product->product_images->first()->product_image_url,
						'discount_percentage' => $product->discount_percentage,
						'is_wishlisted' => in_array($product->product_id, $wishlistedIds)
					];
				});

		$discountedProducts = Product::where('discount_percentage', '>', 0)
			->with([
				'product_images' => function ($query) {
					$query->where('image_type', '=', 1)->select('product_image_url', 'product_id');
				}
			])
			->take(10)
			->get()
			->map(function ($product) use ($wishlistedIds) {
				return (object) [
					'product_id' => $product->product_id,
					'title' => $product->name,
					'price' => number_format($product->price, 0, '.', ',') . '₫',
					'discount_percentage' => $product->discount_percentage,
					'img_url' => $product->product_images->first()->product_image_url,
					'is_wishlisted' => in_array($product->product_id, $wishlistedIds)
				];
			});

		return view('product.details', compact(
			'productId',
			'productAttributes',
			'relatedProducts',
			'discountedProducts',
			'productImgs',
			'productCategories',
			'relatedProducts',
			'product',
			'isWishlisted',
		));
	}
}
