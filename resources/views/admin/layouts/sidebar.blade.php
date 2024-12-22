<nav class="sidebar sidebar-offcanvas" id="sidebar">
	<ul class="nav">
		<li class="nav-item nav-profile border-bottom">
			<a href="#" class="nav-link flex-column">
				<div class="nav-profile-image">
					<!-- <img src="../assets/images/faces/face1.jpg" alt="profile"> -->
					<img src="https://imgcdn.stablediffusionweb.com/2024/11/29/ee8eaf23-2480-4bd4-bd6d-a93b0447c662.jpg"
						alt="profile">
					<!--change to offline or busy as needed-->
				</div>
				<div class="nav-profile-text d-flex ms-0 mb-3 flex-column">
					<span class="fw-semibold mb-1 mt-2 text-center">{{ Auth::user()->full_name }}</span>
					<span class="text-secondary icon-sm text-center">{{ Auth::user()->user_name }}</span>
				</div>
			</a>
		</li>
		<li class="nav-item pt-3">
			<a class="nav-link d-block" href="../index.html">
				<!-- <img class="sidebar-brand-logomini" src="../assets/images/logo-mini.svg" alt=""> -->
				<div class="small fw-light pt-1">Admin Panel</div>
			</a>
			<form class="d-flex align-items-center" action="#">
				<div class="input-group">
					<div class="input-group-prepend">
						<i class="input-group-text border-0 mdi mdi-magnify"></i>
					</div>
					<input type="text" class="form-control border-0" placeholder="Search">
				</div>
			</form>
		</li>
		<li class="pt-2 pb-1">
			<span class="nav-item-head">Danh mục</span>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="{{ route('admin.dashboard.showDashboardPage') }}">
				<i class="mdi mdi-compass-outline menu-icon"></i>
				<span class="menu-title">Dashboard</span>
			</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="{{ route('admin.orders.showOrdersPage') }}">
				<i class="menu-icon mdi mdi-cart"></i>
				<span class="menu-title">Quản lý đơn hàng</span>
			</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="{{ route('admin.products.index') }}">
				<i class="menu-icon mdi mdi-flower"></i>
				<span class="menu-title">Quản lý sản phẩm</span>
			</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="/admin/users">
				<i class="menu-icon mdi mdi-account-multiple"></i>
				<span class="menu-title">Quản lý người dùng</span>
			</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="{{ route('admin.claims.index') }}">
				<i class="menu-icon mdi mdi-backup-restore"></i>
				<span class="menu-title">Tiếp nhận đổi/trả hàng</span>
			</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="{{ route('admin.vouchers.showVouchersPage') }}">
				<i class="menu-icon mdi mdi-tag-multiple"></i>
				<span class="menu-title">Quản lý mã giảm giá</span>
			</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="/admin/returns">
				<i class="menu-icon mdi mdi-cogs"></i>
				<span class="menu-title">Cài đặt</span>
			</a>
		</li>
		<li class="pt-2 pb-1">
			<span class="nav-item-head">UI Elements</span>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="collapse" href="#ui-basic" aria-expanded="false"
				aria-controls="ui-basic">
				<i class="mdi mdi-crosshairs-gps menu-icon"></i>
				<span class="menu-title">UI Elements</span>
				<i class="menu-arrow"></i>
			</a>
			<div class="collapse" id="ui-basic">
				<ul class="nav flex-column sub-menu">
					<li class="nav-item"> <a class="nav-link" href="../pages/ui-features/buttons.html">Buttons</a></li>
					<li class="nav-item"> <a class="nav-link" href="../pages/ui-features/dropdowns.html">Dropdowns</a>
					</li>
					<li class="nav-item"> <a class="nav-link" href="../pages/ui-features/typography.html">Typography</a>
					</li>
				</ul>
			</div>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="collapse" href="#icons" aria-expanded="false" aria-controls="icons">
				<i class="mdi mdi-contacts menu-icon"></i>
				<span class="menu-title">Icons</span>
				<i class="menu-arrow"></i>
			</a>
			<div class="collapse" id="icons">
				<ul class="nav flex-column sub-menu">
					<li class="nav-item"> <a class="nav-link" href="../pages/icons/font-awesome.html">Font Awesome</a>
					</li>
				</ul>
			</div>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="collapse" href="#forms" aria-expanded="false" aria-controls="forms">
				<i class="mdi mdi-format-list-bulleted menu-icon"></i>
				<span class="menu-title">Forms</span>
				<i class="menu-arrow"></i>
			</a>
			<div class="collapse" id="forms">
				<ul class="nav flex-column sub-menu">
					<li class="nav-item"> <a class="nav-link" href="../pages/forms/basic_elements.html">Form
							Elements</a></li>
				</ul>
			</div>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="collapse" href="#charts" aria-expanded="false" aria-controls="charts">
				<i class="mdi mdi-chart-bar menu-icon"></i>
				<span class="menu-title">Charts</span>
				<i class="menu-arrow"></i>
			</a>
			<div class="collapse" id="charts">
				<ul class="nav flex-column sub-menu">
					<li class="nav-item"> <a class="nav-link" href="../pages/charts/chartjs.html">ChartJs</a></li>
				</ul>
			</div>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="collapse" href="#tables" aria-expanded="false" aria-controls="tables">
				<i class="mdi mdi-table-large menu-icon"></i>
				<span class="menu-title">Tables</span>
				<i class="menu-arrow"></i>
			</a>
			<div class="collapse" id="tables">
				<ul class="nav flex-column sub-menu">
					<li class="nav-item"> <a class="nav-link" href="../pages/tables/basic-table.html">Basic Table</a>
					</li>
				</ul>
			</div>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="collapse" href="#auth" aria-expanded="false" aria-controls="auth">
				<i class="mdi mdi-lock menu-icon"></i>
				<span class="menu-title">User Pages</span>
				<i class="menu-arrow"></i>
			</a>
			<div class="collapse" id="auth">
				<ul class="nav flex-column sub-menu">
					<li class="nav-item"> <a class="nav-link" href="../pages/samples/blank-page.html"> Blank Page </a>
					</li>
					<li class="nav-item"> <a class="nav-link" href="../pages/samples/login.html"> Login </a></li>
					<li class="nav-item"> <a class="nav-link" href="../pages/samples/register.html"> Register </a></li>
					<li class="nav-item"> <a class="nav-link" href="../pages/samples/error-404.html"> 404 </a></li>
					<li class="nav-item"> <a class="nav-link" href="../pages/samples/error-500.html"> 500 </a></li>
				</ul>
			</div>
		</li>
		<li class="nav-item pt-3">
			<a class="nav-link" href="../docs/documentation.html" target="_blank">
				<i class="mdi mdi-file-document-box menu-icon"></i>
				<span class="menu-title">Documentation</span>
			</a>
		</li>
	</ul>
</nav>