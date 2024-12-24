@extends('admin.layouts.layout')

@section('title', 'Quản lý đổi/trả hàng')

@section('style')
<link rel="stylesheet" href="{{ asset('css/admin/claims/index.css') }}">
@endsection

@section('lib')
<!-- SweetAlert2 for modals -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection

@section('content')
<div class="content-wrapper">
    <!-- Add Snowflakes -->
    <div id="snowflakes"></div>
    
    <!-- Add Statistics Section -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Thống kê yêu cầu theo trạng thái</h4>
                    <div class="btn-group">
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-period="week">7 ngày</button>
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-period="month">30 ngày</button>
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-period="year">365 ngày</button>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>Top 5 sản phẩm được yêu cầu đổi/trả nhiều nhất</h4>
                </div>
                <div class="card-body">
                    <canvas id="productsChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Page Header -->
    <div class="page-header">
        <h3 class="page-title">Quản lý yêu cầu đổi/trả hàng</h3>
    </div>
    <div class="row">
        <!-- Pending Requests Section -->
        <div class="col-12 mb-4">
            <h4 class="text-muted mb-4">Tiếp nhận yêu cầu (phê duyệt/từ chối)</h4>
            <!-- Refund Requests Table -->
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>Yêu cầu Trả hàng, hoàn tiền</h4>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Sản phẩm</th>
                                    <th>Khách hàng</th>
                                    <th>Thời gian yêu cầu <i class="mdi {{ request('type') === 'refund' && request('direction') === 'asc' ? 'mdi-arrow-up' : 'mdi-arrow-down' }} sort-icon" 
									data-type="refund" role="button"></i></th>
									<th>Trạng thái</th>
                                    <th>Mã đơn</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($refundRequests as $request)
                                <tr>
                                    <td>{{ $request->return_refund_id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $request->order_item->product->product_images->first()->product_image_url ?? asset('images/placeholder-plant.jpg') }}" 
                                                class="product-image me-3" alt="Image">
											<div class="d-flex flex-column">
                                                <h6 class="mb-0">{{ $request->order_item->product->name }}</h6>
                                                <small class="text-muted mt-1">Số lượng: {{ $request->quantity }}</small><br>
                                                <small class="mt-2">Số tiền: {{ number_format($request->order_item->total_price, 0, ',', '.') }}₫</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            {{ $request->user->full_name }}
                                            <br>
                                            <small class="text-muted">{{ $request->user->email }}</small>
                                        </div>
                                    </td>
                                    <td>{{ $request->created_at->format('d/m/Y H:i') }}</td>
									<td class="editable-cell" data-request-id="{{ $request->return_refund_id }}" data-field="status" data-status="{{ $request->status }}" data-status-level=1>
										@switch ($request->status)
											@case('pending')
												<span class="badge badge-warning">Chờ xử lý</span>
												@break
											@case('accepted')
												<span class="badge badge-success">Đã chấp nhận</span>
												@break
											@case('rejected')
												<span class="badge badge-danger">Đã từ chối</span>
												@break
											@case('received')
												<span class="badge badge-info">Đã nhận hàng</span>
												@break
										@endswitch
									</td>
                                    <td>
                                        <span class="badge badge-{{ 
                                            (function() {
                                                switch(random_int(0, 3)) {
                                                case 0:
                                                    echo 'pending';
                                                    break;
                                                case 1:
                                                    echo 'accepted';
                                                    break;
                                                case 2:
                                                    echo 'rejected';
                                                    break;
                                                case 3:
                                                    echo 'received';
                                                    break;
                                                }
                                            })()
                                        }} editable-cell" data-request-id="{{ $request->return_refund_id }}" data-field="status">
                                            <span class="order-id text-decoration-underline" data-order-id="{{ $request->order_item->order_id }}">#${{ $request->order_item->order_id }}</span>
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-outline-info btn-sm view-details-btn" data-request-id="{{ $request->return_refund_id }}"><i class="mdi mdi-eye"></i></button>
										<button class="btn btn-sm btn-outline-success quick-accept-btn" data-request-id="{{ $request->return_refund_id }}"><i class="mdi mdi-check-all"></i></button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <!-- Pagination -->
                        <!-- {{ $refundRequests->links() }} -->
						@include('admin.layouts.pagination', ['paginator' => $refundRequests, 'itemName' => 'Yêu cầu trả hàng'])
                    </div>
                </div>
            </div>
            <!-- Return Requests Table -->
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>Yêu cầu Đổi hàng</h4>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Sản phẩm</th>
                                    <th>Khách hàng</th>
                                    <th>Thời gian yêu cầu <i class="mdi {{ request('type') === 'return' && request('direction') === 'asc' ? 'mdi-arrow-up' : 'mdi-arrow-down' }} sort-icon" 
									data-type="return" role="button"></i></th>
									<th>Trạng thái </th>
                                    <th>Mã đơn</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($returnRequests as $request)
                                <tr>
                                    <td>{{ $request->return_refund_id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $request->order_item->product->product_images->first()->product_image_url ?? asset('images/placeholder-plant.jpg') }}" 
                                                class="product-image me-3" alt="Product image">
                                            <div>
                                                <h6 class="mb-0">{{ $request->order_item->product->name }}</h6>
                                                <small class="text-muted">Số lượng: {{ $request->quantity }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            {{ $request->user->full_name }}
                                            <br>
                                            <small class="text-muted">{{ $request->user->email }}</small>
                                        </div>
                                    </td>
                                    <td>{{ $request->created_at->format('d/m/Y H:i') }}</td>
									<td class="editable-cell" data-request-id="{{ $request->return_refund_id }}" data-field="status" data-status="{{ $request->status }}" data-status-level="1">
										@switch ($request->status)
											@case('pending')
												<span class="badge badge-warning">Chờ xử lý</span>
												@break
											@case('accepted')
												<span class="badge badge-success">Đã chấp nhận</span>
												@break
											@case('rejected')
												<span class="badge badge-danger">Đã từ chối</span>
												@break
											@case('received')
												<span class="badge badge-info">Đã nhận hàng</span>
												@break
										@endswitch
									</td>
                                    <td>
                                        <span class="badge badge-{{ 
                                            (function() {
                                                switch(random_int(0, 3)) {
                                                case 0:
                                                    echo 'pending';
                                                    break;
                                                case 1:
                                                    echo 'accepted';
                                                    break;
                                                case 2:
                                                    echo 'rejected';
                                                    break;
                                                case 3:
                                                    echo 'received';
                                                    break;
                                                }
                                            })()
                                        }} data-request-id="{{ $request->return_refund_id }}" data-field="status">
                                            <span class="order-id text-decoration-underline" data-order-id="{{ $request->order_item->order_id }}">#${{ $request->order_item->order_id }}</span>
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-outline-info btn-sm view-details-btn" data-request-id="{{ $request->return_refund_id }}"><i class="mdi mdi-eye"></i></button>
										<button class="btn btn-sm btn-outline-success quick-accept-btn" data-request-id="{{ $request->return_refund_id }}"><i class="mdi mdi-check-all"></i></button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <!-- Pagination -->
						@include('admin.layouts.pagination', ['paginator' => $returnRequests, 'itemName' => 'Yêu cầu đổi hàng', 'compact' => true])
                    </div>
                </div>
            </div>
        </div>

        <!-- Processing Requests Section -->
        <div class="col-12">
            <h4 class="text-muted mb-4">Đang xử lý</h4>
            <div class="row processing-claims">
                <!-- Processing Refund Requests -->
                <div class="col-lg-6 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-header">
                            <h4>Yêu cầu Trả hàng, hoàn tiền</h4>
                        </div>
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Sản phẩm</th>
										<th>Tình trạng</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($processedRefundRequests as $request)
                                    <tr class="processed-row" role="button" data-request-id="{{ $request->return_refund_id }}">
                                        <td>#{{ $request->return_refund_id }}</td>
										<td>
											<div class="d-flex align-items-center">
												<img src="{{ $request->order_item->product->product_images->first()->product_image_url ?? asset('images/placeholder-plant.jpg') }}" 
													class="product-image me-3" alt="Product image">
												<div>
													<h6 class="mb-1">{{ $request->order_item->product->name }}</h6>
													<small class="text-muted d-block">{{ $request->created_at->format('d/m/Y H:i') }}</small>
												</div>
											</div>
										</td>
										<td class="editable-cell" style="padding-left: 20px;" data-request-id="{{ $request->return_refund_id }}" data-field="status" data-status-level=2 data-status="{{ $request->status }}">
											<span class="badge badge-{{ $request->status }} mt-1">
												{{ $request->status === 'rejected' ? 'Đã từ chối' : 'Đã nhận hàng' }}
											</span>
										</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @include('admin.layouts.pagination', ['paginator' => $processedRefundRequests, 'itemName' => 'Yêu cầu đã xử lý', 'compact' => true])
                        </div>
                    </div>
                </div>

                <!-- Processing Return Requests -->
                <div class="col-lg-6 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-header">
                            <h4>Yêu cầu Đổi hàng</h4>
                        </div>
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Sản phẩm</th>
										<th>Tình trạng</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($processedReturnRequests as $request)
                                    <tr class="processed-row" role="button" data-request-id="{{ $request->return_refund_id }}">
                                        <td>#{{ $request->return_refund_id }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $request->order_item->product->product_images->first()->product_image_url ?? asset('images/placeholder-plant.jpg') }}" 
                                                    class="product-image me-3" alt="Product image">
                                                <div>
                                                    <h6 class="mb-1">{{ $request->order_item->product->name }}</h6>
                                                    <small class="text-muted d-block">{{ $request->created_at->format('d/m/Y H:i') }}</small>
                                                </div>
                                            </div>
                                        </td>
										<td class="editable-cell" data-field="status" data-request-id="{{ $request->return_refund_id }}" data-status-level=2 data-status="{{ $request->status }}">
											<span class="badge badge-{{ $request->status }} mt-1">
												{{ $request->status === 'rejected' ? 'Đã từ chối' : 'Đã nhận hàng' }}
											</span>
										</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @include('admin.layouts.pagination', ['paginator' => $processedReturnRequests, 'itemName' => 'Yêu cầu đã xử lý', 'compact' => true])
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Completed Requests Section -->
        <div class="col-12">
            <h4 class="text-muted mb-4">Đã hoàn thành</h4>
            <div class="row completed-claims">
                <!-- Completed Refund Requests -->
                <div class="col-lg-6 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-header">
                            <h4>Đã hoàn tiền</h4>
                        </div>
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Sản phẩm</th>
										<th>Tình trạng</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($completedRefundRequests as $request)
                                    <tr class="processed-row" role="button" data-request-id="{{ $request->return_refund_id }}">
                                        <td>#{{ $request->return_refund_id }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $request->order_item->product->product_images->first()->product_image_url ?? asset('images/placeholder-plant.jpg') }}" 
                                                    class="product-image me-3" alt="Product image">
                                                <div>
                                                    <h6 class="mb-1">{{ $request->order_item->product->name }}</h6>
                                                    <small class="text-muted d-block">{{ $request->created_at->format('d/m/Y H:i') }}</small>
                                                </div>
                                            </div>
                                        </td>
										<td class="editable-cell" data-field="status" data-request-id="{{ $request->return_refund_id }}" data-status-level=3 data-status="{{ $request->status }}">
											<span class="badge badge-info mt-1">
												Đã hoàn tiền
											</span>
										</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @include('admin.layouts.pagination', ['paginator' => $completedRefundRequests, 'itemName' => 'yêu cầu đã được hoàn tiền', 'compact' => true])
                        </div>
                    </div>
                </div>

                <!-- Completed Return Requests -->
                <div class="col-lg-6 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-header">
                            <h4>Đã đổi hàng</h4>
                        </div>
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Sản phẩm</th>
										<th>Tình trạng</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($completedReturnRequests as $request)
                                    <tr class="processed-row" role="button" data-request-id="{{ $request->return_refund_id }}">
                                        <td>#{{ $request->return_refund_id }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $request->order_item->product->product_images->first()->product_image_url ?? asset('images/placeholder-plant.jpg') }}" 
                                                    class="product-image me-3" alt="Product image">
                                                <div>
                                                    <h6 class="mb-1">{{ $request->order_item->product->name }}</h6>
                                                    <small class="text-muted d-block">{{ $request->created_at->format('d/m/Y H:i') }}</small>
                                                </div>
                                            </div>
                                        </td>
										<td class="editable-cell" data-field="status" data-request-id="{{ $request->return_refund_id }}" data-status-level=3 data-status="{{ $request->status }}">
                                            <span class="badge badge-info mt-1">
                                                Đã đổi hàng
                                            </span>
										</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
							@include('admin.layouts.pagination', ['paginator' => $processedReturnRequests, 'itemName' => 'yêu cầu đã hoàn tất đổi hàng', 'compact' => true])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Request Details Modal -->
<div class="modal fade vintage-modal" id="requestDetailsModal" tabindex="-1" aria-labelledby="requestDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title vintage-title">Chi tiết yêu cầu</h5>
                <button type="button" class="btn-close vintage-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body vintage-body" id="request-details-content">
                <!-- Request details will be loaded here via AJAX -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn reject-btn text-muted vintage-btn" data-request-id="">Từ chối <span class="mdi mdi-account-alert text-muted"></span></button>
                <button type="button" class="btn btn-outline accept-btn fw-bold vintage-btn vintage-btn-primary" style="font-size: 0.9rem;" data-request-id="">Chấp nhận <span class="mdi mdi-check-all"></span></button>
            </div>
        </div>
    </div>
</div>

<!-- Order details modal -->
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
<script src="{{ asset('js/admin/claims/index.js') }}"></script>
@endsection
