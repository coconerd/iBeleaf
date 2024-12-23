<?php	
	session_start();
	use App\Providers\ProductData;
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
	<link rel="stylesheet" href="{{asset('css/layouts/header-main.css')}}">
	<link rel="stylesheet" href="{{asset('css/products/product.css')}}">
	<link rel="stylesheet" href="{{asset('css/products/product-container.css')}}">
	<link rel="stylesheet" href="{{asset('css/products/product-introduce.css')}}">
@endsection

@section("content")
	<div class="nav-bg-main" >
		<img src="{{ asset('images/main/Background-main1.png')}}" alt="main-background">
		<div class="nav-container"></div>
	</div>
	<div class="nav-introduce"></div>

	<div class="body-container">
		<div class="title-container">
			<p>{{ $title }}</p>
			<div class="box-title-container">
				<div></div>
				<span>SẢN PHẨM NỔI BẬT</span>
				<div></div>
			</div>
		</div>
		{{-- ------------------- --}}
		<div class="cate-container">
			<span>CÂY TRONG NHÀ</span>
		</div>
		<div class="product-container" id="cay-trong-nha"></div>
		<a class="more-product" href="cay-trong-nha" id="href-cay-trong-nha"> Xem thêm </a>
		{{-- ------------------- --}}
		<div class="cate-container">
			<span>CÂY NGOÀI TRỜI</span>
		</div>
		<div class="product-container" id="cay-ngoai-troi"></div>
		<a class="more-product" href="cay-ngoai-troi" id="href-cay-ngoai-troi"> Xem thêm </a>
		{{-- ------------------- --}}
		<div class="cate-container">
			<span>CHẬU CÂY</span>
		</div>
		<div class="product-container" id="chau-cay"></div>
		<a class="more-product" href="chau-cay" id="href-chau-cay"> Xem thêm </a>
	</div>

	{{-- --}}
	<div class="body-container">
		<div class="title-container">
			<p>{{ $title }}</p>
			<div class="box-title-container">
				<div></div>
				<span>VÌ SAO NÊN CHỌN CHÚNG TÔI</span>
				<div></div>
			</div>	
		</div>
		<div class="choose-container" name="{{$title}}"></div>

		<img class="end-section" src="{{ asset('images/main/img_banner.webp') }}" alt="end-section">
	</div>
@endsection

{{-- load dữ liệu --}}
@push('scripts')
	<script src="{{ asset('js/products/introduce.js')}}"></script>
	<script src="{{ asset('js/products/choose-intro.js')}}"></script>		
@endpush

@push('scripts')
	<script>
		// alert("successfull");
		let category = [{
			name: "Cây Trong Nhà",		
			id: "cay-trong-nha"	
		}, {
			name: "Cây Ngoài Trời",
			id: "cay-ngoai-troi"
		}, {
			name: "Chậu Cây",
			id: "chau-cay"
		}
		];

		// function processPrice(number) {
		// 	let res = "";
		// 	str = String(number);
		// 	while(str.length >= 3) {
		// 		if(res == "")
		// 			res = str.slice(-3);
		// 		else
		// 			res = str.slice(-3) + "." + res;

		// 		str = str.slice(0, -3);
		// 	}
		// 	if(str != "")
		// 		res = str + "." + res;

		// 	return res;
		// }
		
		function addProduct(id) {
			let product_html = "";
			switch (id) {
				case "cay-trong-nha":
					list_product = <?php echo json_encode(ProductData::getPlant("Cây Trong Nhà"))?>;
					type = "CÂY CẢNH"
					break;
				case "cay-ngoai-troi":
					list_product = <?php echo json_encode(ProductData::getPlant("Cây Ngoài Trời"))?>;
					type = "CÂY CẢNH"
					break;
				case "chau-cay":
					list_product = <?php echo json_encode(ProductData::getPlant("Chậu Cây"))?>;
					type = "CHẬU CÂY"
					break;
			}
			
			list_product.forEach(product => {
				product_html += `
				<div class="nav-product">
					<a class="nav-product-img" href="${window.location.origin + "/product/" + product.product_id}" id="${product.product_id}"></a>
					<div class="nav-product-category"> ${type} </div>`;

				if(product.discount_percentage > 0 && product.discount_percentage <= 100)
					product_html += `<div class="nav-product-discount"> -${product.discount_percentage}% </div>`;
				
				product_html += `
					<div class="nav-product-name">
						${product.short_description}
					</div>`;
					

				if(product.discount_percentage > 0)
					product_html += `
					<div class="nav-product-price"> 
						${product.discount_price}
						<span>₫</span> 
						<span class="nav-product-price-discount">${product.price}₫</span> 
					</div>`;	
				else
					product_html += `
					<div class="nav-product-price">
						${product.price}
						<span>₫</span>
					</div>`;

				product_html += `</div>`;

				// AJAX
				fetch(`/get-products?number=${product.product_id}`)
				.then(response => response.json()) // Chuyển phản hồi thành JSON
				.then(data => {	
					var html = "";
					var first = "nope";
					var isTwo = false; 
						
					for (let i = 0; i < data.length; i++) {
						if(first == "nope") {
							first = `<img class="disable_image" src="${data[i].product_image_url}" alt="${product.name}" style="opacity: 0;">`;
							html += `<img class="enable_image" src="${data[i].product_image_url}" alt="${product.name}">`;
						}
						else {
							isTwo = true;
							html += `<img class="disable_image" src="${data[i].product_image_url}" alt="${product.name}" style="opacity: 0;">`;
							break;
						}
					}
					if(isTwo == false && first != "nope")
						html += first;

					$(`#${product.product_id}`).append(html);
				})
				.catch(error => {
					console.error('Lỗi:', error);
				});
		});
			$(`#${id}`).append(product_html);
		}

		$(document).ready(function(){
			category.forEach(cate=>{
				addProduct(cate.id);
			});
		});
	</script>
@endpush
