@foreach($orders as $order)
	<div class="card mb-4" style="border-radius: 8px;" data-order-id="{{ $order->order_id }}">
		<div class="card-header d-flex justify-content-between align-items-center">
		@switch($order->status)
			@case('pending')
			<div>
				<i class="bi bi-box me-1" style="font-size: 1.1rem; color: #949A90;"></i>
				<span class="text-uppercase text" style="color: #949A90;">Chờ lấy hàng</span>
			</div>
				@break
			@case('delivering')
			<div>
				<i class="bi bi-truck me-1" style="font-size: 1.1rem; color: #435E53;"></i>
				<span class="text-uppercase" style="color: #435E53;">Đang giao</span>
			</div>
				@break
			@case('delivered')
				<div class="d-flex d-row">
					<i class="bi bi-truck text-success me-1" style="font-size: 1.1rem;"></i>
					<span class="text-uppercase text-success">Đã giao</span>		
				</div>
				@break
			@case('cancelled')
				<div class="d-flex d-row">
					<span class="text-uppercase text-danger">Đã hủy</span>		
				</div>
				@break
			@case('returned')
				<div class="d-flex d-row">
					<i class="fa-solid fa-arrow-right-arrow-left me-3 mt-1"></i>
					<span class="text-uppercase">Đổi hàng</span>		
				</div>
				@break
			@case('refunded')
				<div class="d-flex d-row">
					<i class="fa-solid fa-arrow-left me-3 mt-1"></i>
					<span class="text-uppercase">Trả hàng / hoàn tiền</span>		
				</div>
				@break
			@default
				<span class="text-uppercase">{{ $order->status }}</span>
				@break
		@endswitch
		@if ($order->status == 'pending' || $order->status == 'delivering')
			<span>Đặt lúc: {{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i') }}</span>
		@elseif ($order->status == 'completed' || $order->status == 'delivered')
			<span>Giao hàng: {{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i') }} - {{ \Carbon\Carbon::parse($order->delivery_time)->format('d/m/Y H:i') }}</span>	
		@endif
		</div>
		<div class="card-body p-0">
			<!-- Loop through order items -->
			@foreach($order->order_items as $item)
				<div class="p-3 {{ !$loop->last ? 'border-bottom' : '' }}">
					<div class="d-flex">
						<!-- Product Image -->
						<img src="{{ $item->product->product_images[0]->product_image_url ?? asset('images/placeholder-plant.jpg') }}"
							alt="Product Image" class="me-3" style="width: 80px; height: auto; border-radius: 3px;">
						<!-- Product Details -->
						<div>
							<h6 class="mb-1" onmouseup="window.location.href='{{
								route('product.show', ['product_id' => $item->product->product_id])
							}}'">
								{{ $item->product->short_description }}
							</h6>
							<p style="color: grey;">Mã sản phẩm: {{ $item->product->code }}</p>
							<p>x {{ $item->quantity }}</p>
						</div>
						<!-- Pricing -->
						<div class="ms-auto text-end d-flex align-items-center">
							@if ($item->product->discount_percentage > 0)
								<p class="mb-0">
									<span class="text-muted text-decoration-line-through" style="font-size: 1.1rem; color:#949A90;">
										{{ number_format($item->product->price, 0, '.', ',') }}₫
									</span>
									<br>
									<span class="fw-bold" style="font-size: 1.2rem; color: #435E53;">
										{{ number_format($item->product->price * (1 - $item->product->discount_percentage / 100), 0, '.', ',') }}₫
									</span>
								</p>
							@else
								<p class="fw-bold mb-0" style="font-size: 1.2rem; color: #435E53;">
									{{ number_format($item->product->price, 0, '.', ',') }}₫
								</p>
							@endif
						</div>
					</div>
				</div>
			@endforeach
		</div>
		<div class="card-footer d-flex justify-content-between align-items-center">
			@if ($order->voucher_id != null) 
			<div class="d-flex flex-column">
				<span class="me-2">
					@if ($order->voucher->voucher_type == 'cash')
						Voucher:
					@else
						Coupon:
					@endif
					<strong style="font-variant: normal;">{{ $order->voucher->voucher_name }}</strong>
				</span>
				<span>Thành tiền: <strong>{{ number_format($order->total_price, 0, ',', '.') }}đ</strong></span>
			</div>
			@else
				<span>Thành tiền: <strong>{{ number_format($order->total_price, 0, ',', '.') }}đ</strong></span>
			@endif
			<div>
				@if ($order->status === "delivered")
					<button class="btn btn-sm rounded text-white feedbackBtn" data-order-id="{{ $order->order_id }}" style="background-color: #1E4733;">Đánh giá</button>
					<button class="btn btn-sm rounded" id="repurchaseBtn" style="border: 1px solid black;">Mua lại</button>
					<button class="btn btn-sm rounded" id="refundReturnBtn" style="border: 1px solid black;">Trả hàng/ Hoàn tiền</button>
				@elseif ($order->status === "pending")
					<button class="btn btn-sm btn-outline-warning" id="cancelBtn" style="">Hủy đơn</button>
				@endif
			</div>
		</div>
	</div>
@endforeach
@if($orders->isEmpty())
	<div class="d-flex flex-column align-items-center mt-5">
		<i class="bi bi-bag fs-5" style="color: #212529 !important;"></i>
		<p class="fs-5 mt-4" style="color: #212529 !important;">Bạn chưa có đơn hàng nào.</p>
	</div>
@endif

<!-- Feedback Modal -->
<div class="modal fade" id="feedbackModal" tabindex="-1" aria-labelledby="feedbackModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable"> <!-- Make dialog scrollable -->
        <div class="modal-content" style="max-height: 80vh;"> <!-- Set fixed max height -->
            <div class="modal-header">
                <h5 class="modal-title text-uppercase fw-light">Đánh giá sản phẩm</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('orders.submitFeedback') }}" method="POST" enctype="multipart/form-data"> <!-- Add enctype -->
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="order_id" id="modalOrderId" value="">
                    <!-- Feedback items will be loaded here -->
                    <div id="feedbackItems"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn text-muted fw-light" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn fw-bold">Gửi đánh giá</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
