@extends("layouts.layout")
@section("title", "landing-page")
@section("style")
<style>
	/* Banner styles */
	.banner .container {
		position: relative;
		height: 100px;
		padding: 0;
		margin: 0;
		/* Adjust the height as needed */
	}

	.banner {
		overflow-x: hidden;
		width: 100vw;
	}

	#bannerImg {
		width: 100%;
		height: 100%;
		object-fit: cover;
		position: relative;
		left: 50%;
		transform: translateX(-50%);
	}

	.banner .text-container {
		position: absolute;
		top: 50%;
		left: 50%;
		transform: translate(-50%, -50%);
		text-align: center;
		color: white;
		text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
	}

	.banner .navigation-links {
		margin-top: 1rem;
	}

	.banner .navigation-links a {
		color: white;
		text-decoration: none;
		margin: 0 1rem;
	}

	/* End of banner styles */
	body {
		background-color: #fcf9f3;
	}

	.main-image img {
		border-radius: 8px;
		box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
	}

	.thumbnail-container {
		display: flex;
		gap: 1rem;
	}

	.thumbnail-wrapper {
		width: 8rem;
		height: 7.5rem;
		overflow: hidden;
		/* Ensure the image overflows the frame */
		position: relative;
		background-color: transparent;
		border: none;
		border-radius: 25px;
		padding: 0px;
	}

	.img-thumbnail {
		width: 115%;
		height: 110%;
		/* Slightly zoomed in */
		object-fit: cover;
		position: relative;
		/* Position the image absolutely within the wrapper */
		border: none;
		background-color: transparent;
		border-radius: 10px;
		cursor: pointer;
		transition: transform 0.39s ease-in-out, opacity 0.39s ease-in-out;
		opacity: 0.5;
		margin: 0px;
	}

	.img-thumbnail:hover {
		transform: translateY(-5px);
		/* Push the image up slightly */
		opacity: 1;
	}

	.js-img-thumbnail-active {
		opacity: 1;
		box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
	}

	.product-title {
		font-size: 1.75rem;
		font-weight: bold;
	}

	.product-price {
		color: #28a745;
	}

	.hotline-section ul {
		margin: 0;
		padding: 0;
		list-style: none;
	}

	.hotline-section a {
		color: #007bff;
	}

	.hotline-section a:hover {
		text-decoration: underline;
	}

	.related-products .card {
		border: none;
		transition: transform 0.2s;
	}

	.related-products .card:hover {
		/* transform: translateY(-5px); */
	}

	.reviews textarea {
		resize: none;
	}

	/* Left column - Product image styles */
	#lcol {
		padding-left: 1rem;
	}

	/* End of product image styles */

	/* Middle column - Product details style */
	#mcol {
		padding-right: 2rem;
	}

	.table th {
		font-weight: normal;
		font-size: medium;
		color: #6c757d;
		background-color: transparent;
		padding: 5px;
	}

	.table tr {
		margin: 0;
	}

	.table td {
		padding-left: 0;
		background-color: transparent;
		font-size: medium;
		padding: 5px;
	}

	.table ul {
		list-style-type: none;
		padding-left: 0;
		margin-bottom: 0;
	}

	.table ul li {
		margin: 0px;
	}

	/* Add these new styles */
	#carouselBadge .carousel-control-prev,
	#carouselBadge .carousel-control-next {
		opacity: 0;
		transition: opacity 0.3s ease;
		border-radius: 50%;
		width: 40px;
		object-fit: contain;
	}

	#carouselBadge:hover .carousel-control-prev,
	#carouselBadge:hover .carousel-control-next {
		opacity: 1;
		drop-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
	}

	#carouselBadge {
		box-shadow: none;
	}

	#carouselBadge .carousel-inner {
		border: none;
		background-color: transparent;
		box-shadow: none;
	}

	/* End of product details style */

	/* Right col styles */

	/* End of right col styles */

	.main-image {
		position: relative;
		width: 100%;
		border-radius: 8px;
		overflow: hidden;
	}

	.carousel-inner {
		border-radius: 8px;
		box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
	}

	.carousel-item img {
		width: 100%;
		object-fit: cover;
	}

	/* Hide Bootstrap's default controls */
	.carousel-control-prev,
	.carousel-control-next {
		display: block;
	}

	.main-image-container {
		display: flex;
		transition: transform 0.3s ease-in-out;
		width: 100%;
	}

	.main-image-container img {
		min-width: 100%;
		flex-shrink: 0;
	}

	.main-image:hover .prev-arrow {
		transform: translate(0, -50%);
	}

	.main-image:hover .next-arrow {
		transform: translate(0, -50%);
	}

	.main-image:hover .nav-arrows {
		opacity: 1;
		/* Instead of display:flex, use opacity */
	}


	.nav-arrows {
		position: absolute;
		top: 50%;
		width: 40px;
		height: 40px;
		background: none;
		background-color: transparent;
		color: white;
		border: none;
		border-radius: 50%;
		cursor: pointer;
		display: flex;
		/* Changed from 'none' to 'flex' */
		align-items: center;
		justify-content: center;
		font-size: 24px;
		transition: all 0.3s ease;
		opacity: 0;
		/* Instead of display:none, use opacity */
	}

	.prev-arrow {
		left: 10px;
		transform: translate(-20px, -50%);
	}

	.next-arrow {
		right: 10px;
		transform: translate(20px, -50%);
	}

	/* Fullscreen modal styles */
	.fullscreen-modal {
		display: none;
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		background: rgba(0, 0, 0, 0.9);
		z-index: 1050;
		cursor: pointer;
	}

	.fullscreen-image {
		max-height: 90vh;
		max-width: 90vw;
		position: absolute;
		top: 50%;
		left: 50%;
		transform: translate(-50%, -50%);
	}

	.modal-close {
		position: fixed;
		top: 20px;
		right: 20px;
		color: white;
		font-size: 30px;
		cursor: pointer;
		z-index: 1051;
	}

	.wishlist-button {
		position: absolute;
		top: 10px;
		right: 10px;
		background: transparent;
		border: none;
		color: grey;
		font-size: 24px;
		cursor: pointer;
		transition: color 0.3s ease;
	}

	.wishlist-button:hover {
		color: red;
	}

	.wishlist-button.added {
		color: red;
	}
