<?php
use App\Providers\ProductData;
?>

@extends("layouts.layout")
@section("title", "Chi tiết sản phẩm")

@section("head-script")
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@endsection

@section("style")
<link rel="stylesheet" href="{{ asset('css/product/index.css') }}">
@endsection

@section("content")
<!-- Custom alerts (hidden by default) -->
<div class="mt-2 alert alert-success visually-hidden" style="position: fixed; top: 10%; right: 2%; z-index: 1000;">
</div>

<div class="mt-2 alert alert-danger visually-hidden" style="position: fixed; top: 10%; right: 2%; z-index: 1000;">
</div>

<!-- Main container of page -->
<div class="container-fluid">
	<div class="row banner">
		<div class="container">
			<img src="{{ !empty($productImgs) ? $productImgs[0] : asset('images/placeholder-banner.png') }}"
				id="bannerImg" alt="Banner Image">
			<div class="text-container">
				<div class="navigation-links d-flex align-items-center nowrap">
					<a href="{{ url('/') }}" class="me-3 fw-bold" style="font-size: 1rem">Trang chủ</a>
					<a class="fw-bold select-none" style="user-select: none;">Danh mục</a>
					<a>
						<i class="bi bi-caret-right-fill" style="opacity: 0.8;"></i>
					</a>
					@foreach(array_slice($productCategories, 0, 2) as $category)
									<a href="{{ 
								route(
									'products',
									['category' => ProductData::convertCateHref($category)]
						) }}" class="me-3 fw-bold product-category">
							<i class="bi bi-arrow-90deg-right category-routing"></i>
							{{ $category }}
						</a>
					@endforeach
				</div>
			</div>
		</div>
	</div>
	<div class="container-fluid mt-4">
		<div class="row">
			<!-- Column 1: Product Images -->
			<div class="col-md-5" id="lcol">
				<div class="main-image">
					<p
						class="discount-text text-white fw-bold {{$product->discount_percentage <= 0 ? "visually-hidden" : ""}}">
						-{{ floor($product->discount_percentage) == $product->discount_percentage
	? number_format($product->discount_percentage, 0)
	: number_format($product->discount_percentage, 2) }}%</p>
					<div id="productCarousel" class="carousel slide" data-bs-ride="false" data-bs-interval="false">
						<div class="carousel-inner">
							@foreach ($productImgs as $img)
								<div class="carousel-item {{ $loop->first ? 'active' : '' }}">
									<img src="{{ asset($img) }}" class="d-block w-100" alt="Product Image">
								</div>
							@endforeach
						</div>
					</div>
					<button class="nav-arrows prev-arrow" onclick="navigateImage(-1)">&#10094;</button>
					<button class="nav-arrows next-arrow" onclick="navigateImage(1)">&#10095;</button>
					<button
						class="heart-button {{ $product->is_wishlisted ? 'heart-button-active' : 'heart-button-inactive' }}"
						data-product-id="{{ $product->product_id }}">
						<i class="fas fa-heart"></i>
					</button>
				</div>
				<div class="thumbnail-container mt-3">
					@foreach ($productImgs as $img)
						<div class="thumbnail-wrapper">
							<img src="{{ asset($img) }}"
								class="img-thumbnail {{ $loop->first ? 'js-img-thumbnail-active' : '' }}" alt="Thumbnail">
						</div>
					@endforeach
				</div>
			</div>

			<!-- Middle column: Product Details -->
			<div class="col-md-4" id="mcol">
				<h2 class="product-title vintage-heading">{{ $product->short_description }}</h2>
				@if ($product->discount_percentage > 0)
					<p class="product-price">
						<span class="text-muted text-decoration-line-through discounted-price-text">
							{{ number_format($product->price, 0, ',', '.') }} ₫
						</span>
						<br>
						<span class="fw-bold text price-text">
							{{ number_format($product->price * (1 - $product->discount_percentage / 100), 0, ',', '.') }} ₫
						</span>
					</p>
				@else
					<p class="product-price fw-bold price-text">
						{{ number_format($product->price, 0, ',', '.') }} ₫
					</p>
				@endif
				<p class="product-description">{{ $product->detailed_description }}</p>
				<table class="table table-borderless">
					<tbody>
						@foreach ($productAttributes as $key => $values)
							<tr>
								<th class="align-middle">{{ $key }}</th>
								<td class="align-middle">
									@if (is_array($values))
										<ul class="list-unstyled align-middle">
											@foreach ($values as $value)
												<li class="align-middle">{{ $value }}</li>
											@endforeach
										</ul>
									@else
										{{ $values }}
									@endif
								</td>
							</tr>
							@unless ($loop->last)
								<tr>
									<td colspan="2" class="border-bottom p-0 m-0"></td>
								</tr>
							@endunless
						@endforeach
					</tbody>
				</table>
				<div class="mt-5 d-flex align-items-left mb-5">
					<div class="d-inline-flex align-items-center border rounded me-3">
						<button class="btn btn-outline-primary border-0 decrementBtn"
							style="width: 40px; height: 40px;">-</button>
						<input type="text" class="counter form-control text-center border-0" value="1"
							style="width: 50px;" readonly>
						<button class="btn btn-outline-primary border-0 incrementBtn"
							style="width: 40px; height: 40px;">+</button>
					</div>
					<button class="btn btn-success me-3 addCartBtn" data-product-id="{{ $productId }}">Thêm
						vào giỏ hàng</button>
				</div>
				<!-- SKU and Product Categories for show  -->
				<table class="table table-borderless">
					<tbody>
						<tr>
							<td colspan="2" class="border-bottom p-0 m-0" style="border-color: #e9ecef;"></td>
						</tr>
						<tr>
							<td class="align-middle">
								<p class="mb-0" style="font-size: 0.85rem; color: var(--bs-dark);">SKU:
									{{ $product->code }}
								</p>
							</td>
						</tr>
						<tr>
							<td colspan="2" class="border-bottom p-0 m-0" style="border-color: #e9ecef; opacity: 0.2">
							</td>
						</tr>
						<td>
							<p class="mb-0" style="font-size: 0.85rem; color: var(--bs-dark)">Categories: <span>
									{{ implode(', ', $productCategories) }}
								</span></p>
						</td>
						<tr>
							<td colspan="2" class="border-bottom p-0 m-0" style="border-color: #e9ecef; opacity: 0.2">
							</td>
						</tr>
					</tbody>
				</table>
				<div class="d-flex gap-2">
					<a href="#" class="btn btn-outline rounded-circle p-2" style="width: 40px; height: 40px;">
						<i class="fab fa-facebook-f"></i>
					</a>
					<a href="#" class="btn btn-outline rounded-circle p-2" style="width: 40px; height: 40px;">
						<i class="fab fa-twitter"></i>
					</a>
					<a href="#" class="btn btn-outline rounded-circle p-2" style="width: 40px; height: 40px;">
						<i class="fab fa-pinterest"></i>
					</a>
					<a href="#" class="btn btn-outline rounded-circle p-2" style="width: 40px; height: 40px;">
						<i class="fab fa-linkedin-in"></i>
					</a>
				</div>

				<!-- Quality assurance badges -->
				<div id="carouselBadge" class="carousel slide mt-3" data-bs-ride="carousel">
					<div class="carousel-inner">
						<div class="carousel-item active">
							<div class="d-flex justify-content-center">
								<img src="{{ asset(path: 'https://mowgarden.com/wp-content/uploads/2022/12/plant.png') }}"
									style="width: 70px; height: auto; object-fit: contain; margin-bottom: 5px"
									alt="Badge 1">
								<div class="d-flex flex-column align-items-left;" style="padding-left: 10px;">
									<p class="mb-0 fw-bold" style="font-size: 1rem;">Miễn phí đổi trả</p>
									<p style="font-size: 1rem">Đổi trả trong vòng 7 ngày nếu không hài lòng</p>
								</div>
							</div>
						</div>
						<div class="carousel-item">
							<div class="d-flex justify-content-center">
								<img src="{{ asset('https://mowgarden.com/wp-content/uploads/2022/12/save-plants.png') }}"
									alt="Badge 2"
									style="width: 70px; height: auto; object-fit: contain; margin-bottom: 5px;">
								<div class="d-flex flex-column align-items-left" style="padding-left: 10px;">
									<p class="mb-0 fw-bold" style="font-size: 1rem;">Bảo hành 30 ngày</p>
									<p style="font-size: 1rem">Thay cây mới nếu bị chết trong vòng 30 ngày
									</p>
								</div>
							</div>
						</div>
						<div class="carousel-item">
							<div class="d-flex justify-content-center">
								<img src="{{ asset('https://mowgarden.com/wp-content/uploads/2022/12/gardening-4.png') }}"
									alt="Badge 3"
									style="width: 70px; height: auto; object-fit: contain; margin-bottom: 5px;">
								<div class="d-flex flex-column align-items-left" style="padding-left: 10px;">
									<p class="mb-0 fw-bold" style="font-size: 1rem;">Hướng dẫn kỹ thuật</p>
									<p style="font-size: 1rem">Hướng dẫn từ đội ngũ giàu kinh nghiệm</p>
								</div>
							</div>
						</div>
						<a class="carousel-control-prev" href="#carouselBadge" role="button" data-bs-slide="prev"
							style="top: 50%; transform: translateY(-50%);">
							<span class="carousel-control-prev-icon" aria-hidden="true"></span>
							<span class="visually-hidden">Previous</span>
						</a>
						<a class="carousel-control-next" href="#carouselBadge" role="button" data-bs-slide="next"
							style="top: 50%; transform: translateY(-50%);">
							<span class="carousel-control-next-icon" aria-hidden="true"></span>
							<span class="visually-hidden">Next</span>
						</a>
					</div>
				</div>
			</div>

			<!-- Column 3: (rcol) Product Order Info -->
			<div class="col-md-3 ms-auto" id="rcol">
				<div class="card" style="background-color: #F9F7F3;">
					<!-- Your existing card content -->
					<div class="card-body">
						<h5 class="card-title">Đặt hàng nhanh qua Hotline<br>(8h - 21h)</h5>
						<ul class="list-unstyled">
							<li>Hotline 1: <a href="tel:0912345678" class="text-decoration-none">0912 345 678
									(Call/Zalo)</a></li>
							<li>Hotline 2: <a href="tel:0812345678" class="text-decoration-none">0976 215 903
									(Call/Zalo)</a></li>
							<li>Hotline 3: <a href="tel:0923456789" class="text-decoration-none">0937 494 749
									(Call/Zalo)</a>
							</li>
						</ul>
						<div class="mt-3">
							<p class="mb-1"><i class="fas fa-check-circle text-success"></i> Miễn phí giao hàng các đơn
								từ
								1000K <br>(Miễn phí tối đa 50K)</p>
							<p class="mb-1"><i class="fas fa-check-circle text-success"></i> Thời gian giao hàng dự kiến
								từ
								1 - 7 ngày</p>
							<p class="mb-1"><i class="fas fa-check-circle text-success"></i> Các sản phẩm cây gồm chậu:
								Chỉ giao hàng tại TP.HCM</p>
							<p class="mb-0"><i class="fas fa-check-circle text-success"></i> Các sản phẩm cây nhỏ, chậu,
								phụ kiện, vật tư: Giao hàng toàn quốc</p>
						</div>
						<div class="d-flex align-items-center mt-3">
							<i class="fas fa-lock"></i>
							<span class="ms-2">Chính sách bảo mật</span>
						</div>
						<div class="d-flex align-items-center">
							<i class="fas fa-truck"></i>
							<span class="ms-2">Phương thức thanh toán</span>
						</div>
						<div class="mt-3">
							<p class="mb-0">Plant Paradise chúng tôi hiểu tâm lý e ngại của khách hàng khi mua hàng
								online. Do đó chúng tôi
								sẽ
								gửi
								hình ảnh thực tế của sản phẩm trước khi giao hàng để xác thực nhằm đảm bảo lượng dịch vụ
								và quyền lợi của khách hàng.
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- Custom styled HR -->
		<hr class="border-dark opacity-25 my-4" <!-- Related Products Section -->
		<div class="related-products mt-5">
			<h3 class="ms-4 mb-3 fw-light" style="color: #999999; font-size: 1.5rem">SẢN PHẨM TƯƠNG TỰ</h3>
			<div id="relatedProductsCarousel" class="carousel slide" data-bs-ride="carousel">
				<div class="carousel-inner">
					<div class="p-4">
					@foreach(array_chunk($relatedProducts->toArray(), 4) as $chunk)
								<div class="carousel-item {{ $loop->first ? 'active' : '' }}">
									<div class="d-flex justify-content-center">
										@foreach($chunk as $r)
															<div class="card m-3" style="width: 18rem;">
																<div class="position-relative">
																	<div class="card-img-wrapper">
																		<p
																			class="discount-text text-white fw-bold {{$r->discount_percentage <= 0 ? "visually-hidden" : ""}}">
																			-{{ floor($r->discount_percentage) == $r->discount_percentage
											? number_format($r->discount_percentage, 0)
											: number_format($r->discount_percentage, 2) }} %
																		</p>
																		<img src="{{ asset($r->img_url)}}" 
																			class="card-img-top" 
																			alt="Related Product"
																			data-second-image="{{ $r->second_img_url ?? '' }}"
																			data-original-image="{{ asset($r->img_url) }}">
																		<button class="hover-heart {{$r->is_wishlisted ? "heart-button-active" : ""}}"
																			data-product-id="{{ $r->product_id }}">
																			<i class="fas fa-heart"></i>
																		</button>
																		<a href="{{ route('product.show', ['product_id' => $r->product_id]) }}"
																			class="view-product">
																			<i class="fa-solid fa-leaf"></i>&nbsp;Xem chi tiết
																		</a>
																	</div>
																</div>
																<div class="card-body text-center">
																	<p class="card-text">{{ $r->title }}</p>
																	@if ($r->discount_percentage > 0)
																		<div class="d-flex justify-content-center align-items-center gap-2">
																			<span class="text-muted text-decoration-line-through discounted-price-text">
																				{{ number_format($r->price, 0, ',', '.') }}₫
																			</span>
																			<span class="price-text">
																				{{ number_format($r->price * (1 - $r->discount_percentage / 100), 0, ',', '.') }}₫
																			</span>
																		</div>
																	@else
																		<h3 class="price-text mb-0">
																			{{ number_format($r->price, 0, ',', '.') }}₫
																		</h3>
																	@endif
																</div>
															</div>
										@endforeach
									</div>
								</div>
					@endforeach
					</div>
				</div>
				<button class="carousel-control-prev" type="button" data-bs-target="#relatedProductsCarousel"
					data-bs-slide="prev">
					<span class="carousel-control-prev-icon" aria-hidden="true"></span>
				</button>
				<button class="carousel-control-next" type="button" data-bs-target="#relatedProductsCarousel"
					data-bs-slide="next">
					<span class="carousel-control-next-icon" aria-hidden="true"></span>
				</button>
			</div>
		</div>
		<!-- Custom styled HR -->
		<hr class="border-dark opacity-25 my-4">

		<!-- Feedback Section -->
		<div class="container-fluid my-5">
			<div class="row">
				<div class="col-12">
					<h2 id="reviewTitle">Reviews</h2>
					<div id="reviewsList" class="mb-4">
						<!-- Reviews will be loaded here dynamically -->
					</div>
				</div>
			</div>
			<div class="row" style="padding-right: 15vw;">
				<div class="col-12 align-items-left" style="border: black 1px solid">
					<div class="card border-0" style="background-color: transparent">
						<div class="card-body">
							<h4 class="card-title">Hãy để lại đánh giá</h4>
							<form id="reviewForm" action="{{ route('product.submitFeedback') }}" method="POST"
								enctype="multipart/form-data">
								@csrf
								<input type="hidden" name="product_id" value="{{ $productId }}">
								<div class="form-group mb-4">
									<label for="rating">Rating *</label>
									<div class="star-rating mt-2" style="position: absolute; left: 20px">
										@for($i = 5; $i >= 1; $i--)
											<input type="radio" id="star{{$i}}" name="rating" value="{{$i}}" required>
											<label for="star{{$i}}"><i class="fas fa-star"></i></label>
										@endfor
									</div>
								</div>
								<div class="form-group mt-5">
									<label for="review">Đánh giá của bạn (tối thiểu 10 ký tự) *</label>
									<textarea name="review" minlength=10 maxlength=255 class="form-control mt-2"
										rows="5" required
										style="background-color: #fff; border: 1px solid #ced4da;"></textarea>
									<!-- image upload component -->
									<label class="form-label mt-2" id='imgCounter'>Tải ảnh lên (tối đa 5)</label>
									<input type="file" name="images[]" accept="image/*" multiple
										class="form-control visually-hidden">
								</div>
								<!-- Preview uploaded images -->
								<div class="d-flex flex-wrap" id='preview-image-container'>
									<!-- Add new image button -->
									<div class="p-2" style="background-color: transparent;">
										<div class="d-flex justify-content-center align-items-center border border-dashed"
											style="width: 100px; height: 100px; cursor: pointer;"">
											<span class=" fs-3">+</span>
										</div>
									</div>
								</div>
								<button type="submit" id='submitFeedbackBtn' class="btn btn-primary bnt-md mt-3">Gửi đánh giá</button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Discounted products section -->
		<div class="container my-5" id="discountedProducts">
			<div class="row">
				<div class="col-12 d-flex align-items-center">
					<div class="flex-grow-1 border-bottom mb-2"></div>
					<h2 class="fw-light mx-3" style="color: var(--bs-dark);">SẢN PHẨM KHUYẾN MÃI</h2>
					<div class="flex-grow-1 border-bottom mb-2"></div>
				</div>
			</div>
			<div class="row">
				@foreach(array_chunk($discountedProducts->toArray(), 5) as $discountChunk)
						<div class="d-flex justify-content-center">
							@foreach($discountChunk as $r)
										<div class="card me-4 mb-4 mt-4" style="width: 18rem;">
											<div style="position: relative; overflow: hidden;">
												<p
													class="discount-text text-white fw-bold {{$r->discount_percentage <= 0 ? "visually-hidden" : ""}}">
													-{{ floor($r->discount_percentage) == $r->discount_percentage
								? number_format($r->discount_percentage, 0)
								: number_format($r->discount_percentage, 2) }}%
												</p>
												<img src="{{ asset($r->img_url)}}" 
													class="card-img-top" 
													alt="Related Product"
													data-second-image="{{ $r->second_img_url ?? '' }}"
													data-original-image="{{ asset($r->img_url) }}"
													style="position: relative; width: 100%; height: auto;">
												<button class="hover-heart {{ $r->is_wishlisted ? "heart-button-active" : "" }}"
													data-product-id="{{ $r->product_id }}">
													<i class="fas fa-heart"></i>
												</button>
												<a href="{{ route('product.show', ['product_id' => $r->product_id]) }}"
													class="view-product">
													<i class="fa-solid fa-leaf"></i>&nbsp;Xem chi tiết
												</a>
											</div>
											<div class="card-body text-center">
												<p class="card-text">{{ $r->title }}</p>
												@if ($r->discount_percentage > 0)
													<h3>
														<span class="text-muted text-decoration-line-through discounted-price-text">
															{{ number_format($r->price, 0, ',', '.') }} ₫
														</span>
														<br>
														<span class="price-text">
															{{ number_format($r->price * (1 - $r->discount_percentage / 100), 0, ',', '.') }} ₫
														</span>
													</h3>
												@else
													<h3 class="price-text" style="font-size: 1.3rem;">
														{{ number_format($r->price, 0, ',', '.') }} ₫
													</h3>
												@endif
											</div>
										</div>
							@endforeach
						</div>
				@endforeach
			</div>
		</div>

		<!-- Warranty Info section -->
		<div class="container my-5">
			<div class="row">
				<div class="col-12 d-flex align-items-center">
					<div class="flex-grow-1 border-bottom mb-2"></div>
					<h2 class="fw-light mx-3" style="color: var(--bs-dark);">CHÍNH SÁCH ĐỔI TRẢ</h2>
					<div class="flex-grow-1 border-bottom mb-2"></div>
				</div>
			</div>
		</div>
		<div class="container my-5" style="padding: 0 13vw;">
			<p style="text-align: justify;">
				Plant Paradise mong muốn mang đến cho khách hàng trải nghiệm mua sắm tuyệt vời nhất. Vì vậy, tất cả sản
				phẩm
				tại Plant Paradise đều được kiểm tra chất lượng kỹ lưỡng trước khi đến tay bạn. Chúng tôi hy vọng
				bạn không chỉ hài lòng với sản phẩm mà còn với chất lượng dịch vụ của chúng tôi. Nếu có bất kỳ vấn đề
				nào xảy ra,
				đừng ngần ngại liên hệ ngay với chúng tôi để được hỗ trợ kịp thời nhé!
			</p>
			<div class="row">
				<div class="col-md-6 pe-4">
					<div class="d-flex flex-column mt-4">
						<h4 class="text-dark text-center">Sản phẩm không phải là cây</h4>
						<p style="text-align: justify;">Đối với các sản phẩm không phải là cây xanh, quý khách có thể
							thực hiện đổi trả trong vòng <b>30 ngày</b> kể từ khi nhận hàng, nếu sản phẩm gặp lỗi do nhà
							sản xuất.</p>
					</div>
				</div>
				<div class="col-md-6 ps-5">
					<div class="d-flex flex-column mt-4">
						<h4 class="text-dark text-center">
							Sản phẩm là cây xanh
						</h4>
						<p style="text-align: justify;">
							Đối với các sản phẩm cây xanh, nếu gặp tình trạng hư hại, héo úa hoặc suy
							yếu khi được giao, quý khách có thể yêu cầu đổi trả cây mới hàng trong vòng <b>7 ngày</b>,
							hoặc vui lòng liên hệ với chúng tôi để được hỗ trợ.
						</p>
					</div>
				</div>
			</div>
			<div class="row mt-5">
				<p style="text-align: justify;">
					Nếu có vấn đề cần hỗ trợ liên quan đến bảo hành vả đổi trả sản phẩm, quý khách có thể liên hệ với
					chúng tôi thông qua
					hotline (0912 345 678 hoặc 0976 215 903), fanpage, hoặc gửi email đến hotroplantparadise@gmail.com
				</p>
			</div>
		</div>
		<!-- <div class="col-md-6"></div> -->
		<div class="container-fluid my-5">
			<div class="row">
				<div class="border-bottom"></div>
			</div>
		</div>
	</div>
