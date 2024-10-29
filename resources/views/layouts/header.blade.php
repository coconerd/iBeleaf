<!-- Top Header with time and contact info -->
<div class="header-top py-1">
	<div class="container d-flex justify-content-between align-items-center">
		<div>
			<i class="bi bi-clock me-1"></i> 08:30 - 22:00
			<span class="mx-2">|</span>
			<i class="bi bi-telephone me-1"></i> 0838 369 639 - 09 6688 9393
		</div>
		<div>
			<i class="bi bi-heart me-3"></i>
			<span class="me-2">
				@if (auth()->check())
					{{auth()->user()->name}}
				@else
					<a class="text-white" href="auth/login">Đăng nhập</a>
				@endif
			</span>
			<i class="bi bi-person"></i>
		</div>
	</div>
</div>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light navbar-main">
	<div class="container">
		<!-- Logo -->
		<a class="navbar-brand" href="#">
			<img src="https://png.pngtree.com/png-clipart/20220823/original/pngtree-green-coconut-sticker-vector-illustration-png-image_8462536.png"
				alt="Logo">
			<label>Plant Paradise</label>
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
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="indoorPlantsDropdown" role="button"
						data-bs-toggle="dropdown" aria-expanded="false">
						Cây trong nhà
					</a>
					<ul class="dropdown-menu" aria-labelledby="indoorPlantsDropdown">
						<li><a class="dropdown-item" href="#">Loại cây 1</a></li>
						<li><a class="dropdown-item" href="#">Loại cây 2</a></li>
					</ul>
				</li>
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="outdoorPlantsDropdown" role="button"
						data-bs-toggle="dropdown" aria-expanded="false">
						Cây ngoài trời
					</a>
					<ul class="dropdown-menu" aria-labelledby="outdoorPlantsDropdown">
						<li><a class="dropdown-item" href="#">Loại cây 1</a></li>
						<li><a class="dropdown-item" href="#">Loại cây 2</a></li>
					</ul>
				</li>
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="potDropdown" role="button"
						data-bs-toggle="dropdown" aria-expanded="false">
						Chậu cây
					</a>
					<ul class="dropdown-menu" aria-labelledby="potDropdown">
						<li><a class="dropdown-item" href="#">Loại chậu 1</a></li>
						<li><a class="dropdown-item" href="#">Loại chậu 2</a></li>
					</ul>
				</li>
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="accessoriesDropdown" role="button"
						data-bs-toggle="dropdown" aria-expanded="false">
						Phụ kiện
					</a>
					<ul class="dropdown-menu" aria-labelledby="accessoriesDropdown">
						<li><a class="dropdown-item" href="#">Phụ kiện 1</a></li>
						<li><a class="dropdown-item" href="#">Phụ kiện 2</a></li>
					</ul>
				</li>
				<form class="nav-item d-flex ms-5" role="search">
					<input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
					<a href="#" class="search mt-2"><i class="bi bi-search"></i></a>
				</form>
				<li class="nav-item ms-4 mt-1">
					<a href="#" class="cart me-3"><i class="bi bi-cart"></i>&nbsp;0₫</a>
				</li>
		</div>
	</div>
</nav>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css"></script>
<!-- End of navbar -->