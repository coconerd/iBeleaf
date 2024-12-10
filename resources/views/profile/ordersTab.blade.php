@foreach($orders as $order)
	<div class="card mb-4">
		<div class="card-header d-flex justify-content-between align-items-center">
			<span>Trạng thái đơn hàng</span>
			<span>Giao: 14h30 23/5/2024</span>
		</div>
		<div class="card-body p-0">
			<!-- Loop through order items -->
			@foreach($order->order_items as $item)
				<div class="p-3 {{ !$loop->last ? 'border-bottom' : '' }}">
					<div class="d-flex">
						<!-- Product Image -->
						<img src="{{ $item->product->product_images[0]->product_image_url ?? asset('images/placeholder-plant.jpg') }}"
							alt="Product Image" class="me-3" style="width: 80px; height: auto;">
						<!-- Product Details -->
						<div>
							<h6 class="mb-1">{{ $item->product->short_description }}</h6>
							<p class="mb-1">Product code: {{ $item->product->code }}</p>
							<p class="mb-1">x {{ $item->quantity }}</p>
						</div>
						<!-- Pricing -->
						<div class="ms-auto text-end">
							@if ($item->product->discount_percentage > 0)
								<p style="color: #28a745;">
									<span class="text-muted text-decoration-line-through" style="font-size: 1.3rem;">
										{{ number_format($item->product->price, 0, '.', ',') }}₫
									</span>
									<br>
									<span class="text-success fw-bold" style="font-size: 1.4rem;">
										{{ number_format($item->product->price * (1 - $item->product->discount_percentage / 100), 0, '.', ',') }}₫
									</span>
								</p>
							@else
									<p class="text-success fw-bold" style="font-size: 1.4rem; color: #28a745;">
										{{ number_format($item->product->price, 0, '.', ',') }}₫
									</p>
								</div>
							@endif
					</div>
				</div>
			@endforeach
		</div>
		<div class="card-footer d-flex justify-content-between align-items-center">
			<span>Thành tiền: <strong>{{ number_format($order->total_price, 0, ',', '.') }}đ</strong></span>
			<div>
				<button class="btn btn-outline-success btn-sm rounded-full" id="feedbackBtn">Đánh giá</button>
				<button class="btn btn-outline-primary btn-sm rounded-full" id="repurchaseBtn">Mua lại</button>
				<button class="btn btn-outline-danger btn-sm roundd-full" id="refundReturnBtn">Trả / hoàn</button>
			</div>
		</div>
	</div>
	@if($orders->isEmpty())
		<p>Bạn chưa có đơn hàng nào.</p>
	@endif
@endforeach