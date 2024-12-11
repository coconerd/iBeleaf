@extends('layouts.layout')
@section('title', 'cart-page')

@section('head-script')
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
@endsection

@section("body-script")
	<script src="/js/cartCalculation.js"></script>
@endsection

@section('style')
    <style>
        /*quantity minus and plus button*/
        .quantity-input {
            width: 42px;
            height: 36px;
            text-align: center;
            border: 1px solid #EBEBEC;
            border-radius: 6px;
            margin: 0 0.5rem;
            /* Add these properties */
            line-height: 35px;
            padding: 0;
            vertical-align: middle;
            outline: none;
            /* Add text styling */
            color: #1E4733;
            font-weight: 500;
            font-size: 15px;
        }
        .quantity-input::-webkit-outer-spin-button,
        .quantity-input::-webkit-inner-spin-button {
            -webkit-appearance: none;
        }
        .quantity-btn:hover {
            background: #f0e9d8;
            cursor: pointer;
        }
        .quantity-btn:focus {
            outline: none;
            box-shadow: none;
        }
        .quantity-btn:active {
            background: #e8e0cc;
            transform: scale(0.98);
        }
        .quantity-btn {
            background: #F8F3E7;
            border: none;
            padding: 6px 16px;
            border-radius: 6px;
            font-size: 15px;
            
        }
        .quantity-btn.minus,
        .quantity-btn.plus {
            color: #106B32;
            font-weight: 600;
        }
        .quantity-wrapper {
            display: flex;
            align-items: center;
            margin-top: 0.5rem;
        }

        /*product image and discount label*/
        .product-image {
            max-width: 184px;
            max-height: 184px;
            border-radius: 15px;
            z-index: 0;
        }
        .image-container {
            position: relative;
            width: 100%;
            z-index: 1;
        }
        .discount-label {
            background-color: #CD853F;
            color: white;
            padding: 3px 9px; /* Padding around the text */
            border-radius: 17px 14px 28px 5px;
            
            position: absolute;
            font-size: 12.25px;
            display: inline-block; /* Inline block for better sizing */
            font-weight: 500;
            top: -5px;
            left: -3px;
            z-index: 1;
        }
        .discount-label::after {
            content: '';
            position: absolute;
            top: 6px;
            left: -2.45px; /* Adjust to position the curve */
            width: 6px;
            height: 100%;
            background-color: #CD853F;
            border-top-left-radius: 24px; /* Curve */
            border-bottom-left-radius: 30px; /* Curve */
            z-index: -1;
        }
        /**/
        .wishlist-btn {
            border: 1px solid #ddd;
            background: white;
            padding: 5px 10px;
            border-radius: 50%;
        }
        .apply-btn {
            background: #4a4a4a;
            color: white;
        }
        .item-detail{
            margin-bottom: 2%;
        }
        .unit-price{
            margin-top: 5px;
        }
        .price {
            font-size: 15px;
            font-weight: 600;
            color: #1E4733;
        }
        .total-price{
            font-size: 18px;
        }
        .currency-label {
            font-size: 12px;
            font-weight: normal;
            margin-top: 3px;
        }
        .each-cart-item {
            border-top: 2px solid #EBEBEC;
            padding-top: 1rem;
            margin-top: 0.5rem;
        }
        @media (min-width: 768px) {
            .temp-total-price {
                position: relative;
                right: -15px;
            }
        }
        .cart-control {
            margin-bottom: 0.5rem;
        }
        /*Cart summary section*/
        .card-title{
            margin-left: -3px;
        }
        .card{
            background-color: rgba(247, 244, 240, 0.6);
            border: none;
            box-shadow: none;
            position: relative;
            left: 20px;
        }
        .left-side{
            text-align: left;
            color: #7E7E7E;
            font-weight: 400;
        }
        .right-side{
            text-align: right;
        }
        #total-discounted-price{
            font-weight: 600;
            color: #1E4733;
        }
        .form-control {
            border: 0.5px solid #ced4da;
            border-right: none;
            border-radius: 4px 0 0 4px;
        }
        .apply-btn {
            height: 100%;
            background-color: #0f5a29;
            height: 100%;
            border: 0.5px solid #1E4733;
            border-left: none;
            border-radius: 0 5px 5px 0;
        }
        #apply-coupon{
            font-size: 16px;
        }
        #couponCode::placeholder {
            color: #A9A9A9;
            opacity: 0.7;

        }
        #couponCode {
            color: #000; /* Normal text color for input value */
        }
        #couponCode:focus {
            outline: none;
            box-shadow: none;
            border-color: #ced4da;
        }
        #cart-summary-line{
            border-top: 2px solid #949A90;
            margin-top: 2rem;
        }
        .btn-primary{
            background-color: #0f5a29;
            border-radius: 8px;
            border: none;
            font-size: 18px;
            padding: 10px 20px;
        }
        .btn.btn-primary:hover{
            background-color: #1E4733;
            transition: background-color 0.3s ease;
        }
        .d-flex.justify-content-between {
            margin-bottom: 0.5rem !important; /* Reduced from mb-4 */
        }
        #vat {
            font-size: 14px;
            text-align: right;
            opacity: 0.35;
            margin-bottom: 1rem;
            line-height: 1;
        }
    </style>
