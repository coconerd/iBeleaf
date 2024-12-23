<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Providers\DBConnService;
use App\Providers\ProductData;

// require_once base_path('core/abc.php');

class FunctionController extends Controller
{
    public function __construct(DBConnService $dbConnService)
	{
        ProductData::setDBConnService($dbConnService);
	}

    public function searchProducts(Request $request) {
        $products = ProductData::getProducts();
        // echo $list_product;
        return response()->json($products);
    }
}
