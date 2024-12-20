@foreach($orders as $order)
    <div class="card mb-4 order-card" style="border-radius: 12px;" data-order-id="{{ $order->order_id }}">
        <div class="card-header d-flex justify-content-between align-items-center"
			onmouseup="window.location.href='{{ route('orders.detail', ['order_id' => $order->order_id]) }}'"
		>
        @switch($order->status)
            @case('pending')
				<div>
            	    <span class="text-uppercase text" style="color: #949A90;">
						<i class="bi bi-box me-1" style="font-size: 1.1rem; color: #949A90;"></i>
						Đang xử lý
					</span>
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
            <span>Giao hàng: {{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i') }} - {{ \Carbon\Carbon::parse($order->deliver_time)->format('d/m/Y H:i') }}</span>    
        @endif
        </div>
        <div class="card-body p-0">
            <!-- Loop through order items -->
            @foreach($order->order_items as $item)
                <div class="p-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                    <div class="d-flex product-item" data-order-items-id="{{ $item->order_items_id }}">
                        <!-- Product Image -->
                        <img src="{{ $item->product->product_images[0]->product_image_url ?? asset('images/placeholder-plant.jpg') }}"
                            alt="Product Image" class="me-3 product-image-thumbnail" style="width: 80px; height: auto; border-radius: 3px;"
							onmouseup="window.location.href='{{ route('orders.detail', ['order_id' => $order->order_id]) }}'">
                        <!-- Product Details -->
                        <div>
                            <h6 class="mb-1 product-short-description" onmouseup="window.location.href='{{
                                route('product.show', ['product_id' => $item->product->product_id])
                            }}'">
                                {{ $item->product->short_description }}
                            </h6>
                            <p style="color: grey;">Mã sản phẩm: {{ $item->product->code }}</p>
                            <p class="purchaseQuantity">x {{ $item->quantity }}</p>
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
                    <strong style="font-letiant: normal;">{{ $order->voucher->voucher_name }}</strong>
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
                    <button class="btn btn-sm rounded refundReturnBtn" style="border: 1px solid black;">Trả hàng/ Hoàn tiền</button>
                @elseif ($order->status === "pending")
                    <button class="btn btn-sm btn-outline-warning" id="cancelBtn">Hủy đơn</button>
                @endif
            </div>
        </div>
    </div>
@endforeach

@if($orders->isEmpty())
    <div class="empty-state">
        <h5 class="mt-5 fw-light">Bạn chưa có đơn hàng nào</h5>
        <p class="text-muted">Hãy bắt đầu mua sắm để tạo đơn hàng đầu tiên của bạn</p>
        <a href="/" class="btn btn-primary mt-2">
            Khám phá sản phẩm
        </a>
    </div>
@endif

<!-- Feedback Modal -->
<div class="modal fade" id="feedbackModal" tabindex="-1" aria-labelledby="feedbackModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable"> <!-- Make dialog scrollable -->
        <div class="modal-content">
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

<!-- Refund/Return Modal -->
<div class="modal fade" data-order-id="" id="refundReturnModal" tabindex="-1" aria-labelledby="refundReturnModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content" style="overflow-y: auto;">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase fw-light">Đổi / Trả Hàng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('orders.submitRefundReturn') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <input type="hidden" name="order_id" class="refundReturnOrderId">

                    <!-- Select request type -->
                    <div class="mb-4">
                        <label class="form-label">Chọn loại yêu cầu</label>
                        <div class="request-type-group d-flex gap-3">
                            <label class="request-type-btn">
                                <input type="radio" name="request_type" value="return" required>
                                <span class="btn-content">
                                    <i class="bi bi-arrow-left-right me-2"></i>
                                    Đổi hàng
                                </span>
                            </label>
                            <label class="request-type-btn">
                                <input type="radio" name="request_type" value="refund" required>
                                <span class="btn-content">
                                    <i class="bi bi-arrow-return-left me-2"></i>
                                    Trả hàng
                                </span>
                            </label>
                        </div>
                    </div>

                    <!-- Select items to return/refund -->
                    <div class="mb-4">
                        <label class="form-label">Chọn sản phẩm</label>
                        <select class="form-select refundReturnItemsSelect" multiple required>
                            <!-- Options will be populated via JavaScript -->
                        </select>
                    </div>

                    <!-- Selected items list -->
                    <div class="refundReturnItemsList mb-4">
                        <!-- Selected items will be displayed here -->
                    </div>

                    <!-- Reason tag -->
                    <div class="mb-4">
                        <label class="form-label">Lý do đổi/trả</label>
                        <select class="form-select" name="reason_tag" required>
                            <option value="">-- Chọn lý do --</option>
                            <option value="wrong_item">Giao sai sản phẩm</option>
                            <option value="damaged">Sản phẩm bị hư hỏng</option>
                            <option value="not_as_described">Sản phẩm không như mô tả</option>
                            <option value="quality_issue">Vấn đề chất lượng</option>
                            <option value="change_mind">Đổi ý</option>
                            <option value="other">Lý do khác</option>
                        </select>
                    </div>

                    <!-- Reason description -->
                    <div class="mb-4">
                        <label class="form-label">Mô tả chi tiết về lý do yêu cầu đổi/trả &nbsp;<i class="bi bi-info-circle-fill text-muted"></i></label>
                        <textarea class="form-control" name="reason_description" rows="4" required minlength="0" maxLength="255"></textarea>
                    </div>

                    <!-- Upload images -->
                    <div class="mb-5">
                        <label class="form-label" id="refundReturnImgCounter">Tải ảnh lên (tối đa 5 ảnh/mỗi sản phẩm đã mua)</label>
                        <input type="file" name="images[]" accept="image/*" multiple class="form-control" onchange="previewRefundReturnImages(this)">
                    </div>

                    <!-- Image preview container -->
                    <div id="refundReturnPreviewContainer" class="preview-image-container mt-2"></div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn text-muted fw-light" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn fw-bold">Gửi yêu cầu</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$('.feedbackBtn').on('click', function() {
    let orderId = $(this).data('order-id');
    $('#modalOrderId').val(orderId);
    let feedbackItems = '';
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
    $('#feedbackModal').modal('show').appendTo('body');

    // Handle star rating persistence
    $('.star-rating input').on('change', function() {
        let selectedRating = $(this).val();
        $(this).siblings('label').each(function() {
            let starValue = $(this).prev('input').val();
            if (starValue <= selectedRating) {
                $(this).addClass('selected');
            } else {
                $(this).removeClass('selected');
            }
        });
    });

    $('.star-rating label').hover(
        function() {
            $(this).addClass('hover');
            $(this).prevAll().addClass('hover');
        },
        function() {
            $(this).removeClass('hover');
            $(this).prevAll().removeClass('hover');
        }
    );
});

