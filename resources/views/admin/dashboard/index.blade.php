@extends("admin.layouts.layout")
@section("title", "Admin dashboard")

@section('style')
<link rel="stylesheet" href="{{ asset('css/admin/dashboard/charts.css') }}">
@endsection

@section('body-script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('js/admin/dashboard/charts.js') }}"></script>
@endsection

@section('content')
<h2>{{ \Carbon\Carbon::now()->format('F j, Y g:i A') }}</h2>

<div class="section-divider">
	<h2 class="section-title">
		<i class="fa-solid fa-lg fa-chart-line" style="color: #28a745;"></i>
		Hiệu suất bán hàng
	</h2>
</div>
<div class="container-fluid">

	<!-- Stats Cards Row -->
	<div class="row">
		<!-- Orders Card -->
		<div class="col-sm-4 stretch-card grid-margin">
			<div class="card">
				<div class="card-body">
					<div class="stats-card">
						<h6 class="stats-title">Tổng doanh thu</h6>
						<div class="stats-value">
							<span id="today-sales"></span>
						</div>
						<div class="growth-indicator">
							<span class="growth-data">
								<span id="sales-indicator" class="me-1"></span>
								<span id="sales-growth" class="me-1"></span>
							</span>
							<span class="text-muted">so với ngày hôm qua</span>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Revenue Card -->
		<div class="col-sm-4 stretch-card grid-margin">
			<div class="card">
				<div class="card-body">
					<div class="stats-card">
						<h6 class="stats-title">Tổng đơn đặt hàng</h6>
						<div class="stats-value">
							<span id="today-orders"></span>
						</div>
						<div class="growth-indicator">
							<span class="growth-data">
								<span id="orders-indicator"></span>
								<span id="orders-growth" class=""></span>
							</span>
							<span class="text-muted">so với ngày hôm qua</span>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Customers Card -->
		<div class="col-sm-4 stretch-card grid-margin">
			<div class="card">
				<div class="card-body">
					<div class="stats-card">
						<h6 class="stats-title">Tổng khách hàng mới</h6>
						<div class="stats-value">
							<span id="total-customers"></span>
						</div>
						<div class="growth-indicator">
							<span class="growth-data">
								<span id="customers-indicator"></span>
								<span id="customers-growth" class=""></span>
							</span>
							<span class="text-muted">so với ngày hôm qua</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Sales Performance Chart Row -->
	<div class="row">
		<div class="col-sm-12 stretch-card grid-margin">
			<div class="card">
				<div class="card-body">
					<div class="d-flex justify-content-between">
						<div class="card-title">
							<small style="font-size: 18px; color: #2A6548;">Trong vòng 2 tuần qua</small>
						</div>
						<div class="d-flex text-muted font-20">
							<i class="mdi mdi-printer mouse-pointer"></i>
							<i class="mdi mdi-help-circle-outline ms-2 mouse-pointer"></i>
						</div>
					</div>
					<div class="line-chart-wrapper">
						<canvas id="salesChart" width="400" height="200"></canvas>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="section-divider position-relative">
	<h2 class="section-title">
		<i class="fa-solid fa-lg fa-crown" style="color: #28a745;"></i>
		Sản phẩm bán chạy
	</h2>
	<a href="/admin/products" class="d-flex position-absolute text-secondary" 
		style="right: 20px; bottom: 17px; font-size: 1rem; opacity: 0.65;">
		<h class="" style="text-decoration: none !important;">Sản phẩm</h4>
		<i class="bi bi-caret-right"></i>
	</a>
</div>

<div class="container-fluid">
	<div class="card">
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-hover" id="top-selling-table">
					<thead>
						<tr>
							<th></th>
							<th>Hình ảnh</th>
							<th>Mã sản phẩm</th>
							<th>Tên sản phẩm</th>
							<th class="text-end">Rating</th>
							<th class="text-end">Số lượng đã bán</th>
						</tr>
					</thead>
					<tbody>
						<!-- Data will be populated by JavaScript -->
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
@endsection