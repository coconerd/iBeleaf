@extends('layouts.layout')
@section('title', 'cart-page')

@section("body-script")
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/cart/cartManager.js') }}"></script>
    <script src="{{ asset('js/cart/voucherManager.js') }}"></script>
@endsection

@section('style')
	<link rel="stylesheet" href="{{ asset('css/cart/items.css') }}">
	<link rel="stylesheet" href="{{ asset('css/cart/christmas.css') }}">
@endsection

@section('content')
    <div class="snowflakes" aria-hidden="true">
        @for ($i = 1; $i <= 12; $i++)
            <div class="snowflake" style="left: {{ rand(0, 100) }}%; animation-delay: {{ $i * 0.2 }}s">
                <div class="inner">❅</div>
            </div>
        @endfor
    </div>

    <div class="container mt-4">
    @if(isset($cartItems) && $cartItems->count() > 0)
        <div class="row">
            <!--Cart Items Section-->
            <div class="col-lg-8 id="cart-header">
                <h4 class="fw-bold mb-3">🎄 Giỏ hàng</h4>
                <div class="d-flex justify-content-between">
                    <p class="fw-semibold">Mặt hàng
                        (<span class="items-count">{{ $totalQuantity }}</span>)
                    </p>
                    <p class="fw-semibold">Tạm tính</p>
                </div>
                
                <div class="all-cart-items">
                    @foreach($cartItems as $item)
                        <div class="d-flex mb-4 align-items-center each-cart-item {{ $item->product->stock_quantity <= 0 ? 'out-of-stock' : '' }}"
                            data-cart-id="{{ $item->cart_id }}"
                            data-product-id="{{ $item->product_id }}">
                            <!--Cart Item Image And Discount Label-->
                            <div class="item-cart">
                                @if ($item->product && $item->product->productImages->where('image_type', 1)->first())
                                    <div class="image-container">
                                        <img src= "{{ $item->product->productImages->where('image_type', 1)->first()->product_image_url }}"
                                            alt="Hình ảnh sản phẩm"
                                            class="product-image">
                                        @if($item->product->discount_percentage > 0)
                                            <div class="discount-label">Giảm {{ $item->product->discount_percentage }}%</div>
                                        @endif
                                    </div>
                                @else
                                    <img src="{{ asset('images/no-image.png') }}"
                                        alt="Không có hình ảnh"
                                        class="product-image">
                                @endif
                            </div>
                            
                            <!--Cart Item Details-->
                            <div class="container">
                                <div class="row item-detail">
                                    <h6 class="fw-semibold col-md-bg-danger">{{ $item->product->name }}</h6>
                                    <p class="col-md-bg-success unit-price">
                                        <span class="font-normal fs-6">Đơn giá (giá gốc): </span>
                                        <span class="price fs-7"
                                            data-price="{{ $item->product->price }}"
                                            data-discount="{{ $item->product->discount_percentage }}">
                                            {{ $item->product->price }}
                                        </span>
                                        <span class="currency-label fs-6"><b>₫</b></span>
                                    </p>
                                </div>

                                <div class="cart-control container-fluid px-0">
                                <div class="row">
                                        <div class="quantity-wrapper col-12 col-sm-12 col-md-4 mb-3">
                                            <!--Disable the quantity input and buttons when out of stock-->
                                            <button class="quantity-btn minus">-</button>
                                            <input type="number" class="quantity-input"
                                                    value="{{ $item->quantity }}"
                                                    min="1" max="{{ $item->product->stock_quantity }}"
                                                    data-max-stock="{{ $item->product->stock_quantity }}"
                                                    oninput="validateQuantity(this)"
                                                    data-discount-percentage="{{ $item->product->discount_percentage }}">
                                            <button class="quantity-btn plus">+</button>

                                            <i class="fa-solid fa-trash-can ms-2 remove-item-btn fs-5"
                                                style="color: #c78b5e; cursor: pointer;"
                                                data-cart-id="{{ $item->cart_id }}"
                                                data-product-name="{{ $item->product->name }}"></i>
                                        </div>
                                        
                                        <div class="temp-final-total-price-price col-12 col-sm-12 col-md-8">
                                            <!-- Use text-start on mobile, text-end on larger screens -->
                                            <div class="row justify-content-start justify-content-md-end">
                                                @if($item->product->stock_quantity > 0)
                                                    <span class="temp-title text-start text-md-end fs-6">Tạm tính (Đã bao gồm khuyến mãi)</span>
                                                    <div class="d-flex align-items-center justify-content-md-end justify-content-start">
                                                        <span class="price fs-7 total-uprice me-1"
                                                            data-cart-id="{{ $item->cart_id }}"
                                                            data-unit-price="{{ $item->unit_price }}"
                                                            data-discount-percent="{{ $item->product->discount_percentage ?? 0}}">
                                                            {{ $item->discounted_price }}
                                                        </span>
                                                        <span class="currency-label fs-6"><b>₫</b></span>
                                                    </div>
                                                @else
                                                    <div class="d-flex align-items-center justify-content-md-end justify-content-start">
                                                        <span class="text-danger">Sản phẩm hiện đã hết hàng</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>

                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Cart Summary Section -->
            <div class="col-12 col-lg-4 mb-5">
                <div class="card">
                    <div class="card-body p-3 p-md-4">
                        <h5 class="card-title mb-4 text-center text-lg-start">
                            Thông tin đơn hàng (<span class="items-count">{{ $totalQuantity }}</span>)
                        </h5>
                        <div id="container">
                            <div class="row align-items-center mb-2">
                                <div class="col-7 col-lg-6">
                                    <p class="info left-side fs-7 mb-0">Thành tiền (<span class="items-count-mh">{{ $totalQuantity }} mặt hàng</span>)</p>
                                </div>
                                <div class="col-5 col-lg-6">
                                    <p class="info right-side text-end mb-0">
                                        <span class="price fs-6" id="first-total-price">{{ $totalDiscountedPrice }}</span>
                                        <span class="currency-label fs-6"><b>₫</b></span>
                                    </p>
                                </div>
                            </div>

                            <div class="row align-items-center mb-2">
                                <div class="col-7 col-lg-6">
                                    <p class="info left-side fs-7 mb-0">Tổng khuyến mãi</p>
                                </div>
                                <div class="col-5 col-lg-6">
                                    <p class="info right-side text-end mb-0">
                                        <span class="price fs-6" id="total-discount-amount">{{ $totalDiscountAmount }}</span>
                                        <span class="currency-label fs-6"><b>₫</b></span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="voucher-section mt-3">
                            <div class="input-group mb-2">
                                <input type="text" class="form-control" id="voucher-input" placeholder="Nhập mã voucher">
                                <button class="btn apply-btn" id="voucher-apply">ÁP DỤNG</button>
                            </div>
                            <div id="voucher-amount"></div>
                            <div id="voucher-error" class="text-danger mb-2" style="display: none;"></div>
                            <div class="voucher-box" style="display: none;" id="valid-voucher-box" data-voucher-type="">
                                <div class="voucher-details" data-voucher-id="">
                                    <div class="voucher-icon">
                                        <i class="fa-regular fa-circle-check" style="color: #c78b5e;"></i>
                                    </div>
                                    <div>
                                        <p class="voucher-info"><span id="voucher-description"></span></p>
                                        <span id="voucher-discount"></span>
                                        <input type="hidden" id="voucher-id" name="voucher_id" value="">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr id="cart-summary-line">
                        <div class="row align-items-center mb-2">
                            <div class="col-7 col-lg-6">
                                <p class="info left-side fs-5 mb-0" id="total-price-text">Tổng tiền:</p>
                            </div>
                            <div class="col-5 col-lg-6">
                                <p class="info right-side text-end mb-0">
                                    <span class="price fs-5" id="final-price">{{ $totalDiscountedPrice }}</span>
                                    <span class="currency-label fs-6"><b>₫</b></span>
                                </p>
                            </div>
                        </div>

                        <div id="ship">
                            <i>(Chưa bao gồm phí vận chuyển)</i>
                        </div>
                        <button class="btn btn-primary w-100" id="checkout-btn">Thanh toán</button>
                        <div class="text-center mt-3">
                            <a href="/" class="continue-shopping d-block">
                                <i class="fas fa-shopping-cart me-2"></i> Tiếp tục mua hàng
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!--Voucher Announcement Section-->
            <!-- <div class="testimonial-container mt-4">
                <div class="testimonial-card">
                    <div class="testimonial-content">
                        <p class="testimonial-text">
                            Mình học được rất nhiều điều bổ ích từ khóa học này, đặc biệt là phần về ngôn ngữ thị giác. Anh Phương cũng rất nhiệt tình và luôn sẵn sàng hỗ trợ, giải đáp thắc mắc của mình.
                        </p>
                    </div>
                    <div class="testimonial-author">
                        <img src="{{ asset('images/testimonials/avatar.jpg') }}" alt="Avatar" class="testimonial-avatar">
                        <h4 class="author-name">Trần Đăng Khoa</h4>
                    </div>
                </div>
            </div> -->
        </div>
    @else
        <div class="text-center py-5">
            <i class="bi bi-cart-x display-1 text-muted"></i>
            <h5 class="mt-4" style="color: #1E362D;">Không có sản phẩm trong giỏ hàng</h5>
        </div>
    @endif
</div>
@endsection
