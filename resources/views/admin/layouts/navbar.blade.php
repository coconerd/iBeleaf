<nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
	<div class="navbar-menu-wrapper d-flex align-items-stretch">
		<button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
			<span class="mdi mdi-chevron-double-left"></span>
		</button>
		<div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
			<a class="navbar-brand brand-logo-mini" href="../index.html"><img src="../../../assets/images/logo-mini.svg"
					alt="logo" /></a>
		</div>
		<ul class="navbar-nav">
			<li class="nav-item dropdown">
				<a class="nav-link" id="messageDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
					<i class="mdi mdi-email-outline"></i>
				</a>
				<div class="dropdown-menu dropdown-menu-left navbar-dropdown preview-list"
					aria-labelledby="messageDropdown">
					<h6 class="p-3 mb-0 fw-semibold">Messages</h6>
					<div class="dropdown-divider"></div>
					<a class="dropdown-item preview-item">
						<div class="preview-thumbnail">
							<img src="../../../assets/images/faces/face1.jpg" alt="image" class="profile-pic">
						</div>
						<div class="preview-item-content d-flex align-items-start flex-column justify-content-center">
							<h6 class="preview-subject ellipsis mb-1 fw-normal">Mark send you a message</h6>
							<p class="text-gray mb-0"> 1 Minutes ago </p>
						</div>
					</a>
					<div class="dropdown-divider"></div>
					<a class="dropdown-item preview-item">
						<div class="preview-thumbnail">
							<img src="../../../assets/images/faces/face6.jpg" alt="image" class="profile-pic">
						</div>
						<div class="preview-item-content d-flex align-items-start flex-column justify-content-center">
							<h6 class="preview-subject ellipsis mb-1 fw-normal">Cregh send you a message</h6>
							<p class="text-gray mb-0"> 15 Minutes ago </p>
						</div>
					</a>
					<div class="dropdown-divider"></div>
					<a class="dropdown-item preview-item">
						<div class="preview-thumbnail">
							<img src="../../../assets/images/faces/face7.jpg" alt="image" class="profile-pic">
						</div>
						<div class="preview-item-content d-flex align-items-start flex-column justify-content-center">
							<h6 class="preview-subject ellipsis mb-1 fw-normal">Profile picture updated</h6>
							<p class="text-gray mb-0"> 18 Minutes ago </p>
						</div>
					</a>
					<div class="dropdown-divider"></div>
					<h6 class="p-3 mb-0 text-center text-primary font-13">4 new messages</h6>
				</div>
			</li>
			<li class="nav-item dropdown ms-3">
				<a class="nav-link position-relative" id="notificationDropdown" href="#" data-bs-toggle="dropdown">
					<i class="mdi mdi-bell-outline"></i>
					<span class="position-absolute top-1 ms-2 translate-middle badge rounded-pill bg-danger notifications-count">
						0
					</span>
				</a>
				<div class="dropdown-menu dropdown-menu-left navbar-dropdown preview-list"
					aria-labelledby="notificationDropdown">
					<h6 class="px-3 py-3 fw-semibold mb-0">Thông báo</h6>
					<div class="dropdown-divider"></div>
					<div id="notifications">
					</div>
					<h6 class="p-3 font-13 mb-0 text-primary text-center">Xem tất cả thông báo</h6>
				</div>
			</li>
		</ul>
		<ul class="navbar-nav navbar-nav-right">
			<li class="nav-item nav-logout d-none d-md-block me-3">
				<a class="nav-link" href="#">Status</a>
			</li>
			<li class="nav-item nav-logout d-none d-md-block">
				<button class="btn btn-sm btn-danger">Đăng xuất</button>
			</li>
			<li class="nav-item nav-profile dropdown d-none d-md-block">
				<a class="nav-link dropdown-toggle" id="profileDropdown" href="#" data-bs-toggle="dropdown"
					aria-expanded="false">
					<div class="nav-profile-text">Tiếng Việt</div>
				</a>
				<div class="dropdown-menu center navbar-dropdown" aria-labelledby="profileDropdown">
					<a class="dropdown-item" href="#">
						<i class="flag-icon flag-icon-bl me-3"></i> English </a>
					<a class="dropdown-item" href="#">
						<i class="flag-icon flag-icon-bl me-3"></i> French </a>
					<div class="dropdown-divider"></div>
					<a class="dropdown-item" href="#">
						<i class="flag-icon flag-icon-cn me-3"></i> Chinese </a>
					<div class="dropdown-divider"></div>
				</div>
			</li>
			<li class="nav-item nav-logout d-none d-lg-block">
				<a class="nav-link" href="../index.html">
					<i class="mdi mdi-home-circle"></i>
				</a>
			</li>
		</ul>
		<button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
			data-toggle="offcanvas">
			<span class="mdi mdi-menu"></span>
		</button>
	</div>
</nav>

<link rel="stylesheet" href="{{ asset('css/admin/layouts/navbar.css') }}">
<script src="https://cdn.jsdelivr.net/npm/pusher-js@7.0.3/dist/web/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.11.3/dist/echo.iife.min.js"></script>
<script src="{{ asset('js/admin/layouts/navbar.js') }}"></script>