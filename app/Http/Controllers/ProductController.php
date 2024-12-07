<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use Log;

class ProductController extends Controller
{

	public function show(Request $request)
	{
		$productId = 'CCNPLT0000';
		$productAttributes = [
			'TÊN KHOA HỌC' => 'Dracaena fragrans',
			'TÊN THÔNG THƯỜNG' => 'Phát tài bộ - Thiết Mộc lan',
			'QUY CÁCH SẢN PHẨM' => [
				'Kích thước chậu: 30x30cm (DxC)',
				'Chiều cao tổng: 120 - 130 cm'
			],
			'ĐỘ KHÔ' => 'Dễ chăm sóc',
			'YÊU CẦU ÁNH SÁNG' => 'Nắng tán xả, chịu được năng trực tiếp',
			'NHU CẦU NƯỚC' => 'Tưới nước 2 - 3 lần/tuần'
		];

		$relatedProducts = [];
		$bannerImgSrc = 'https://mowgarden.com/wp-content/uploads/2023/07/cay-phat-tai-bo-mowgarden.jpg';
		$productImgs = [
			'https://mowgarden.com/wp-content/uploads/2023/07/cay-phat-tai-bo-mowgarden.jpg',
			'https://mowgarden.com/wp-content/uploads/2023/07/cay-phat-tai-bo-mowgarden-02-800x800.jpg',
		];
		$productCategories = ['Cây cảnh', 'Cây văn phòng', 'Cây phong thủy'];

		$relatedProducts = [
			(object) [
				'title' => 'Cây Phong Thủy A',
				'price' => '650.000₫',
				'imgSrc' => 'https://mowgarden.com/wp-content/uploads/2023/07/cay-phat-tai-bo-mowgarden.jpg'
			],
			(object) [
				'title' => 'Cây Văn Phòng B',
				'price' => '450.000₫',
				'imgSrc' => 'https://mowgarden.com/wp-content/uploads/2023/07/cay-phat-tai-bo-mowgarden.jpg'
			],
			(object) [
				'title' => 'Cây Cảnh C',
				'price' => '550.000₫',
				'imgSrc' => 'https://mowgarden.com/wp-content/uploads/2023/07/cay-phat-tai-bo-mowgarden.jpg'
			],
			(object) [
				'title' => 'Cây Phát Tài D',
				'price' => '850.000₫',
				'imgSrc' => 'https://mowgarden.com/wp-content/uploads/2023/07/cay-phat-tai-bo-mowgarden.jpg'
			],
			(object) [
				'title' => 'Cây Sen Đá E',
				'price' => '250.000₫',
				'imgSrc' => 'https://mowgarden.com/wp-content/uploads/2023/07/cay-phat-tai-bo-mowgarden.jpg'
			],
			(object) [
				'title' => 'Cây Xương Rồng F',
				'price' => '350.000₫',
				'imgSrc' => 'https://mowgarden.com/wp-content/uploads/2023/07/cay-phat-tai-bo-mowgarden.jpg'
			]
		];

		$product = Product::find($productId);

		$isWishlisted = false;
		if (Auth::check()) {
			$user = Auth::user();
			$isWishlisted = $user->wishlist()
				->wherePivot('product_id', $productId)
				->exists();
		}

		Log::info('User ' . 'already wishlisted' . ' product ' . $productId . ': ' . $isWishlisted);

		return view('product.details', compact(
			'productId',
			'productAttributes',
			'relatedProducts',
			'bannerImgSrc',
			'productImgs',
			'productCategories',
			'relatedProducts',
			'product',
			'isWishlisted',
		));
	}
}
