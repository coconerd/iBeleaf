<!-- Top Header with time and contact info -->
<div class="header-top py-2">
	<div class="container d-flex justify-content-between align-items-center">
		<div>
			<i class="bi bi-clock me-1"></i> 08:30 - 22:00
			<span class="mx-2">|</span>
			<i class="bi bi-telephone me-1"></i> 0838 369 639 - 09 6688 9393
		</div>
		<div>
			<i class="fa-solid fa-heart me-3" role="button"
				onclick="window.location.href='{{ route('wishlist.index') }}'"></i>
			<span class="me-2">
				@if (auth()->check())
					{{auth()->user()->full_name}}
				@else
					<a class="text-white" href="{{ url('/auth/login') }}">Đăng nhập</a>
				@endif
			</span>
			<i class="fa-solid fa-user" style="cursor: pointer;" onclick="window.location.href='{{ route('profile.homePage') }}'"></i>
		</div>
	</div>
</div>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light navbar-main" id="navbar-header">
	<div class="container">
		<!-- Logo -->
		<a class="navbar-brand" href="/">
			<img src="{{ asset('images/logos/tp-logo.png') }}" alt="LOGO">
			<label>PLANT PARADISE</label>
		</a>

		<!-- Toggle button for mobile view -->
		<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
			aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>

		<!-- Navbar links -->
		<div class="collapse navbar-collapse" id="navbarNav">
			<ul class="navbar-nav mx-auto">
				<!-- Dropdowns for different product categories -->
				<li class="nav-item dropdown a">
					<a class="nav-link dropdown-toggle" href="/cay-trong-nha" id="indoorPlantsDropdown" role="button"
						data-bs-toggle="dropdown" aria-expanded="false">
						Cây trong nhà
					</a>
				</li>
				<li class="nav-item dropdown a">
					<a class="nav-link dropdown-toggle" href="/cay-ngoai-troi" id="outdoorPlantsDropdown" role="button"
						data-bs-toggle="dropdown" aria-expanded="false">
						Cây ngoài trời
					</a>
				</li>
				<li class="nav-item dropdown a">
					<a class="nav-link dropdown-toggle" href="/chau-cay" id="potDropdown" role="button"
						data-bs-toggle="dropdown" aria-expanded="false">
						Chậu cây
					</a>
				</li>
				<form class="nav-item d-flex ms-5" role="search" action="/search">
					<input class="form-control me-2" id="input-search" type="search" placeholder="Search" aria-label="Search" name="name">
					<a href="#" class="search mt-2" id="but-input-search"><i class="bi bi-search"></i></a>

					<div id="out-search"></div>
					<div id="search-list-products" >
						<div id="search-popular" class="box-search" style="display: none;">
						</div>
						<div class="box-search-product box-search">
							<div id="search-product" class="box-search"></div>	
							<div class="div-more-search">
								<a id="more-search" href="#">Xem thêm</a>
							</div>
						</div>
					</div>
				</form>

				<!-- Cart icon -->
				<li class="nav-item ms-4 mt-1">
					<div class="cart-container">
						<a href="{{ route('cart.items') }}" class="cart me-3">
							<i class="fa-solid fa-cart-shopping cart-icon" style="color: #1e362d;"></i>
							<span class="cart-badge" id="cart-count"
								data-cart-id="{{ Auth::user()->cart->cart_id ?? 0 }}">
								{{
									Auth::check() && Auth::user()->role_type == 0 && Auth::user()->cart
									? Auth::user()->cart->items()
										->join('products', 'cart_items.product_id', '=', 'products.product_id')
										->where('products.stock_quantity', '>', 0)
										->sum('cart_items.quantity')
									: 0
								}}
							</span>
						</a>
					</div>
				</li>
			</ul>
		</div>

		<div class="dropdown-bar-close"></div>
		<div class="dropdown-bar"></div>
		<script src="{{ asset('js/engine/dropdown.js')}}"></script>
	</div>
</nav>

{{-- search bar --}}
<script src="{{ asset('js/engine/searchEngine.js')}}"></script>
<link rel="stylesheet" href="{{asset('css/engine/searchEngine.css')}}">
{{-- end seach bar --}}

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

<!-- Cart's style -->
<style>
	.cart-container {
		position: relative;
		display: inline-block;
	}

	.cart-icon {
		font-size: 22px;
		position: absolute;
		top: 4px;
		left: 14px;
	}

	.cart-badge {
		position: absolute;
		top: -9px;
		left: 28px;
		background-color: #F72C5B;
		color: white;
		border-radius: 50%;
		padding: 2px 4px;
		font-size: 10px;
		min-width: 18px;
		text-align: center;
	}

	.cart:hover {
		text-decoration: none;
		color: inherit;
	}
</style>
<!-- End of navbar -->