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
            font-size: 16px;
            
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
        .product-image {
            max-width: 184px;
            max-height: 184px;
            border-radius: 15px;
        }
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
        .col-md-4 {
            position: relative;
            left: 2%;
            bottom: 30px;
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
        }
        .cart-item {
            border-top: 2px solid #EBEBEC;
            padding-top: 1rem;
            margin-top: 0.5rem;
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
                    <div class="d-flex mb-4 align-items-center cart-item">

                        <!--Cart Item Image-->
                        @if ($item->product && $item->product->productImages->where('image_type', 1)->first())
                            <img src= "{{$item->product->productImages->where('image_type', 1)->first()->product_image_url}}"
                                alt="Hình ảnh sản phẩm"
                                class="product-image">
                        @else
                            <img src="{{ asset('images/no-image.png') }}"
                                alt="Không có hình ảnh"
                                class="product-image">
                        @endif

                        <!--Cart Item Details-->
                        <div class="col-md-4 d-flex flex-column">
                            <h6 class="fw-semibold mb-2">{{ $item->product->name }}</h6>
                            <p class="mb-2">
                                <span class="font-normal">Đơn giá: </span>
                                <span class="price unit-price">{{ number_format($item->product->price) }}</span>
                                <span class="currency-label">VND</span>
                            </p>

                            <div class="d-flex justify-content-between">
                                <div class="quantity-wrapper mt-4">
                                    <button class="quantity-btn minus">-</button>
                                    <input type="number" class="quantity-input" value="{{ $item->quantity }}"
                                            min="1" max="99" onchange="calculateItemTotal(this)">
                                    <button class="quantity-btn plus">+</button>
                                </div>
                                
                                <div class="temp-total-price">
                                    <span class="font-normal mb-2">Tạm tính (Đã bao gồm thuế)</span>
                                    <span class="price total-price">{{ number_format($item->quantity * $item->product->price) }}</span>
                                </div>
                            </div>
                            
                        </div>

                    </div>
                @endforeach
            </div>

            <!-- Cart Summary Section -->
            <div class="col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Order Summary</h5>
                        <div class="mb-3">
                            <input type="text" class="form-control" id="couponCode" placeholder="Enter coupon code">
                            <button class="btn apply-btn w-100 mt-2">Apply Coupon</button>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span id="subtotal">0₫</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Shipping:</span>
                            <span>Free</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                            <strong>Total:</strong>
                            <strong id="total">0₫</strong>
                        </div>
                        <button class="btn btn-primary w-100">Proceed to Checkout</button>
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