function limitFiles(input, maxFiles) {
    if (input.files.length > maxFiles) {
        alert('Bạn chỉ có thể tải lên tối đa ' + maxFiles + ' ảnh.');
        input.value = '';
    }
}

// Function to preview images
function previewImages(input, productId) {
    let previewContainer = document.getElementById('preview-' + productId);
    previewContainer.innerHTML = '';
    if (input.files && input.files.length > 0) {
        if (input.files.length > 5) {
            alert('Bạn chỉ có thể tải lên tối đa 5 ảnh.');
            input.value = '';
            $('#imgCounter').text(`Tải ảnh lên (0/5)`);
            return;
        }
        Array.from(input.files).forEach(function(file) {
            let reader = new FileReader();
            reader.onload = function(e) {
                let img = document.createElement('img');
                img.src = e.target.result;
                img.classList.add('preview-image');
                previewContainer.appendChild(img);
            };
            reader.readAsDataURL(file);
        });
        $('#imgCounter').text(`Tải ảnh lên (${input.files.length}/5)`);
    }

    $('#feedbackModal').modal('show').appendTo('body');
}

function previewRefundReturnImages(inputElement) {
    const modal = $(inputElement).closest('.modal');
    const previewContainer = modal.find('#refundReturnPreviewContainer');
    const imgCounter = modal.find('#refundReturnImgCounter');

    previewContainer.html("");
    selectedItemsQuantity = 0;
    modal.find('.refund-item').each(function() {
        const quantityInput = $(this).find('.quantity-input');
        selectedItemsQuantity += parseInt(quantityInput.val());
    });

    if (inputElement.files && inputElement.files.length > 0) {
        if (inputElement.files.length > 5 * selectedItemsQuantity) {
            alert(`Bạn chỉ có thể tải lên tối đa ${5 * selectedItemsQuantity} ảnh.`);
            inputElement.value = '';
            imgCounter.text(`Tải ảnh lên (tối đa 5 ảnh/mỗi sản phẩm đã mua)`);
            return;
        }
        Array.from(inputElement.files).forEach(function (file) {
            let reader = new FileReader();
            reader.onload = function (e) {
                let img = document.createElement('img');
                img.src = e.target.result;
                img.classList.add('preview-image');
                previewContainer.append(img);
            };
            reader.readAsDataURL(file);
        });
        imgCounter.text(`Tải ảnh lên (${inputElement.files.length}/${5 * selectedItemsQuantity})`);
    }
}

$(document).ready(function() {
    // Optimize card animation timing
    $('.order-card').each(function(index) {
        $(this).css({
            'opacity': '0',
            'transform': 'translateY(10px)' // Reduced movement
        }).delay(index * 50) // Reduced delay between cards
          .animate({
            'opacity': '1',
            'transform': 'translateY(0)'
        }, 300); // Reduced animation duration
    });

    // Faster modal transitions
    $('.modal').on('show.bs.modal', function () {
        $(this).css('opacity', 0)
            .animate({ opacity: 1 }, 200); // Reduced to 200ms
    });

    $('.modal').on('hide.bs.modal', function () {
        $(this).animate({ opacity: 0 }, 200); // Reduced to 200ms
    });
});
</script>
<script src="{{ asset('js/profile/ordersTab.js') }}"></script>
<link rel="stylesheet" href="{{ asset('css/profile/ordersTab.css') }}">

<!-- Add this for smooth scrolling -->
<style>
    html {
        scroll-behavior: smooth;
    }
</style>