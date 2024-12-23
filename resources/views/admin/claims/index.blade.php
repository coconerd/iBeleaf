@extends('admin.layouts.layout')

@section('title', 'Quản lý đổi/trả hàng')

@section('style')
<link rel="stylesheet" href="{{ asset('css/admin/claims/index.css') }}">
@endsection

@section('lib')
<!-- SweetAlert2 for modals -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('content')
<div class="content-wrapper">
    <!-- Page Header -->
    <div class="page-header">
        <h3 class="page-title">Quản lý yêu cầu đổi/trả hàng</h3>
    </div>
    <div class="row">
        <!-- Pending Requests Section -->
        <div class="col-12 mb-4">
            <h4 class="text-muted mb-4">Chưa xử lý</h4>
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
									<td class="editable-cell" data-request-id="{{ $request->return_refund_id }}" data-field="status">
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
                        {{ $refundRequests->links() }}
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
									<td class="editable-cell" data-request-id="{{ $request->return_refund_id }}" data-field="status">
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
                        {{ $returnRequests->links() }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Processed Requests Section -->
        <div class="col-12">
            <h4 class="text-muted mb-4">Đã xử lý</h4>
            <div class="row processed-claims">
                <!-- Processed Refund Requests -->
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
                                                    <span class="badge badge-{{ $request->status }} mt-1">
                                                        {{ $request->status === 'rejected' ? 'Đã từ chối' : 'Đã nhận hàng' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $processedRefundRequests->links() }}
                        </div>
                    </div>
                </div>

                <!-- Processed Return Requests -->
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
                                                    <span class="badge badge-{{ $request->status }} mt-1">
                                                        {{ $request->status === 'rejected' ? 'Đã từ chối' : 'Đã nhận hàng' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $processedReturnRequests->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Request Details Modal -->
<div class="modal fade" id="requestDetailsModal" tabindex="-1" aria-labelledby="requestDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chi tiết yêu cầu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="request-details-content">
                <!-- Request details will be loaded here via AJAX -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn reject-btn text-muted" style="font-size: 0.9rem;" data-request-id="">Từ chối <span class="mdi mdi-account-alert text-muted"></span></button>
                <button type="button" class="btn btn-outline accept-btn fw-bold" style="font-size: 0.9rem;" data-request-id="">Chấp nhận <span class="mdi mdi-check-all"></span></button>
            </div>
        </div>
    </div>
</div>

<!-- Order details modal -->
<div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-labelledby="orderDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chi tiết đơn hàng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <div class="modal-body">
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