</style>
@endsection
@section("content")
<div class="container-fluid">
	<div class="row banner">
		<div class="container">
			<img src="{{ asset(path: $bannerImgSrc) }}" id="bannerImg" alt="Banner Image">
			<div class="text-container">
				<div class="navigation-links d-flex align-items-center nowrap">
					<strong class="me-5">
						<span style="font-size: 1.5rem; color: white;">Cây phát tài b�� 5 - Cây thiết mộc lan
							CPTK001</span>
					</strong>
					<a href="#" class="me-3" style="font-size: 1rem">Trang chủ</a>
					<a href="#" class="me-3" style="font-size: 1rem">Cây Cảnh Văn Phòng</a>
					<a href="#" class="font-size: 0.75rem"><b>Cây phát tài bộ 5 - Cây thiết mộc lan CPTK001</b></a>
				</div>
			</div>
		</div>
	</div>
	<div class="container-fluid mt-4">
		<div class="row">
			<!-- Column 1: Product Images -->
			<div class="col-md-5" id="lcol">
				<div class="main-image">
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
					<button id="wishlistButton" class="wishlist-button">
						<i class="fas fa-heart"></i>
					</button>
				</div>
				<div class="thumbnail-container mt-3">
					@foreach ($productImgs as $img)
						<div class="thumbnail-wrapper">
							<img src="{{ asset($img) }}"
								class="img-thumbnail {{ $loop->first ? 'js-img-thumbnail-active' : '' }}" alt="Thumbnail"
								onclick="document.querySelector('.main-image img').src = this.src;document.querySelectorAll('.img-thumbnail').forEach(t => t.classList.remove('js-img-thumbnail-active')); this.classList.add('js-img-thumbnail-active');">
						</div>
					@endforeach
				</div>
			</div>

			<!-- Middle column: Product Details -->
			<div class="col-md-4" id="mcol">
				<h2 class="product-title">Cây phát tài bó 5 – Cây thiết mộc lan CPTK001</h2>
				<p class="product-price text-success fw-bold fs-4">750.000₫</p>
				<p class="product-description">Cây phát tài bó còn được biết đến với tên gọi khác là cây thiết mộc
					lan...</p>
				<table class="table table-borderless">
					<tbody>
						@foreach ($productAttributes as $key => $value)
							<tr>
								<th class="align-middle">{{ $key }}</th>
								<td class="align-middle">
									@if (is_array($value))
										<ul class="list-unstyled align-middle">
											@foreach ($value as $item)
												<li class="align-middle">{{ $item }}</li>
											@endforeach
										</ul>
									@else
										{{ $value }}
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
						<button class="btn btn-outline-primary border-0" id="decrementBtn"
							style="width: 40px; height: 40px;">-</button>
						<input type="text" id="counter" class="form-control text-center border-0" value="1"
							style="width: 50px;" readonly>
						<button class="btn btn-outline-primary border-0" id="incrementBtn"
							style="width: 40px; height: 40px;">+</button>
					</div>
					<button class="btn btn-success me-3">Thêm vào giỏ hàng</button>
				</div>
				<!-- SKU and Product Categories for show  -->
				<table class="table table-borderless">
					<tbody>
						<tr>
							<td colspan="2" class="border-bottom p-0 m-0" style="border-color: #e9ecef;"></td>
						</tr>
						<tr>
							<td class="align-middle">
								<p class="mb-0" style="font-size: 0.85rem; color: var(--bs-dark);">SKU: <span
										class="">{{$productId}}</>
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
			<!-- Column 3: Product Order Info -->
			<div class="col-md-3 ms-auto" id="rcol">
				<div class="card" style="background-color: #F9F7F3;">
					<!-- Your existing card content -->
					<div class="card-body">
						<h5 class="card-title">Đặt hàng nhanh qua Hotline (8h - 21h)</h5>
						<ul class="list-unstyled">
							<li>Hotline 1: <a href="tel:0838369639" class="text-decoration-none">0838 369 639
									(Call/Zalo)</a></li>
							<li>Hotline 2: <a href="tel:0966889393" class="text-decoration-none">0966 888 9393 (Call -
									Zalo)</a></li>
							<li>Hotline 3: <a href="tel:0975737494" class="text-decoration-none">0975 737 494 (Zalo)</a>
							</li>
						</ul>
						<div class="mt-3">
							<p class="mb-1"><i class="fas fa-check-circle text-success"></i> Miễn phí giao hàng các đơn
								từ
								1000k (tối đa 50k)</p>
							<p class="mb-1"><i class="fas fa-check-circle text-success"></i> Thời gian giao hàng dự kiến
								từ
								1 - 7 ngày</p>
							<p class="mb-1"><i class="fas fa-check-circle text-success"></i> Các sản phẩm cây gốm, chậu:
								Chị
								giao hàng tại Tp.HCM</p>
							<p class="mb-0"><i class="fas fa-check-circle text-success"></i> Các sản phẩm cây nhỏ, chậu,
								phụ
								kiện, vật tư: Giao hàng toàn quốc</p>
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
							<p class="mb-0">MOW Garden hiểu rằng, tâm lý e ngại khi mua hàng Online, do đó chúng tôi sẽ
								gửi
								ảnh thực tế trước khi giao hàng. Nhằm đảm bảo lương dịch vụ và quyền lợi của khách hàng.
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- Custom styled HR -->
		<hr class="border-dark opacity-25 my-4">

		<!-- Related Products Section -->
		<div class="related-products mt-5">
			<h3 class="mb-3 fw-light" style="color: #999999; font-size: 1.5rem">SẢN PHẨM TƯƠNG TỰ</h3>
			<div id="relatedProductsCarousel" class="carousel slide" data-bs-ride="carousel">
				<div class="carousel-inner" style="padding: 0 100px;">
					@foreach(array_chunk(range(1, 6), 3) as $chunk)
						<div class="carousel-item {{ $loop->first ? 'active' : '' }}">
							<div class="d-flex justify-content-center">
								@foreach($relatedProducts as $r)
									<div class="card me-4 mb-4 mt-4" style="width: 18rem;">
										<img src="{{ asset($r->imgSrc)}}" class="card-img-top" alt="Related Product">
										<div class="card-body text-center">
											<p class="card-text text-start">{{ $r->title }}</p>
											<h3 class="text-success" style="font-size: 1.5rem"> {{$r->price}}</>
										</div>
									</div>
								@endforeach
							</div>
						</div>
					@endforeach
				</div>
				<button class="carousel-control-prev" type="button" data-bs-target="#relatedProductsCarousel"
					data-bs-slide="prev">
					<span class="carousel-control-prev-icon" aria-hidden="true"></span>
					<span class="visually-hidden">Previous</span>
				</button>
				<button class="carousel-control-next" type="button" data-bs-target="#relatedProductsCarousel"
					data-bs-slide="next">
					<span class="carousel-control-next-icon" aria-hidden="true"></span>
					<span class="visually-hidden">Next</span>
				</button>
			</div>
		</div>
		<!-- Custom styled HR -->
		<hr class="border-dark opacity-25 my-4">

		<!-- Reviews Section -->
		<div class="container-fluid my-5">
			<div class="row">
				<div class="col-12">
					<h2>Reviews</h2>
					<p>There are no reviews yet.</p>
				</div>
			</div>
			<div class="row" style="padding-right: 15vw;">
				<div class="col-12 align-items-left" style="border: black 1px solid">
					<div class="card border-0" style="background-color: transparent">
						<div class="card-body">
							<h4 class="card-title">Be the first to review "Cây lưỡi hổ Bantel Sensation chậu ươm
								STBS001"</h4>
							<div class="form-group mb-4">
								<label for="rating">Số sao *</label>
								<div class="btn-group btn-group-toggle" data-toggle="buttons">
									<label class="ms-5 btn btn-outline-primary">
										<input type="radio" name="rating" id="rating1" value="1"> <i
											class="bi bi-star"></i>
									</label>
									<label class="btn btn-outline-primary">
										<input type="radio" name="rating" id="rating2" value="2"> <i
											class="bi bi-star"></i><i class="bi bi-star"></i>
									</label>
									<label class="btn btn-outline-primary">
										<input type="radio" name="rating" id="rating3" value="3"> <i
											class="bi bi-star"></i><i class="bi bi-star"></i><i class="bi bi-star"></i>
									</label>
									<label class="btn btn-outline-primary">
										<input type="radio" name="rating" id="rating4" value="4"> <i
											class="bi bi-star"></i><i class="bi bi-star"></i><i
											class="bi bi-star"></i><i class="bi bi-star"></i>
									</label>
									<label class="btn btn-outline-primary">
										<input type="radio" name="rating" id="rating5" value="5"> <i
											class="bi bi-star"></i><i class="bi bi-star"></i><i
											class="bi bi-star"></i><i class="bi bi-star"></i><i class="bi bi-star"></i>
									</label>
								</div>
							</div>
							<div class="form-group">
								<label for="review">Đánh giá của bạn *</label>
								<textarea class="mt-2 mb-3 form-control border-0" id="review" rows="5"
									style="outline: none !important; box-shadow: none !important;"></textarea>
							</div>
							<button type="submit" class="btn btn-primary rounded-1">SUBMIT</button>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Sale products section -->
		<div class="container my-5">
			<div class="row">
				<div class="col-12 d-flex align-items-center">
					<div class="flex-grow-1 border-bottom mb-2"></div>
					<h2 class="fw-light mx-3" style="color: var(--bs-dark);">SẢN PHẨM KHUYẾN MÃI</h2>
					<div class="flex-grow-1 border-bottom mb-2"></div>
				</div>
			</div>
		</div>

		<!-- Sale products section -->
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
				<b>Plant Paradise</b> mong muốn mang đến cho khách hàng trải nghiệm mua sắm tuyệt vời nhất. Vì vậy, tất
				cả sản phẩm
				tại <b>Plant Paradise</b> đều được <b>kiểm tra chất lượng kỹ lưỡng</b> trước khi đến tay bạn. Chúng tôi
				hy vọng
				bạn không
				chỉ hài lòng với sản phẩm mà còn với chất lượng dịch vụ của chúng tôi. Nếu có bất kỳ vấn đề nào xảy ra,
				đừng ngần ngại liên hệ ngay với <b>Plant Paradise</b> để được hỗ trợ kịp thời nhé!
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
							yếu khi được giao, quý khách vui lòng liên hệ với <b>Plant Paradise</b> trong vòng <b>7
								ngày</b> để
							được đổi
							cây mới.
						</p>
					</div>
				</div>
			</div>
			<div class="row mt-5">
				<p style="text-align: justify;">
					Nếu có vấn đề cần hỗ trợ liên quan đến bảo hành vả đổi trả sản phẩm, quý khách có thể liên hệ với
					<b>Plant Paradise</b> thông qua
					hotline (0937 802 672 hoặc 097 123 82 25), fanpage, hoặc gửi email đến hotro@plantparadise.com.vn.
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
</div>
</div>
<div class="container-fluid"></div>

