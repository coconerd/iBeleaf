<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Fill empty 'code' values with unique 7-character alphanumeric strings
        $productsWithEmptyCode = Product::whereNull('code')->orWhere('code', '')->get();
        foreach ($productsWithEmptyCode as $product) {
            do {
                $code = Str::upper(Str::random(7));
            } while (Product::where('code', $code)->exists());
            $product->code = $code;
            $product->save();
        }

		// Fill empty product names
		$productsWithEmptyValues = Product::whereNull('name')->orWhere('name', '')->get();
		foreach ($productsWithEmptyValues as $product) {
			$words = explode(' ', $product->short_description);
			array_pop($words);
			$product->name = implode(' ', $words);
			$product->save();
		}

        // Randomly set 'discount_percentage' for 1/4 of the products
        $products = Product::all();
        $productsToUpdate = $products->random(floor($products->count() / 4));
        foreach ($productsToUpdate as $product) {
            $product->discount_percentage = rand(0, 30);
            $product->save();
        }
    }
}