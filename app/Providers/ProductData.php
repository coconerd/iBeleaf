<?php
namespace App\Providers;
use App\Providers\DBConnService;

class ProductData {
  protected static DBConnService $dbConnService;

  private static function removeVietnameseAccents($str) {
    $accents = [
        'a' => ['à', 'á', 'ạ', 'ả', 'ã', 'â', 'ầ', 'ấ', 'ậ', 'ẩ', 'ẫ', 'ă', 'ằ', 'ắ', 'ặ', 'ẳ', 'ẵ', 'À', 'Á', 'Ạ', 'Ả', 'Ã', 'Â', 'Ầ', 'Ấ', 'Ậ', 'Ẩ', 'Ẫ', 'Ă', 'Ằ', 'Ắ', 'Ặ', 'Ẳ', 'Ẵ'],
        'e' => ['è', 'é', 'ẹ', 'ẻ', 'ẽ', 'ê', 'ề', 'ế', 'ệ', 'ể', 'ễ', 'È', 'É', 'Ẹ', 'Ẻ', 'Ẽ', 'Ê', 'Ề', 'Ế', 'Ệ', 'Ể', 'Ễ'],
        'i' => ['ì', 'í', 'ị', 'ỉ', 'ĩ', 'Ì', 'Í', 'Ị', 'Ỉ', 'Ĩ'],
        'o' => ['ò', 'ó', 'ọ', 'ỏ', 'õ', 'ô', 'ồ', 'ố', 'ộ', 'ổ', 'ỗ', 'ơ', 'ờ', 'ớ', 'ợ', 'ở', 'ỡ', 'Ò', 'Ó', 'Ọ', 'Ỏ', 'Õ', 'Ô', 'Ồ', 'Ố', 'Ộ', 'Ổ', 'Ỗ', 'Ơ', 'Ờ', 'Ớ', 'Ợ', 'Ở', 'Ỡ'],
        'u' => ['ù', 'ú', 'ụ', 'ủ', 'ũ', 'ư', 'ừ', 'ứ', 'ự', 'ử', 'ữ', 'Ù', 'Ú', 'Ụ', 'Ủ', 'Ũ', 'Ư', 'Ừ', 'Ứ', 'Ự', 'Ử', 'Ữ'],
        'y' => ['ỳ', 'ý', 'ỵ', 'ỷ', 'ỹ', 'Ỳ', 'Ý', 'Ỵ', 'Ỷ', 'Ỹ'],
        'd' => ['đ', 'Đ']
    ];

    foreach ($accents as $nonAccent => $accentedChars) {
        $str = str_replace($accentedChars, $nonAccent, $str);
    }

    return $str;
  } 

  private static function getOrderHtml($OrderBy) {
    switch ($OrderBy) {
      case "popularity":
        return " ORDER BY total_orders DESC ";
      case "rating":
        return " ORDER BY total_ratings DESC ";
      case "star":
        return " ORDER BY overall_stars DESC ";
      case "latest":
        return " ORDER BY created_at DESC ";
      case "price-asc":
        return " ORDER BY price ASC ";
      case "price-desc":
        return " ORDER BY price DESC ";
    }

    return "";
  }

  private static function getDiscountPrice($discount, $price) {
    return $price * (100 - $discount) / 100;
  }

  private static function processPrice($number) {
    $res = "";
    $str = (string)$number;
    while(strlen($str) >= 3) {
      if($res == "")
        $res = substr($str, -3);
      else
        $res = substr($str, -3).".".$res;

      $str = substr($str, 0, -3);
    }

    if($str != "")
      $res = $str.".".$res;

    return $res;
    return $str;
  }
  

  public static function convertCateHref($cateName) {
    $cateName = trim(strtolower($cateName));
    $cateName = str_replace(" ", "-", $cateName);
    $cateName = self::removeVietnameseAccents($cateName);
    return $cateName;
  }

  public static function getUrl($url_name = "") {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
    return $protocol."://".$_SERVER['HTTP_HOST'].'/'.$url_name;
  }

  public static function setDBConnService(DBConnService $dbConnService) {
    self::$dbConnService = $dbConnService;
    return self::$dbConnService;
  }

  public static function get($object) {
      // echo(self::$indexProduct);
      // echo("bbbb");
      // $_SESSION["number"] = $_SESSION["number"] + 10;
      return $object;
  }