<!-- Main image modal -->
<div id="fullscreenModal" class="fullscreen-modal">
	<span class="modal-close">&times;</span>
	<img id="fullscreenImage" class="fullscreen-image" src="" alt="Fullscreen view">
</div>
</div>
@endsection

@section("body-script")
<script>
	document.addEventListener('DOMContentLoaded', function () {
		const thumbnails = document.querySelectorAll('.img-thumbnail');
		const carousel = new bootstrap.Carousel(document.querySelector('#productCarousel'));

		thumbnails.forEach((thumb, index) => {
			thumb.addEventListener('click', function () {
				carousel.to(index);
				updateThumbnails(index);
			});
		});

		// Counter functionality
		const decrementButton = document.getElementById("decrementBtn");
		const incrementButton = document.getElementById("incrementBtn");
		const counterInput = document.getElementById("counter");

		// Event listener for decrement
		decrementButton.addEventListener("click", function () {
			let currentValue = parseInt(counterInput.value, 10);
			if (currentValue > 1) {
				counterInput.value = currentValue - 1;
			}
		});

		// Event listener for increment
		incrementButton.addEventListener("click", function () {
			let currentValue = parseInt(counterInput.value, 10);
			counterInput.value = currentValue + 1;
		});

		const productId = {{ $productId }};
		const wishlistButton = document.getElementById('wishlistButton');

		wishlistButton.addEventListener('click', function () {
			fetch('/wishlist/add', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json',
					'X-CSRF-TOKEN': '{{ csrf_token() }}'
				},
				body: JSON.stringify({ product_id: productId })
			})
			.then(response => response.json())
			.then(data => {
				if (data.success) {
					wishlistButton.classList.add('added');
					alert('Product added to your wishlist!');
				} else {
					alert('Please log in to add products to your wishlist.');
				}
			})
			.catch(error => {
				console.error('Error:', error);
			});
		});
	});

	let currentImageIndex = 0;
	const carousel = document.querySelector('#productCarousel');

	function navigateImage(direction) {
		const bsCarousel = bootstrap.Carousel.getInstance(carousel);

		// Calculate new index
		const items = carousel.querySelectorAll('.carousel-item');
		const activeItem = carousel.querySelector('.carousel-item.active');
		currentImageIndex = Array.from(items).indexOf(activeItem);
		let newIndex;

		if (direction > 0) {
			bsCarousel.next();
			newIndex = (((currentImageIndex + 2) % items.length) - 1);
		} else {
			bsCarousel.prev();
			newIndex = (((currentImageIndex + items.length) % items.length) - 1);
		}

		updateThumbnails(newIndex);
	}

	function updateThumbnails(index) {
		document.querySelectorAll('.img-thumbnail').forEach((thumb, i) => {
			thumb.classList.toggle('js-img-thumbnail-active', i === index);
		});
	}

	// Listen for carousel events
	carousel.addEventListener('slid.bs.carousel', function () {
		const activeItem = carousel.querySelector('.carousel-item.active');
		const items = carousel.querySelectorAll('.carousel-item');
		currentImageIndex = Array.from(items).indexOf(activeItem);
		updateThumbnails(currentImageIndex);
	});

	// Add fullscreen functionality
	const modal = document.getElementById('fullscreenModal');
	const modalImg = document.getElementById('fullscreenImage');
	const closeBtn = document.querySelector('.modal-close');

	// Make main carousel images clickable
	document.querySelectorAll('#productCarousel .carousel-item img').forEach(img => {
		img.style.cursor = 'pointer';
		img.onclick = function () {
			modal.style.display = 'block';
			modalImg.src = this.src;
		}
	});

	// Close modal when clicking close button or outside the image
	closeBtn.onclick = function () {
		modal.style.display = 'none';
	}

	modal.onclick = function (e) {
		if (e.target === modal) {
			modal.style.display = 'none';
		}
	}

	// Close modal with escape key
	document.addEventListener('keydown', function (e) {
		if (e.key === 'Escape' && modal.style.display === 'block') {
			modal.style.display = 'none';
		}
	});

</script>
@endsection