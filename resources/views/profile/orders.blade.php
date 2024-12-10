@section('style')
<sytle>
	.orders-container {
	background-color: red;
	}
</sytle>
@endsection
<div class="orders-container">
	<div class="container-fluid">
		<!-- Tabs Navigation -->
		<ul class="nav nav-tabs mb-4" id="orderTabs" role="tablist">
			<li class="nav-item" role="presentation">
				<button class="nav-link active" id="all-orders-tab" data-bs-toggle="tab" data-bs-target="#all-orders"
					type="button" role="tab" aria-controls="all-orders" aria-selected="true">Tất cả</button>
			</li>
			<li class="nav-item" role="presentation">
				<button class="nav-link" id="pending-payment-tab" data-bs-toggle="tab" data-bs-target="#pending-payment"
					type="button" role="tab" aria-controls="pending-payment" aria-selected="false">Chờ thanh
					toán</button>
			</li>
			<li class="nav-item" role="presentation">
				<button class="nav-link" id="shipping-tab" data-bs-toggle="tab" data-bs-target="#shipping" type="button"
					role="tab" aria-controls="shipping" aria-selected="false">Vận chuyển</button>
			</li>
			<li class="nav-item" role="presentation">
				<button class="nav-link" id="completed-tab" data-bs-toggle="tab" data-bs-target="#completed"
					type="button" role="tab" aria-controls="completed" aria-selected="false">Hoàn thành</button>
			</li>
			<li class="nav-item" role="presentation">
				<button class="nav-link" id="cancelled-tab" data-bs-toggle="tab" data-bs-target="#cancelled"
					type="button" role="tab" aria-controls="cancelled" aria-selected="false">Đã hủy</button>
			</li>
			<li class="nav-item" role="presentation">
				<button class="nav-link" id="returns-tab" data-bs-toggle="tab" data-bs-target="#returns" type="button"
					role="tab" aria-controls="returns" aria-selected="false">Trả hàng/Hoàn tiền</button>
			</li>
		</ul>

		<!-- Tabs Content -->
		<div class="tab-content" id="orderTabsContent">
			<!-- All Orders -->
			<div class="tab-pane fade show active" id="all-orders" role="tabpanel" aria-labelledby="all-orders-tab">
				@foreach($orders as $order)
					<div class="card mb-3">
						<div class="card-header d-flex justify-content-between align-items-center">
							<div>
								<strong>{{ $order->shop_name }}</strong>
								<span class="badge bg-primary ms-2">{{ $order->status }}</span>
							</div>
							<div>
								<button class="btn btn-outline-secondary btn-sm">Chat</button>
								<button class="btn btn-outline-secondary btn-sm">Xem Shop</button>
							</div>
						</div>
						<div class="card-body">
							<div class="d-flex">
								<img src="{{ $order->product_image }}" alt="{{ $order->product_name }}"
									class="img-fluid rounded me-3" style="width: 100px; height: 100px;">
								<div>
									<p class="mb-1">{{ $order->product_name }}</p>
									<small class="text-muted">Phân loại hàng: {{ $order->product_variant }}</small><br>
									<small>x{{ $order->quantity }}</small>
								</div>
								<div class="ms-auto text-end">
									<p class="mb-1 fw-bold text-danger">{{ number_format($order->price, 0, '.', ',') }}₫</p>
									@if ($order->original_price)
										<small
											class="text-decoration-line-through text-muted">{{ number_format($order->original_price, 0, '.', ',') }}₫</small>
									@endif
								</div>
							</div>
						</div>
						<div class="card-footer d-flex justify-content-between align-items-center">
							<small>Vui lòng chỉ nhấn "Đã nhận được hàng" khi đơn hàng đã được giao.</small>
							<div>
								@if ($order->status === 'Shipping')
									<button class="btn btn-outline-primary btn-sm">Đã Nhận Hàng</button>
									<button class="btn btn-outline-secondary btn-sm">Liên Hệ Người Bán</button>
								@elseif ($order->status === 'Completed')
									<button class="btn btn-outline-success btn-sm">Đánh Giá</button>
									<button class="btn btn-outline-secondary btn-sm">Yêu Cầu Trả Hàng/Hoàn Tiền</button>
									<button class="btn btn-outline-secondary btn-sm">Thêm</button>
								@endif
							</div>
						</div>
					</div>
				@endforeach
			</div>

			<!-- Other Tabs (Repeat similar structure for other statuses) -->
			<div class="tab-pane fade" id="pending-payment" role="tabpanel" aria-labelledby="pending-payment-tab">
				<!-- Pending Payment Orders -->
			</div>
			<div class="tab-pane fade" id="shipping" role="tabpanel" aria-labelledby="shipping-tab">
				<!-- Shipping Orders -->
			</div>
			<div class="tab-pane fade" id="completed" role="tabpanel" aria-labelledby="completed-tab">
				<!-- Completed Orders -->
			</div>
			<div class="tab-pane fade" id="cancelled" role="tabpanel" aria-labelledby="cancelled-tab">
				<!-- Cancelled Orders -->
			</div>
			<div class="tab-pane fade" id="returns" role="tabpanel" aria-labelledby="returns-tab">
				<!-- Returns/Refund Orders -->
			</div>
		</div>
	</div>
	@if($orders->isEmpty())
		<p>Bạn chưa có đơn hàng nào.</p>
	@else
		@foreach($orders as $order)
			<div class="order-card">
				<!-- Display order details -->
				<div class="card-header">
					<strong>{{ $order->shop_name }}</strong>
					<span class="badge bg-primary">{{ $order->status }}</span>
				</div>
				<div class="card-body">
					<!-- ...existing code to display product information... -->
				</div>
			</div>
		@endforeach
	@endif
</div>