$('.feedbackBtn').on('click', function() {
    var orderId = $(this).data('order-id');
    $('#modalOrderId').val(orderId);
    var feedbackItems = '';
    @foreach($orders as $order)
        if ({{ $order->order_id }} == orderId) {
            @foreach($order->order_items as $item)
                feedbackItems += `
                <div class="mb-4">
					<div class="d-flex flex-row">
						<img src="{{ $item->product->product_images[0]->product_image_url ?? asset('images/placeholder-plant.jpg') }}"
							alt="Product Image" class="me-3" style="width: 80px; height: auto; border-radius: 3px;">
						<div class="d-flex flex-column">
							<h6>{{ $item->product->short_description }}</h6>
							<p style="color: grey;">Mã sản phẩm: {{ $item->product->code }}</p>
							<p>x {{ $item->quantity }}</p>
						</div>
					</div>
                    <input type="hidden" name="feedbacks[{{ $item->product_id }}][product_id]" value="{{ $item->product_id }}">
                    <label class="form-label">Nội dung phản hồi</label>
					<textarea class="form-control" name="feedbacks[{{ $item->product_id }}][feedback_content]" rows="3" maxlength="255" minlength="10"></textarea>
                    <label class="form-label mt-2">Đánh giá</label>
                    <div class="star-rating mt-2 mb-3" style="right: 20px">
                        @for($i = 5; $i >= 1; $i--)
                            <input type="radio" id="star{{ $item->product_id }}_{{ $i }}" name="feedbacks[{{ $item->product_id }}][num_star]" value="{{ $i }}" required>
                            <label for="star{{ $item->product_id }}_{{ $i }}"><i class="fas fa-star"></i></label>
                        @endfor
                    </div>
                    <label class="form-label mt-2" id='imgCounter'>Tải ảnh lên (tối đa 5)</label>
                    <input type="file" name="feedbacks[{{ $item->product_id }}][images][]" accept="image/*" multiple
                        class="form-control" onchange="previewImages(this, '{{ $item->product_id }}')">
                    <!-- Image preview container -->
                    <div id="preview-{{ $item->product_id }}" class="preview-image-container mt-2"></div>
                </div>
                <hr>`;
            @endforeach
        }
    @endforeach
    $('#feedbackItems').html(feedbackItems);
    $('#feedbackModal').modal('show');
});

function limitFiles(input, maxFiles) {
    if (input.files.length > maxFiles) {
        alert('Bạn chỉ có thể tải lên tối đa ' + maxFiles + ' ảnh.');
        input.value = '';
    }
}

// Function to preview images
function previewImages(input, productId) {
    var previewContainer = document.getElementById('preview-' + productId);
    previewContainer.innerHTML = '';
    if (input.files && input.files.length > 0) {
        if (input.files.length > 5) {
            alert('Bạn chỉ có thể tải lên tối đa 5 ảnh.');
			input.value = '';
			$('#imgCounter').text(`Tải ảnh lên (0/5)`);
            return;
        }
        Array.from(input.files).forEach(function(file) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var img = document.createElement('img');
                img.src = e.target.result;
                img.classList.add('preview-image');
                previewContainer.appendChild(img);
            };
            reader.readAsDataURL(file);
        });
		$('#imgCounter').text(`Tải ảnh lên (${input.files.length}/5)`);
    }
}
</script>

<link rel="stylesheet" href="{{ asset('css/profile/ordersTab.css') }}">