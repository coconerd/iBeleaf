@extends('admin.layouts.layout')

@section('title', 'Quản lý đơn hàng')

@section('style')
<link rel="stylesheet" href="{{ asset('css/admin/orders/index.css') }}">
@endsection

@section('lib')
<!-- Flatpickr -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
<!-- Add Snowflakes -->
<div id="snowflakes"></div>

<!-- Alerts -->
<div class="mt-2 alert alert-success visually-hidden" style="position: fixed; top: 10%; right: 2%; z-index: 1000;">
</div>
<div class="mt-2 alert alert-danger visually-hidden" style="position: fixed; top: 10%; right: 2%; z-index: 1000;"> </div>
<!-- End Alerts -->

<div class="page-header">
    <h3 class="page-title fs-5">Quản lý/tiếp nhận các đơn hàng của khách hàng <span class="christmas-icon">🎄</span></h3>
</div>

<div class="content-wrapper">
    <!-- Add Statistics Section -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Thống kê doanh thu</h4>
                    <div class="btn-group">
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-period="week">7 ngày</button>
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-period="month">30 ngày</button>
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-period="year">365 ngày</button>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4>Phân bố trạng thái đơn hàng</h4>
                </div>
                <div class="card-body">
                    <canvas id="orderStatusChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="orders-container">
        <div class="row">
            <div class="col-md-12 grid-margin">
                <div class="card newest-orders-card">
                    <div class="card-body">
                        <h4 class="card-title fs-5">Các đơn hàng mới nhất <span class="mdi mdi-information-box-outline"></span></h4>
                        <div class="newest-orders-list">
                            @foreach($newestOrders as $order)
                                <div class="newest-order-item">
                                    <div class="d-flex align-items-center order-header" data-order-id="{{ $order->order_id }}">
                                        <div class="order-info row w-50">
                                            <div class="col-3">
                                                <span class="order-id clickable" data-order-id="{{ $order->order_id }}">Đơn hàng #{{ $order->order_id }}</span>
                                            </div>
                                            <div class="col-6">
                                                <span class="customer-name">KH: {{ $order->user->full_name }}</span>
                                            </div>
                                        </div>
                                        <span class="ms-auto me-3 order-time">{{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i') }}</span>
                                        <span class="status-badge
                                            @switch($order->status)
                                                @case('pending') badge-pending @break
                                                @case('delivering') badge-delivering @break
                                                @case('delivered') badge-delivered @break
                                                @case('cancelled') badge-cancelled @break
                                                @default badge-default
                                            @endswitch
                                        ">
                                            @switch($order->status)
                                                @case('pending')
                                                    @if ($order->payment_method == "Banking" && $order->is_paid == 0)
                                                        Chờ KH tt. online
                                                    @else
                                                    <span class="mdi mdi-alert-box-outline"></span> Đang chờ xử lý
                                                    @endif
                                                    @break
                                                @case ('delivering')
                                                    Đang giao hàng
                                                    @break
                                                @case ('delivered')
                                                    Đã giao hàng
                                                    @break
                                                @case('cancelled')
                                                    Đã hủy
                                                    @break
                                                @default
                                                    N\A
                                                @break
                                            @endswitch
                                        </span>
                                        <i class="mdi mdi-chevron-down toggle-icon ms-2"></i>
                                    </div>
                                    <div class="order-details" id="order-details-{{ $order->order_id }}" style="display: none;">
                                        <div class="order-summary mt-3">
                                            <p><strong>Tổng tiền:</strong> {{ number_format($order->total_price, 0, ',', '.') }} ₫</p>
                                            <p><strong>Phí vận chuyển:</strong> {{ number_format($order->deliver_cost, 0, ',', '.') }} ₫</p>
                                        </div>
                                        <div class="order-items">
                                            @foreach($order->order_items as $item)
                                                <div class="order-item d-flex align-items-center mb-3">
                                                    <img src="{{ $item->product->product_images[0]->product_image_url ?? asset('images/placeholder-plant.jpg') }}" alt="{{ $item->product->name }}" class="product-image me-3">
                                                    <div>
                                                        <p class="product-name mb-1">{{ $item->product->name }}</p>
                                                        <p class="product-quantity-price mb-0">Số lượng: {{ $item->quantity }} x {{ number_format($item->product->price, 0, ',', '.') }} ₫</p>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <button class="btn btn-primary mt-2 mb-3 change-status-btn rounded" style="font-size: 0.9rem;" data-order-id="{{ $order->order_id }}">
                                            <p class="d-flex align-items-center">Cập trạng thái đang giao hàng <span class="mdi mdi-truck-delivery ms-2" style="font-size: 1.2rem;"></span></p>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 grid-margin">
                <div class="card orders-card">
                    <div class="card-body">
                        <h4 class="card-title mb-4 fs-5">Danh sách đơn hàng <span class="mdi mdi-view-list"></span></h4>
                        
                        <form method="GET" action="{{ route('admin.orders.showOrdersPage') }}" class="filter-section mb-4">
                            <div class="filter-grid">
                                <div class="form-group">
                                    <div class="date-filter-container">
                                        <div class="btn-group mb-3" role="group">
                                            <input type="radio" class="btn-check" name="dateFilterType" id="singleDate"
                                                value="single" checked>
                                            <label class="btn btn-outline" for="singleDate">Lọc theo ngày</label>
                                            <input type="radio" class="btn-check" name="dateFilterType" id="dateRange"
                                                value="range">
                                            <label class="btn btn-outline" for="dateRange">Lọc khoảng thời gian</label>
                                        </div>

                                        <div id="singleDatePicker" class="date-input-group">
                                            <input type="text" class="form-control-md" id="single_date" name="single_date"
                                                placeholder="Chọn ngày">
                                        </div>

                                        <div id="rangeDatePicker" class="date-input-group" style="display: none;">
                                            <div class="d-flex gap-3">
                                                <input type="text" class="form-control-md" id="date_start" name="date_start"
                                                    placeholder="Chọn ngày bắt đầu">
                                                <input type="text" class="form-control-md" id="date_end" name="date_end"
                                                    placeholder="Chọn ngày kết thúc">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="filter-group-container">
                                    <div class="form-group">
                                        <label for="status" class="vintage-label">Tình trạng đơn hàng</label>
                                        <select class="form-control form-control-lg vintage-select" id="status" name="status">
                                            <option value="">Tất cả</option>
                                            <option value="pending">Đang chờ xử lý</option>
                                            <option value="delivering">Đang giao hàng</option>
                                            <option value="delivered">Đã giao thành công</option>
                                            <option value="cancelled">Khách hàng đã hủy đơn</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="is_paid" class="vintage-label">Tình trạng thanh toán</label>
                                        <select class="form-control form-control-lg vintage-select" id="isPaid" name="is_paid">
                                            <option value="">Tất cả</option>
                                            <option value="1">Đã thanh toán</option>
                                            <option value="0">Chưa thanh toán</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="payment_method" class="vintage-label">Loại hình thanh toán</label>
                                        <select class="form-control form-control-lg vintage-select" id="isPaid" name="payment_method">
                                            <option value="">Tất cả</option>
                                            <option value="COD">Thanh toán khi nhận hàng (COD)</option>
                                            <option value="Banking">Thanh toán Online</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <button type="submit" class="btn vintage-btn vintage-btn-primary filter-submit">
                                    <i class="mdi mdi-filter-variant"></i> Áp dụng bộ lọc
                                </button>
                            </div>
                        </form>

                        <div class="table-container">
                            <div class="table-responsive">
                                <table class="table vintage-table">
                                    <thead>
                                        <tr>
                                            <th class="sortable" data-sort="order_id">
                                                Mã đơn hàng &nbsp;
                                                <i class="sort-icon mdi {{ request('sort') == 'order_id' ? (request('direction', 'desc') == 'asc' ? 'mdi-arrow-up' : 'mdi-arrow-down') : 'mdi-swap-vertical' }}"></i>
                                            </th>
                                            <th>Tên khách hàng</th>
                                            <th class="sortable" data-sort="total_price">
                                                Tổng tiền
                                                <i class="sort-icon mdi {{ request('sort') == 'total_price' ? (request('direction') == 'asc' ? 'mdi-arrow-up' : 'mdi-arrow-down') : 'mdi-swap-vertical' }}"></i>
                                            </th>
                                            <th class="sortable" data-sort="created_at">
                                                Ngày đặt
                                                @if (!request('sort'))
                                                    <i class="sort-icon mdi mdi-arrow-down"></i>
                                                @else
                                                    <i class="sort-icon mdi {{ request('sort') == 'created_at' ? (request('direction', 'desc') == 'asc' ? 'mdi-arrow-up' : 'mdi-arrow-down') : 'mdi-swap-vertical' }}"></i>
                                                @endif
                                            </th>
                                            <th class="sortable" data-sort="deliver_time">
                                                Ngày giao
                                                <i class="sort-icon mdi {{ request('sort') == 'deliver_time' ? (request('direction') == 'asc' ? 'mdi-arrow-up' : 'mdi-arrow-down') : 'mdi-swap-vertical' }}"></i>
                                            </th>
                                            <th>Trạng thái</th>
                                            <th>PTTT</th>
                                            <th>Đã thanh toán</th>
                                            <th>Sửa đổi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($orders as $order)
                                            <tr>
                                                <td>{{ $order->order_id }}</td>
                                                <td>{{ $order->user->full_name }}</td>
                                                <td>{{  number_format($order->total_price, 0, ',', '.')  }} ₫</td>
                                                <td>{{  \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i')  }}</td>
                                                <td>
                                                @if ($order->status === 'delivered')
                                                    {{  \Carbon\Carbon::parse($order->deliver_time)->format('d/m/Y H:i')  }}
                                                @else
                                                    Chưa giao
                                                @endif
                                                </td>
                                                <td class="editable-cell" data-order-id="{{ $order->order_id }}" data-field="status">
                                                    <span class="status-badge 
                                                        @switch($order->status)
                                                            @case('pending') badge-pending @break
                                                            @case('delivering') badge-delivering @break
                                                            @case('delivered') badge-delivered @break
                                                            @case('cancelled') badge-cancelled @break
                                                            @default badge-default
                                                        @endswitch
                                                        ">
                                                        @switch($order->status)
                                                            @case('pending')
                                                                @if ($order->payment_method == "Banking" && $order->is_paid == 0)
                                                                    Chờ KH tt. online
                                                                @else
                                                                <span class="mdi mdi-alert-box-outline"></span> Đang chờ xử lý
                                                                @endif
                                                                @break
                                                            @case ('delivering')
                                                                Đang giao hàng
                                                                @break
                                                            @case ('delivered')
                                                                Đã giao hàng
                                                                @break
                                                            @case('cancelled')
                                                                Đã hủy
                                                                @break
                                                            @default
                                                                N\A
                                                                @break
                                                        @endswitch
                                                    </span>
                                                </td>
                                                <td>
                                                    @if ($order->payment_method == "COD")
                                                        <span class="badge badge-neutral-1">COD</span>
                                                    @else
                                                        <span class="badge badge-neutral-2">Online</span>
                                                    @endif
                                                </td>
                                                <td class="editable-cell" data-order-id="{{ $order->order_id }}" data-field="is_paid">
                                                    @if ($order->is_paid == 1)
                                                        <span class="badge badge-success">Đã thanh toán <span class="mdi mdi-check-all"></span></span>
                                                    @else
                                                        <span class="badge badge-danger">Chưa thanh toán</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="#"
                                                        class="btn btn-info btn-sm view-details-btn" data-order-id='{{ $order->order_id }}'>Xem</a>
                                                    <!-- <a href="{{ route('admin.orders.edit', $order->id) }}"
                                                        class="btn btn-warning btn-sm">Edit</a> -->
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @include('admin.layouts.pagination', ['paginator' => $orders, 'itemName' => 'Đơn hàng'])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade vintage-modal" id="orderDetailsModal" tabindex="-1" aria-labelledby="orderDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title vintage-title">Chi tiết đơn hàng</h5>
                <button type="button" class="btn-close vintage-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <div class="modal-body vintage-body">
                <!-- Order details will be loaded here via AJAX -->
                <div id="order-details-content">
                    <!-- Loading indicator -->
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Đang tải...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('body-script')
<script src="{{ asset('js/admin/orders/index.js') }}"></script>
<script></script>
<script>
	const statusOptions = {
		'pending': 'Đang chờ xử lý',
		'delivering': 'Đang giao hàng',
		'delivered': 'Đã giao hàng',
		'cancelled': 'Đã hủy'
	};

	const isPaidOptions = {
		'1': 'Đã thanh toán',
		'0': 'Chưa thanh toán'
	};
</script>
@endsection