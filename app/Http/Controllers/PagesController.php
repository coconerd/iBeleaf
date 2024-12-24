<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Providers\DBConnService;
use App\Providers\ProductData;

// require_once base_path('core/abc.php');

class PagesController extends Controller
{
    private $title = "Plant Paradise";
    private $author = "CocoNerd";

    public function __construct(DBConnService $dbConnService)
	{
        ProductData::setDBConnService($dbConnService);
	}

    private function checkVariable(&$bg_url, &$des, &$type_product) {
        // gán lại giá trị khi background hoặc des nó bị không có dữ lịue
        if($type_product == "Uncategorized") {
            if($bg_url == "")
            $bg_url = ProductData::getUrl("images/main/categories/uncategorized.jpg");
            if($des == "")
                $des = "Những sản phẩm không được phân loại";
        }
        else
        if($type_product == "Sản Phẩm") {
            if($bg_url == "")
                $bg_url = ProductData::getUrl("images/main/categories/plantSearching.jpg");
            if($des == "")
                $des = "Những sản phẩm đã được tìm kiếm";
        }
        else
        if($type_product == "Chậu Cây") {
            if($bg_url == "")
                $bg_url = ProductData::getUrl("images/main/product-introduce/background-pot.png");
            if($des == "")
                $des = "Giúp không gian của bạn thêm sang trọng và tinh tế với những sản phẩm chậu cây đa dạng. Thật tuyệt vời khi mang đến cho bạn những phút giây thư giãn bên cạnh những chậu cây được thiết kế đẹp mắt và chất lượng cao.";
        }
        else {
            if($bg_url == "")
                $bg_url = "https://mowgarden.com/wp-content/uploads/2021/07/danh-muc-canh-trong-trong-nha.jpg";
            if($des == "")
                $des = "Giúp cho ngôi nhà của bạn thêm tươi mới và xanh với những sản phẩm cây trong nhà. Thật tuyệt vời mang đến cho bạn những phút giây thư giãn bên canh những sản phẩm cây xanh chất lượng.";
        }
    }

    private function checkImageSrc(&$url) {
        $context = stream_context_create(['http' => ['timeout' => 5]]); // Thiết lập timeout
        $result = @file_get_contents($url,
         false, 
         $context);
    
        if ($result === false) {
            $url = ProductData::getUrl("images/main/error_plant.jpg");
            echo "COO";
        } 
    }

    public function indexMain() {
        $title = $this->title;
        $author = $this->author;
        $description = "this is a main page";
        // $caytrongnha = ProductData::getPlant("Cây Trong Nhà");
        // $cayngoaitroi = ProductData::getPlant("Cây Ngoài Trời");
        // $chaucay = ProductData::getPlant("Chậu Cây");
        // , "caytrongnha", "cayngoaitroi", "chaucay"
        return view ("products.index", compact("title", "author", "description"));
    }

    public function categoryMain($category, $page = null) {
        $firstHref = $category;
        $page = $page ?? 1;
        $max_product = 36;
        $max_page = 1;
        $bg_url = "";
        $des = "";
        $type_product = "";
        $number_result = 0;
        $list_product = [];
        $OrderBy = "nan";
        $minPrice = 0;
        $maxPrice = 100000000;
        $filterText = "";

        if(isset($_GET["min-price"]))
            $minPrice = $_GET["min-price"];

        if(isset($_GET["max-price"]))
            $maxPrice = $_GET["max-price"];

        if(isset($_GET["orderby"]))
            $OrderBy = $_GET["orderby"];

        if(isset($_GET["name"]) && ($_GET["name"] != "")) {
            $filterText = $_GET["name"];
        }
    
        if($category == "uncategorized")
            $type_product = "Uncategorized";

        if($category == "search") {
            $type_product = "Sản Phẩm";

            if(!ProductData::check_GetListProduct($category, $page, $type_product, $list_product, $max_product, $OrderBy, $minPrice, $maxPrice, $filterText)) 
                abort(404, 'Page not found');

            $number_result = count($list_product);
            $max_page = max(floor(($number_result - 1) / $max_product) + 1, 1);

            if($page > $max_page)
                abort(404, 'Out Of Max Page not found');
            
            $offset = ($page - 1) * $max_product;
            $list_product = array_slice($list_product, $offset, $max_product);

            $category = "Sản Phẩm";
        }
        else
        if($category == "chau-cay" || $category == "cay-canh") {
            if(!ProductData::check_GetListProduct($category, $page, $type_product, $list_product, $max_product, $OrderBy, $minPrice, $maxPrice, $filterText))
                abort(404, 'Page not found');

            $number_result = ProductData::getNumberProduct($category, $minPrice, $maxPrice);
            $max_page = max(floor(($number_result - 1) / $max_product) + 1, 1);

            if($page > $max_page)
                abort(404, 'Out Of Max Page not found');
            
            if($category == "chau-cay")
                $category = "Chậu Cây";
            else
                $category = "Cây Cảnh";
        }
        else {
            $cate_id = "";
            
            if(!ProductData::checkHref($category, $cate_id, $bg_url, $des)) 
                abort(404, 'Category not found');

            if(!ProductData::check_GetListProduct($cate_id, $page, $type_product, $list_product, $max_product, $OrderBy, $minPrice, $maxPrice, $filterText))
                abort(404, 'Page not found');
            
            $number_result = ProductData::getNumberProduct($cate_id, $minPrice, $maxPrice);
            $max_page = max(floor(($number_result - 1) / $max_product) + 1, 1);

            if($page > $max_page)
                abort(404, 'Out Of Max Page not found');
        }

        // foreach ($list_product as $product) {
        //     foreach ($product['list_image'] as $img) {
        //         self::checkImageSrc($img['product_image_url']);
        //     }  
        // }

        $title = $category." • ".$this->title;
        $author = $this->author;
        $description = "this is a category page";
        $url_page = ProductData::getUrl(ProductData::convertCateHref($firstHref)."/page/");

        self::checkVariable($bg_url, $des, $type_product);

        $list_mainCate = ProductData::getMainCategory();  
        $list_conCate = ProductData::getConCategory($type_product);

        return view ("products.category", compact("title", "author", "description", "category", "bg_url", "des", "type_product", "list_product", "max_page", "page", "url_page", "max_product", "number_result", "list_mainCate", "list_conCate"));
    }

    public function increment(Request $request) {
        $number = $request->query('number', 0);
        $result = ProductData::getImagePlant($number);  
        return response()->json($result);
    }

    public function getCategories(Request $request) {
        $category = $request->query('category', 0);
        $list_mainCate = ProductData::getMainCategory();  
        $list_conCate = ProductData::getConCategory($category);  
        return response()->json($list_mainCate, $list_conCate);
    }
}