@endsection

@section('content')
    <div class="container mt-4">
    @if(isset($cartItems) && $cartItems->count() > 0)
        <div class="row">
            <!--Cart Items Section-->
            <div class="col-lg-8">
                <h5 class="fw-bold mb-3">Giỏ hàng</h5>
                <div class="d-flex justify-content-between">
                    <p class="fw-semibold">Mặt hàng ({{$cartItems->count()}})</p>
                    <p class="fw-semibold">Tạm tính</p>
                </div>
                
                @foreach($cartItems as $item)
                    <div class="d-flex mb-4 align-items-center each-cart-item">

                        <!--Cart Item Image And Discount Label-->
                        <div class="item-card">
                            @if ($item->product && $item->product->productImages->where('image_type', 1)->first())
                                <div class="image-container">
                                    <img src= "{{$item->product->productImages->where('image_type', 1)->first()->product_image_url}}"
                                        alt="Hình ảnh sản phẩm"
                                        class="product-image">
                                    @if($item->product->discount_percentage > 0)
                                        <div class="discount-label">Giảm {{$item->product->discount_percentage}}%</div>
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
                                    <span class="font-normal">Đơn giá: </span>
                                    <span class="price">{{ number_format($item->product->price) }}</span>
                                    <span class="currency-label">VND</span>
                                </p>
                            </div>

                            <div class="cart-control container-fluid px-0">
                               <div class="row">
                                    <div class="quantity-wrapper col-12 col-sm-12 col-md-4 mb-3">
                                        <button class="quantity-btn minus">-</button>
                                        <input type="number" class="quantity-input" value="{{ $item->quantity }}"
                                                min="1" max="99" onchange="calculateItemTotal(this)">
                                        <button class="quantity-btn plus">+</button>
                                    </div>
                                    
                                    <div class="temp-total-price col-12 col-sm-12 col-md-8">
                                        <!-- Use text-start on mobile, text-end on larger screens -->
                                        <div class="row justify-content-start justify-content-md-end">
                                            <span class="temp-title text-start text-md-end">Tạm tính (Đã bao gồm khuyến mãi)</span>
                                            <div class="d-flex align-items-center justify-content-md-end justify-content-start">
                                                <span class="price total-price me-1">{{ number_format($item->discounted_price) }}</span>
                                                <span class="currency-label">VND</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>

                    </div>
                @endforeach
            </div>

            <!-- Cart Summary Section -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Thông tin đơn hàng ({{$cartItems->count()}})</h5>
                        <div id ="container">
                            <div class="row">
                                <p class="info col-6 left-side">Thành tiền ({{$cartItems->count()}} mặt hàng)</p>
                                <p class="info col-6 right-side" id="total-discounted-price">{{ number_format($totalDiscountedPrice) }} VND</p>
                            </div>
                            <div class="row">
                                <p class="info col-6 left-side">Phí vận chuyển</p>
                                <p class="info col-6 right-side">Miễn phí giao hàng</p>
                            </div>
                            <div class="row">
                                <p class="info col-6 left-side">Tổng khuyến mãi</p>
                                <p class="info col-6 right-side">{{ number_format($totalDiscountAmount) }} VND</p>
                            </div>
                        </div>

                        <div class="input-group">
                            <input type="text" class="form-control" id="couponCode" placeholder="Nhập mã giảm giá">
                            <button class="btn apply-btn" id="apply-coupon" style="width: 25%;">ÁP DỤNG</button>
                        </div>

                        <hr id="cart-summary-line">
                        <div class="d-flex justify-content-between mb-4">
                            <strong>Tổng tiền:</strong>
                            <strong id="total">{{number_format($totalDiscountedPrice)}} VND</strong>
                        </div>
                        <div id="vat">
                            <i>(Đã bao gồm VAT)</i>
                        </div>
                        <button class="btn btn-primary w-100">Thanh toán</button>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-5">
            <i class="bi bi-cart-x display-1 text-muted"></i>
            <h5 class="mt-4" style="color: #1E362D;">Không có sản phẩm trong giỏ hàng</h5>
        </div>
    @endif
</div>
@endsection