  public static function getPlant($nameCate, $number = 8) {
    $conn = self::$dbConnService->getDBConn();

    if($nameCate === "Chậu Cây") {
      $sql = "
        SELECT * 
        FROM products 
        WHERE type = 2
        LIMIT ?;
      ";
      $pstm = $conn->prepare($sql);
      $pstm->bind_param("i", $number);
    }
    else {
      $sql = "
        SELECT p.* 
        FROM products AS p 
        JOIN (
            SELECT pc.product_id 
            FROM product_categories AS pc
            WHERE pc.category_id IN (
                SELECT c.category_id 
                FROM categories AS c 
                WHERE c.name = ?
            )
            LIMIT ?
        ) AS subquery ON p.product_id = subquery.product_id;
      ";
      $pstm = $conn->prepare($sql);
      $pstm->bind_param("si", $nameCate, $number);
    }
    $pstm->execute();
    $result = $pstm->get_result();
    
    $rows = [];
    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        $row['discount_price'] = self::getDiscountPrice($row['discount_percentage'], $row['price']);
        $row['discount_price'] = self::processPrice($row['discount_price']);
        $row['price'] = self::processPrice($row['price']);
        $rows[] = $row;
      }
    }
    $pstm->close();
    return $rows;
  }

  public static function getImagePlant($product_id) {
    $conn = self::$dbConnService->getDBConn();
    $sql = "
      SELECT * 
      FROM product_images 
      WHERE product_id = ?;
    ";
    $pstm = $conn->prepare($sql);
    $pstm->bind_param("s", $product_id);
    $pstm->execute();
    $result = $pstm->get_result();
    $rows = [];
    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
      }
    }
    else {
      $rows[] = array(
        "product_image_id" => "-1",
        "product_image_url" => self::getUrl('images/main/product-introduce/non_plant.png')
      );
    }
    $pstm->close();
    return $rows;
  }

  public static function checkHref(&$cateName, &$cate_id, &$bg_url, &$des){
    $conn = self::$dbConnService->getDBConn();
    $sql = "
      SELECT * 
      FROM 
      categories;
    ";
    $pstm = $conn->prepare($sql);
    $pstm->execute();
    $result = $pstm->get_result();
    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        if(self::convertCateHref($row['name']) === $cateName) {
          $cateName = trim($row['name']);
          $cate_id = $row['category_id'];
          $bg_url = $row['background_url'];
          $des = $row['description'];
          $pstm->close();
          return true;
        } 
      }
    }

    $pstm->close();
    return false;
  }

  public static function check_GetListProduct(&$cate_id, $page, &$type_product, &$list_product, $max_product = 40, $OrderBy = "nan", $minPrice, $maxPrice, $filterText){
    if($page <= 0) 
      return false;

    $OrderBy = self::getOrderHtml($OrderBy);

    $offset = ($page - 1) * $max_product;

    $conn = self::$dbConnService->getDBConn();
    if($cate_id == "search") {
      $sql = "
        SELECT p.* 
        FROM products AS p 
        WHERE ".$minPrice." <= price and price <= ".$maxPrice."
        ".
        $OrderBy
        ." 
        
      ";
      $pstm = $conn->prepare($sql);
      // $pstm->bind_param("ii", $max_product, $offset);
    }
    else
    if($cate_id == "chau-cay" || $cate_id == "cay-canh") {
      if($cate_id == "chau-cay")
        $type = 2;
      else
        $type = 1;

      $sql = "
        SELECT p.* 
        FROM products AS p 
        WHERE type = ".$type." and ".$minPrice." <= price and price <= ".$maxPrice."
        ".
        $OrderBy
        ." 
        LIMIT ? OFFSET ?;
      ";
      $pstm = $conn->prepare($sql);
      $pstm->bind_param("ii", $max_product, $offset);
    }
    else {
      $sql = "
        SELECT p.* 
        FROM products AS p JOIN (
          SELECT distinct(pc.product_id) 
            FROM product_categories AS pc 
            WHERE pc.category_id = ?
        ) AS subquery 
        ON p.product_id = subquery.product_id 
        WHERE ".$minPrice." <= price and price <= ".$maxPrice."
        ".
        $OrderBy
        ." 
        LIMIT ? OFFSET ?;
      ";
      $pstm = $conn->prepare($sql);
      $pstm->bind_param("sii", $cate_id, $max_product, $offset);
    }
    $pstm->execute();
    $result = $pstm->get_result();
    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        // check in filterText
        if(self::checkFilterText($filterText, $row)) {
          switch ($row['type']) {
            case 1:
              if($type_product != "Uncategorized" && $type_product != "Sản Phẩm")
                $type_product = "Cây Cảnh";
              $row['type'] = "Cây Cảnh";
              break;
            case 2:
              if($type_product != "Uncategorized" && $type_product != "Sản Phẩm")
                $type_product = "Chậu Cây";
              $row['type'] = "Chậu Cây";
              break;
          }
          $row['list_image'] = self::getImagePlant($row['product_id']);
          $row['discount_price'] = self::getDiscountPrice($row['discount_percentage'], $row['price']);
          $row['discount_price'] = self::processPrice($row['discount_price']);
          $row['price'] = self::processPrice($row['price']);
          
          $list_product[] = $row;
        }
      }
    }

    $pstm->close();
    return true;
  }
  
  private static function checkFilterText($filterText, $row) {
    if($filterText == "")
      return true;

    $res = strpos(strtolower($row["short_description"]), strtolower($filterText));
    if($res !== false)
      return true;

    $res = strpos(strtolower($row["code"]), strtolower($filterText));
    if($res !== false)
      return true;

    $res = strpos(strtolower($row["product_id"]), strtolower($filterText));
    if($res !== false)
      return true;

    return false;
  }

  public static function getMaxPage(&$cate_id, $max_product = 40) {
    $conn = self::$dbConnService->getDBConn();
    $sql = "
      SELECT COUNT(distinct pc.product_id) AS AMOUNT
      FROM product_categories AS pc 
      WHERE pc.category_id = ?;
    ";
    $pstm = $conn->prepare($sql);
    $pstm->bind_param("s", $cate_id);
    $pstm->execute();
    $result = $pstm->get_result();
    $row = $result->fetch_assoc();
    $max_page = max(floor(($row['AMOUNT'] - 1) / $max_product) + 1, 1);
    $pstm->close();
    return $max_page;
  }
  
  public static function getNumberProduct(&$cate_id, $minPrice, $maxPrice){
    $conn = self::$dbConnService->getDBConn();
    if($cate_id == "search") {
      $sql = "
        SELECT COUNT(*) as num
        FROM products
        WHERE ".$minPrice." <= price and price <= ".$maxPrice.";
      ";
      $pstm = $conn->prepare($sql);       
    }
    else
    if($cate_id == "chau-cay" || $cate_id == "cay-canh") {
      if($cate_id == "chau-cay")
        $type = 2;
      else
        $type = 1;

      $sql = "
        SELECT COUNT(*) as num
        FROM products 
        WHERE type = ".$type." and ".$minPrice." <= price and price <= ".$maxPrice.";
      ";
      $pstm = $conn->prepare($sql);        
    }
    else {
      $sql = "
        SELECT COUNT(*) as num
        FROM products AS p 
        JOIN (
            SELECT DISTINCT pc.product_id 
            FROM product_categories AS pc 
            WHERE pc.category_id = ?
        ) AS subquery 
        ON p.product_id = subquery.product_id
        WHERE ".$minPrice." <= price and price <= ".$maxPrice.";
      ";
      $pstm = $conn->prepare($sql);
      $pstm->bind_param("s", $cate_id);
    }
    $pstm->execute();
    $result = $pstm->get_result();
    $row = $result->fetch_assoc();
    $number_result = $row['num'];
    $pstm->close();
    return $number_result;
  }

  public static function getMainCategory() {
    return array("Cây Cảnh"=>self::getUrl(self::convertCateHref("Cây Cảnh")), 
      "Chậu Cây"=>self::getUrl(self::convertCateHref("Chậu Cây")), 
      "Uncategorized"=>self::getUrl(self::convertCateHref("Uncategorized")));
  }

  public static function getConCategory($category) {
    $list_conCate = [];

    if($category == "Uncategorized")
      return $list_conCate;

    if($category == "Chậu Cây")
      $type = 2;
    else
      $type = 1;

    $conn = self::$dbConnService->getDBConn();
    $sql = "
      select c.name from categories as c
      join
        (
        select DISTINCT pc1.category_id from product_categories as pc1
        join
          (
          select product_id from products
          where type = ".$type."
          ) as pc0
        on pc0.product_id = pc1.product_id
        ) as c3
      on c3.category_id = c.category_id
      where c.name != 'Uncategorized';
    ";
    $pstm = $conn->prepare($sql);
    $pstm->execute();
    $result = $pstm->get_result();
    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        $row['url_cate'] = self::getUrl(self::convertCateHref($row["name"]));
        $list_conCate[] = $row;
      }
    }
    $pstm->close();
    
    return $list_conCate;
  }

  // get all product
  public static function getProducts() {
    $list_product = [];

    $conn = self::$dbConnService->getDBConn();
    $sql = "
      SELECT *, (
        select pi.product_image_url 
        from product_images as pi 
        where pi.product_id = p.product_id 
        limit 1 
        ) as image_url
      FROM products as p;
    ";
    $pstm = $conn->prepare($sql);
    $pstm->execute();
    $result = $pstm->get_result();
    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        $row['discount_price'] = self::getDiscountPrice($row['discount_percentage'], $row['price']);
        $row['discount_price'] = self::processPrice($row['discount_price']);
        $row['price'] = self::processPrice($row['price']);
        $list_product[] = $row;
      }
    }
    $pstm->close();
    
    return $list_product;
  }
}