</div>

<!-- Main image modal -->
<div id="fullscreenModal" class="fullscreen-modal">
	<span class="modal-close">&times;</span>
	<img id="fullscreenImage" class="fullscreen-image" src="" alt="Fullscreen view">
</div>

<!-- Sticky Cart Section -->
<div class="sticky-bottom border-top shadow-lg py-3" style="background-color: #F9F7F3;">
	<div class="container d-flex align-items-center justify-content-between">
		<!-- Product Info -->
		<div class="d-flex align-items-center">
			<button class="btn btn-outline-primary btn-md me-3 scroll-to-top" onclick="window.scrollTo(0, 0)">
				<i class="bi bi-chevron-up"></i>
			</button>
			<img src="{{ $productImgs[0] }}" alt="Product Image" class="img-fluid rounded me-3"
				style="width: 50px; height: 50px;">
			<div>
				<p class="mb-0 fw-bold text-primary" style="font-size: 1.1rem;">{{ $product->short_description ?? 'Tên sản phẩm' }}</p>
				<small class="text-muted">Mã sản phẩm: {{ $product->code ?? 'Mã sản phẩm' }}</small>
			</div>
		</div>

		<!-- Price and Quantity -->
		<div class="d-flex align-items-center">
			<p class="mb-0 price-text fw-bold me-4 total-price"
				data-unit-price="{{ $product->price * (1 - $product->discount_percentage / 100) }}">
				{{ number_format(
	$product->discount_percentage > 0 ? $product->price * (1 - $product->discount_percentage / 100) : $product->price,
	0,
	',',
	'.'
) }} ₫
			</p>
			<div class="input-group input-group-md me-3" style="width: 120px;">
				<button class="btn border-1 decrementBtn">-</button>
				<input type="text" class="counter form-control text-center border" value="1" min="1">
				<button class="btn border-1 incrementBtn">+</button>
			</div>
			<button class="btn btn-md addCartBtn" data-product-id="{{ $productId }} ">Thêm vào giỏ
				hàng</button>
		</div>
	</div>
</div>
@endsection

@section("body-script")
<script src="{{ asset('js/product/index.js') }}"></script>
<script>
	loadReviews('{{ $productId }}');
</script>
@endsection