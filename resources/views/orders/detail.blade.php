@extends('layouts.layout')
@section('title', 'Order Details')

@section('style')
<link rel="stylesheet" href="{{ asset('css/orders/detail.css') }}">
@endsection

@section('content')
<div class="container my-4">
    <div class="row">
        <!-- Back button and Order ID -->
        <div class="col-12 mb-4">
            <a href="{{ url()->previous() }}" class="back-link">
                <i class="bi bi-arrow-left"></i> Quay trở lại
            </a>
            <h4 class="mt-3">Đơn hàng #{{ $order->order_id }}</h4>
        </div>

        <!-- Order Details Card -->
        <div class="col-md-8">
            <div class="card order-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
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
										Chờ thanh toán
									@else
										Đang xử lý
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
                    </div>
                    <div class="text-muted fw-light">
                        {{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i') }}
                        @if($order->status === 'delivered')
                            <br>
                            <small class="fw-light">Giao hàng: {{ \Carbon\Carbon::parse($order->deliver_time)->format('d/m/Y H:i') }}</small>
                        @endif
                    </div>
                </div>

                <div class="card-body">
                    <!-- Products List -->
                    @foreach($order->order_items as $item)
                    <div class="product-item d-flex align-items-center p-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <img src="{{ $item->product->product_images[0]->product_image_url ?? asset('images/placeholder.jpg') }}" 
                             class="product-image me-3" alt="Product">
                        
                        <div class="flex-grow-1">
                            <h6 class="mb-1">{{ $item->product->short_description }}</h6>
                            <p class="text-muted mb-1">Code: {{ $item->product->code }}</p>
                            <p class="mb-0">Số lượng: {{ $item->quantity }}</p>
                        </div>

                        <div class="text-end">
                            @if($item->product->discount_percentage > 0)
                                <p class="mb-0">
                                    <s class="text-muted">{{ number_format($item->product->price, 0, ',', '.') }}₫</s>
                                    <br>
                                    <span class="text-success">
                                        {{ number_format($item->product->price * (1 - $item->product->discount_percentage / 100), 0, ',', '.') }}₫
                                    </span>
                                </p>
                            @else
                                <p class="text-success mb-0">
                                    {{ number_format($item->product->price, 0, ',', '.') }}₫
                                </p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Delivery Address Card -->
            <div class="card address-card mt-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-geo-alt me-2"></i>Địa chỉ nhận hàng</h6>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $order->deliver_address }}</p>
                </div>
            </div>
        </div>

        <!-- Order Summary Card -->
        <div class="col-md-4">
            <div class="card summary-card">
                <div class="card-body">
                    <h5 class="card-title">Hóa đơn thanh toán</h5>
                    <div class="summary-item d-flex justify-content-between mb-2">
                        <span>Tạm tính</span>
                        <span>{{ number_format($order->provisional_price, 0, ',', '.') }}₫</span>
                    </div>
                    <div class="summary-item d-flex justify-content-between mb-2">
                        <span class="fst-italic">Phí ship&nbsp;<i class="fa-solid fa-truck-fast fst-italic" style="font-size: 0.95rem;"></i></span>
                        <span>{{ number_format($order->deliver_cost, 0, ',', '.') }}₫</span>
                    </div>
                    @if($order->voucher)
                    <div class="summary-item d-flex justify-content-between mb-2">
                        <span>{{ $order->voucher->voucher_type == 'cash' ? 'Voucher' : 'Coupon' }}</span>
                        <span class="text-success">-{{ number_format($discount_amount, 0, ',', '.') }}₫</span>
                    </div>
                    @endif
                    <hr>
                    <div class="summary-total d-flex justify-content-between">
                        <span class="fw-bold">Tổng</span>
                        <span class="fw-bold text-success">{{ number_format($order->total_price, 0, ',', '.') }}₫</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection