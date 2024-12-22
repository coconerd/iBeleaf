@extends('admin.layouts.layout')

@section('title', 'Quản lý Voucher')

@section('lib')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Add flatpickr -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<!-- Optional: Add Vietnamese locale -->
<script src="https://npmcdn.com/flatpickr/dist/l10n/vn.js"></script>
@endsection

@section('style')
<link rel="stylesheet" href="{{ asset('css/admin/vouchers/index.css') }}">
@endsection

@section('head-script')
<script>
	function showAlert(type, message) {
		const Toast = Swal.mixin({
			toast: true,
			position: 'top-end',
			showConfirmButton: false,
			timer: 3000,
			timerProgressBar: true
		});

		Toast.fire({
			icon: type,
			title: message
		});
	}
</script>
@endsection

@section('content')
@if (session('success'))
	<script>showAlert('success', '{{ session('success') }}')</script>
	{{ session()->forget('success') }}
@elseif (session('error'))
	<script>showAlert('error', '{{ session('error') }}')</script>
	{{ session()->forget('error') }}
@endif
<div class="container-fluid vouchers-container">
	<div class="dashboard-header mb-4">
		<div class="header-content">
			<h2 class="modern-title me-5">
				<i class="fas fa-ticket-alt text-success"></i>
				Quản lý Voucher
				<span class="christmas-decoration">
					<i class="fas fa-holly-berry text-danger"></i>
				</span>
			</h2>
			<div class="ms-5 d-flex gap-2">
				<div class="search-box d-flex align-items-center">
					<select class="form-select me-2" id="statusFilter">
						<option value="">Trạng thái</option>
						<option value="not_yet">Chưa hiệu lực</option>
						<option value="active">Còn hiệu lực</option>
						<option value="expired">Hết hạn</option>
					</select>
					<select class="form-select me-2" id="searchType">
						<option value="voucher_name">Tìm theo mã</option>
						<option value="description">Tìm theo mô tả</option>
					</select>
					<input type="text" id="searchInput" class="form-control" 
						placeholder="Tìm kiếm voucher...">
					<button id="searchButton" class="btn btn-success ms-2">
						<i class="fas fa-search"></i>
					</button>
				</div>
				<button id="addVoucherBtn" class="btn modern-btn" data-bs-toggle="modal" data-bs-target="#voucherModal">
					<i class="fas fa-plus"></i> Thêm Voucher
				</button>
			</div>
		</div>
		<div class="snow-overlay"></div>
	</div>

	<div class="modern-card">
		<div class="card-body p-0">
			<div class="table-responsive">
				<table class="table modern-table">
					<thead>
						<tr>
							 <th>
								Mã 
								<i class="mdi sort-icon mdi-arrow-up-down" data-sort="voucher_name"></i>
							</th>
							<th>Mô tả</th>
							<th>Loại</th>
							<th>Giá trị</th>
							<th>
								Bắt đầu 
								<i class="mdi sort-icon mdi-arrow-up-down ms-1" data-sort="voucher_start_date"></i>
								- &nbsp;Kết thúc
								<i class="mdi sort-icon mdi-arrow-up-down ms-1" data-sort="voucher_end_date"></i>
							</th>
							<th>Trạng thái</th>
							<th>Thao tác</th>
						</tr>
					</thead>
					<tbody>
						@foreach($vouchers as $voucher)
							<tr class="voucher-row">
								<td class="voucher-code">{{ $voucher->voucher_name }}</td>
								<td>{{ $voucher->description }}</td>
								<td>
									@if ($voucher->voucher_type == 'percentage')
										Coupon (%)
									@elseif ($voucher->voucher_type == 'cash')
										Voucher (đ)
									@endif
								</td>
								<td>
									@if ($voucher->voucher_type == 'percentage')
										{{ $voucher->value }}%
									@elseif ($voucher->voucher_type == 'cash')
										{{ number_format($voucher->value) }}đ
									@endif
								</td>
								<td>
									{{ $voucher->voucher_start_date->format('d/m/Y') }} -
									{{ $voucher->voucher_end_date->format('d/m/Y') }}
								</td>
								<td>
									@if (now() < $voucher->voucher_start_date)
										<span class="badge bg-neutral">Chưa hiệu lực</span>
									@elseif (now() < $voucher->voucher_end_date)
										<span class="badge bg-success">Còn hiệu lực</span>
									@else
										<span class="badge bg-danger">Hết hạn</span>
									@endif
								</td>
								<td>
									<div class="action-buttons">
										<button class="btn btn-icon edit-voucher" data-id="{{ $voucher->voucher_id }}"
											data-bs-toggle="modal" data-bs-target="#voucherModal">
											<i class="fas fa-edit"></i>
										</button>
										<button class="btn btn-icon delete-voucher" data-id="{{ $voucher->voucher_id }}">
											<i class="fas fa-trash"></i>
										</button>
									</div>
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
			<!-- <div class="d-flex justify-content-between align-items-center px-4 py-3">
				<div class="showing-text text-muted">
					Hiển thị {{ $vouchers->firstItem() ?? 0 }}-{{ $vouchers->lastItem() ?? 0 }} 
					trong tổng số {{ $vouchers->total() ?? 0 }} voucher
				</div>
				<div class="vintage-pagination">
					{{ $vouchers->onEachSide(1)->links('pagination::bootstrap-4') }}
				</div>
			</div> -->
			@include('admin.layouts.pagination', ['paginator' => $vouchers, 'itemName' => 'Mã giảm giá'])
		</div>
	</div>
</div>
@endsection

@include('admin.vouchers.modals.voucher-form')

@section('body-script')
<script src="{{ asset('js/admin/vouchers/index.js') }}"></script>
@endsection