<!DOCTYPE HTML>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>@yield('title')</title>

	<!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css"> -->
	<!-- Import bootstrap lib, with customized color pallete-->
	<link rel="stylesheet" href="{{asset('css/coconerd-bootstrap.css')}}">

	<!-- Import icon libs from CDN -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
	<style>
		/* Custom styling for header and navbar */
		.header-top {
			background-color: #234234;
			/* Dark green color */
			color: white;
			font-size: 0.9rem;
		}

		.navbar-main {
			background-color: #F7F4F0;
			/* Light cream background */
		}

		.navbar-main .navbar-brand img {
			height: 60px;
		}

		.navbar-main .nav-link {
			color: #234234;
			/* font-weight: 500; */
		}

		.navbar-main .cart,
		.navbar-main .search {
			font-size: 1.2rem;
			color: #234234;
		}

		.footer-section {
			padding: 80px 0;
			color: white;
			position: relative;
		}

		.footer-section::before {
			content: "";
			background-size: cover;
			background-color: #234234;
			min-height: 100%;
			background-image: url("https://wallpaperset.com/w/full/d/4/5/16094.jpg");
			position: absolute;
			top: 0;
			left: 0;
			right: 0;
			bottom: 0;
			z-index: -1;
			filter: blur(1px);
			opacity: 0.8;
			border: none;
			padding: 0;
			margin: 0;

			/* radial blur effect */
			background-blend-mode: overlay;
			background-image:
				radial-gradient(circle, rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.9)),
				url("https://wallpaperset.com/w/full/d/4/5/16094.jpg");

		}

		.nav-item .form-control {
			border-radius: 5px;
			margin-left: 15px;
			outline: none;
		}

		.nav-item .form-control:focus {
			border-radius: 5px;
			border: solid 2px #AAC87C;
			/* Overrides bootstrap thick border */
			box-shadow: none !important;
		}

		.footer-logo {
			max-width: 150px;
			margin-bottom: 20px;
		}

		.footer-text {
			font-size: 14px;
			line-height: 1.6;
			margin-bottom: 20px;
		}

		.footer-links h5 {
			font-size: 16px;
			margin-bottom: 15px;
			color: white;
		}

		.footer-links ul {
			list-style: none;
			padding: 0;
		}

		.footer-links li {
			margin-bottom: 10px;
		}

		.footer-links a {
			color: white;
			text-decoration: none;
			font-size: 14px;
		}

		.footer-contact p {
			margin-bottom: 8px;
			font-size: 14px;
		}

		.social-icons {
			margin-top: 20px;
		}

		.social-icons a {
			color: white;
			margin-right: 15px;
			font-size: 18px;
		}

		.map-container {
			width: 100%;
			height: 200px;
			margin-top: 20px;
		}

		.certification-badges img {
			height: 40px;
			margin-right: 15px;
			margin-bottom: 15px;
		}

		.copyright {
			text-align: center;
			padding-top: 20px;
			border-top: 1px solid rgba(255, 255, 255, 0.1);
			margin-top: 30px;
			font-size: 12px;
		}
	</style>
</head>

<body>
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
				<span class="me-2">minhduc468</span>
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