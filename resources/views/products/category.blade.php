<?php	
	session_start();
	use App\Providers\ProductData;

    $numberSlider = 5;
    $sizeSlider = 2;

    $minValue = 0;
    $maxValue = 7500000; 

    $step = 50000;
    $minRangeValue = 0;
    $maxRangeValue = $maxValue;
    if (isset($_GET["min-price"]))
        $minRangeValue = $_GET["min-price"];
    if (isset($_GET["max-price"]))
        $maxRangeValue = $_GET["max-price"];

    $nameFilter = "";
    if (isset($_GET["name"]))
        $nameFilter = $_GET["name"];
?>

@extends("layouts.layout")
@isset($title)
	@section("title", "$title")
@endisset
@isset($author)
	@section("author", "$author")
@endisset
@isset($author)
	@section("description", "$description")
@endisset

@section("style")
    <link rel="stylesheet" href="{{asset('css/products/product-introduce.css')}}">
    <link rel="stylesheet" href="{{asset('css/products/category.css')}}">
    <link rel="stylesheet" href="{{asset('css/products/sidebar.css')}}">
@endsection

@section("content")
    <div class="cate-background">

        <img src="{{ $bg_url }}" alt="cate-background">
        <div class="color-layer"></div>
        <div class="text-cate">
            <h1> {{ mb_strtoupper($category, 'UTF-8') }} </h1>
            <p> {{ $des }} </p>
        </div>
    </div>

    <div class="cate-body">
        <form action="{{ $url_page.'1' }}" method="GET" class="head-contain">
            <div class="box-category">
                <div class="list-category">
                    <a href="<?= ProductData::getUrl() ?>" class="show-category">Trang chủ</a>
                    <span class="separator-category"></span>
                    <h1> <?= $category ?> </h1>
                </div>

                <div class="select-category">
                    <div onclick="$('.background-sidebar').css('display', 'flex');">
                        <span class="icon-equalizer"></span>
                        <strong>chọn danh mục</strong>
                    </div>
                </div>
            </div>

            <div class="background-sidebar" style="display: none;">
                <div class="sidebar-category">
                    {{-- lọc giá sản phẩm --}}
                    <div class="sidebar-content">
                        <span>lọc giá sản phẩm</span>
                    </div>
                    <span class="sidebar-separator"></span>

                    <div class="filter-by-price">
                        <div class="price-input">
                            <div class="field">
                                <span>Min</span>
                                <input type="number" class="input-min" value="{{ $minRangeValue }}" placeholder="{{ $minValue }}" name="min-price" step="{{ $step }}">
                            </div>
                            <div class="spe-price-input"></div>
                            <div class="field">
                                <span>Max</span>
                                <input type="number" class="input-max" value="{{ $maxRangeValue }}" placeholder="{{ $maxValue }}" name="max-price" step="{{ $step }}">
                            </div>
                        </div>

                        <div class="slider-price">
                            <div class="progress-slider"></div>

                            <input type="range" class="min-range" min="{{ $minValue }}" max="{{ $maxValue }}" value="{{ $minRangeValue }}">
                            <input type="range" class="max-range" min="{{ $minValue }}" max="{{ $maxValue }}" value="{{ $maxRangeValue }}">
                        </div>

                        <div class="box-slide">
                            <button class="button-slide" onclick="this.form.submit()">Lọc</button>
                        </div>

                        {{-- thao tac slider --}}
                        <script src="{{ asset('js/ui/slider.js')}}"></script>
                    </div>

                    {{-- danh mục --}}
                    <div class="sidebar-content">
                        <span>danh mục</span>
                    </div>
                    <span class="sidebar-separator"></span>
                    
                    <div class="menu-category">
        
                        <?php
                            foreach($list_mainCate as $mainCate => $urlCate) {
                                $selected = "";
                                if ($mainCate == $type_product)
                                    $selected = "class='selected'";

                                echo '<div class="row-menu">';
                                echo '  <div class="header-row">';
                                echo '      <a href="'.$urlCate.'" '.$selected.'>'.$mainCate.'</a>';
                                echo '      <button class="toggle" onclick="togglearrow(event);">';
                                echo '          <i class=""></i>';
                                echo '      </button>';
                                echo '  </div>';
                                
                                if(count($list_conCate) > 0 && $mainCate == $type_product) {
                                    echo '<ul class="mega-links">';
                                    
                                    foreach ($list_conCate as $conCate) {
                                        $selected = "";
                                        if ($conCate['name'] == $category)
                                            $selected = "class='selected'";

                                        echo '<li><a href="'.$conCate['url_cate'].'" '.$selected.'>'.$conCate['name'].'</a></li>';
                                    }
                                    echo '</ul>';
                                }
                                echo '</div>'; 
                            }

                        ?>
                    </div>

                    {{-- thao tac slider --}}
                    <script src="{{ asset('js/ui/list_categories.js')}}"></script>
                </div>

                <div class="disable-slide-bar" onclick="$(this).closest('.background-sidebar').css('display', 'none');"></div>
            </div>

            
            <div class="type-category">{{ $type_product }}</div>

            <div class="filter-category">
                <span class="showing-result">
                    <?php
                        $firstResult = min(($page - 1) * $max_product + 1, $number_result);
                        $lastResult = min($page * $max_product, $number_result);

                        echo "Đang hiển thị ";
                        echo "<span class='hightlight-showing-result'>";
                        echo $firstResult."-".$lastResult;
                        echo "</span>";
                        echo " trên ";
                        echo "<span class='hightlight-showing-result'>";
                        echo $number_result;
                        echo "</span>";
                        echo " kết quả"; 
                    ?>
                </span>

                <select class="order-form" name="orderby" onchange="this.form.submit()">
                    <?php
                        $list_option = array(
                            "popularity"=>"Nổi bật nhất", 
                            "rating"=>"Đánh giá cao nhất",
                            "star"=>"Số sao cao nhất",
                            "latest"=>"Gần đây nhất",
                            "price-asc"=>"Giá bán tăng dần",
                            "price-desc"=>"Giá bán giảm dần",
                        );  

                        $orderby = "nan";
                        if (isset($_GET["orderby"]))
                            $orderby = $_GET["orderby"];

                        foreach ($list_option as $value => $content) {
                            if ($value != $orderby)
                                echo '<option value="'.$value.'">'.$content.'</option>';
                            else
                                echo '<option value="'.$value.'" selected>'.$content.'</option>';
                        }
                    ?>
                </select>
            
            </div>
            <input type="text" style="display: none;" name="name" value="{{ $nameFilter }}">
        </form>

        <?php
            if($number_result == 0)
                echo '<div class="empty-product"> Hiện tại không có sản phẩm nào </div>';
        ?>

        <div class="product-container" id="cay-trong-nha-container">
            <?php
                $error_img = 'onerror="this.onerror=null; this.src=\''.asset('images/main/error_plant.jpg').'\';"';

                if($number_result != 0)
                    foreach ($list_product as $product) {
                        echo '<div class="nav-product">';
                        echo "<a class='nav-product-img' href='".ProductData::getUrl('product/'.$product['product_id'])."' id='".$product['product_id']."'>";
                        $first = "nope";
                        $isTwo = false; 
                        foreach ($product['list_image'] as $img) {
                            if($first == "nope") {
                                $first = '<img class="disable_image" src="'.$img['product_image_url'].'" '.$error_img.' alt="'.$product['name'].'" style="opacity: 0;">';
                                echo '<img class="enable_image" src="'.$img['product_image_url'].'" '.$error_img.' alt="'.$product['name'].'">';
                            }
                            else {
                                $isTwo = true;
                                echo '<img class="disable_image" src="'.$img['product_image_url'].'" '.$error_img.' alt="'.$product['name'].'" style="opacity: 0;">';
                                break;
                            }
                        }
                        if($isTwo == false && $first != "nope")
                            echo $first;

                        echo '</a>';
                        echo '<div class="nav-product-category">'.$product['type'].'</div>';
                        
                        if($product['discount_percentage'] > 0 && $product['discount_percentage'] <= 100)
                            echo '<div class="nav-product-discount"> <span> -'.$product['discount_percentage'].'% </span></div>';

                        echo '<div class="nav-product-name">' . $product['short_description'] . '</div>';

                        if($product["discount_percentage"] > 0)
                            echo '<div class="nav-product-price">' . $product['discount_price'] . '<span>₫</span> <span class="nav-product-price-discount">'.$product['price'].'₫</span> </div>';   
                        else
                            echo '<div class="nav-product-price">' . $product['price'] . '<span>₫</span> </div>';   

                        echo '</div>';

                        
                    }
            ?>
        </div>  

        <div class="page_slider">
            <?php
                if(isset($_SERVER['QUERY_STRING']))
                    $query_str = '?'.$_SERVER['QUERY_STRING'];
                else    
                    $query_str = "";

                if($page != 1)
                    echo '<a href="'.$url_page.($page - 1).$query_str.'" class="but-slider back-slider"><span></span></a>';
                if($page - $sizeSlider - 1 > 1) {
                    echo '<a href="'.$url_page.'1'.$query_str.'" class="but-slider"><span>1</span></a>';
                    echo '<a class="but-slider"><span>...</span></a>';
                }
                if($page - $sizeSlider - 1 == 1)
                    echo '<a href="'.$url_page.'1'.$query_str.'" class="but-slider"><span>1</span></a>';
                for($i = max(1, $page - $sizeSlider); $i <= min($max_page, $page + $sizeSlider); $i++) {
                        if($i == $page)
                    echo '<a href="'.$url_page.$i.$query_str.'" class="but-slider current-slider"><span>'.$i.'</span></a>';
                    else
                        echo '<a href="'.$url_page.$i.$query_str.'" class="but-slider"><span>'.$i.'</span></a>';
                }
                if($page + $sizeSlider + 1 == $max_page)
                    echo '<a href="'.$url_page.$max_page.$query_str.'" class="but-slider"><span>'.$max_page.'</span></a>';
                if($page + $sizeSlider + 1 < $max_page) {
                    echo '<a class="but-slider"><span>...</span></a>';
                    echo '<a href="'.$url_page.$max_page.$query_str.'" class="but-slider"><span>'.$max_page.'</span></a>';
                }
                if($page != $max_page)
                    echo '<a href="'.$url_page.($page + 1).$query_str.'" class="but-slider next-slider"><span></span></a>';
            ?>
        </div>
    </div>
@endsection

@push('scripts')

@endpush