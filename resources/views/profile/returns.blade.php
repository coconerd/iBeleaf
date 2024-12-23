<div class="returns-container">
    <!-- Type Filter -->
    <div class="filter-row mb-3">
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-outline-secondary active" data-type="all">Tất cả</button>
            <button type="button" class="btn btn-outline-secondary" data-type="refund">Trả hàng</button>
            <button type="button" class="btn btn-outline-secondary" data-type="return">Đổi hàng</button>
        </div>
    </div>

    <!-- Status Filter -->
    <div class="filter-row mb-4">
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-outline-secondary active" data-status="all">Tất cả</button>
            <button type="button" class="btn btn-outline-secondary" data-status="pending">Đang xử lý</button>
            <button type="button" class="btn btn-outline-secondary" data-status="accepted">Đã chấp nhận</button>
            <button type="button" class="btn btn-outline-secondary" data-status="rejected">Đã từ chối</button>
            <button type="button" class="btn btn-outline-secondary" data-status="received">Đã nhận hàng</button>
        </div>
    </div>

    <!-- Returns List -->
    <div class="returns-list">
        @forelse($returnRefundItems as $item)
            <div class="card mb-3 return-item" 
                data-type="{{ $item->type }}"
                data-status="{{ $item->status }}">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <span class="badge {{ $item->type === 'return' ? 'bg-info' : 'bg-warning' }}">
                            {{ $item->type === 'return' ? 'Đổi hàng' : 'Trả hàng' }}
                        </span>
                        <span class="badge 
                            {{ $item->status === 'pending' ? 'bg-secondary' : 
                               ($item->status === 'accepted' ? 'bg-success' : 
                               ($item->status === 'rejected' ? 'bg-danger' : 'bg-primary')) }}">
                            {{ $item->status === 'pending' ? 'Đang xử lý' : 
                               ($item->status === 'accepted' ? 'Đã chấp nhận' : 
                               ($item->status === 'rejected' ? 'Đã từ chối' : 'Đã nhận hàng')) }}
                        </span>
                    </div>
                    <small class="text-muted">{{ $item->created_at->format('d/m/Y H:i') }}</small>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h6 class="card-subtitle mb-2">Mã đơn hàng: {{ $item->order_item->order_id }}</h6>
                            <div class="product-info d-flex align-items-center mb-3">
                                <img src="{{ $item->order_item->product->product_images->first()->product_image_url ?? asset('images/placeholder-plant.jpg') }}" 
                                    class="product-image-thumbnail me-3" alt="Product image">
                                <div>
                                    <h6 class="mb-1">{{ $item->order_item->product->name }}</h6>
                                    <p class="mb-0">Số lượng: {{ $item->quantity }}</p>
                                </div>
                            </div>
                            <div class="reason mt-2">
                                <p class="mb-1"><strong>Lý do:</strong>
								@switch ($item->reason_tag)
									@case('wrong_product')
										<span> Sai sản phẩm</span>
										@break
									@case('damaged')
										<span>Sản phẩm bị hư/hỏng</span>
										@break	
									@case('not_as_described')
										<span>Sản phẩm không đúng với mô tả</span>
										@break	
									@case('quality_issue')
										<span>Không hài lòng về chất lượng</span>
										@break
									@case('change_mind')
										<span>Muốn đổi sang mặt hàng khác</span>
										@break	
									@default('other')
										<span>Lý do khác</span>
										@break
								@endswitch	
								</p>
                                <p class="mb-0"><small>{{ $item->reason_description }}</small></p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            @if($item->refund_return_images->count() > 0)
                                <div class="images-preview">
                                    <p class="mb-2"><small>Hình ảnh đính kèm:</small></p>
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach($item->refund_return_images as $image)
                                            <img src="data:image/jpeg;base64,{{ base64_encode($image->refund_return_image) ?? asset('images/placeholder-plant.jpg') }}" 
                                                class="img-thumbnail" alt="Return image"
                                                style="width: 60px; height: 60px; object-fit: cover;"
                                                onclick="showFullImage(this.src)">
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-5">
                <h5>Bạn chưa có yêu cầu đổi/trả hàng nào</h5>
            </div>
        @endforelse
    </div>
</div>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body p-0">
                <img src="" id="fullImage" class="img-fluid w-100">
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/profile/returns.js') }}"></script>
<link rel="stylesheet" href="{{ asset('css/profile/returns.css') }}">