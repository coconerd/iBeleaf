@extends('layouts.layout')
@section('title', 'shipping-page')

@section("body-script")
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/cart/checkOutManager.js') }}"></script>
@endsection

@section('style')
	<style>
		.checkout-container {
			max-width: 950px; /* Adjust this width to make it smaller */
			margin: 50px auto; /* Center align horizontally and add top/bottom spacing */
			padding: 20px;
			background-color: #fff; /* Optional: Add background for clarity */
			border: none; /* Optional: Add a border */
			border-radius: 8px; /* Optional: Rounded corners */
		}
		/* Payment method styling */
		.checkout-container .payment-method {
			border: 1px solid #ddd;
			border-radius: 8px;
			padding: 10px;
			margin-bottom: 10px;
			cursor: pointer;
		}
        .section-title {
            font-weight: bold;
            font-size: 1.2rem;
            margin-bottom: 15px;
        }
        .required {
            color: red;
        }
        .payment-method {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px;
            margin-bottom: 10px;
            cursor: pointer;
            background-color: #fff;
        }
        .payment-method img {
            height: 30px;
            margin-right: 10px;
        }
        .payment-method.disabled {
            opacity: 0.6;
            pointer-events: none;
        }
        .discount-section button {
            background-color: #000;
            color: white;
        }
        .discount-section button:hover {
            background-color: #333;
        }
		.btn-custom{
            background-color: #1E4733;
            border-radius: 8px;
            border: none;
            color: white;
            font-size: 16px;
            padding: 10px 20px;
        }
        .btn.btn-custom:hover{
            background-color: #0f5a29;
            transition: background-color 0.3s ease;
        }
    </style>
@endsection

@section('content')
<div class="container mt-4 checkout-container">
    <div class="row">
        <!-- Shipping Address Section -->
        <div class="col-md-6 col-12 mb-4 left-body mb-4 mb-md-0">
            <h2 class="section-title">Địa Chỉ Giao Hàng</h2>
            <form>
                <div class="mb-3">
                    <label for="name" class="form-label">Họ và Tên <span class="required">*</span></label>
                    <input type="text" class="form-control" id="name" placeholder="Họ và tên">
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">Số Điện Thoại <span class="required">*</span></label>
                    <input type="text" class="form-control" id="phone" placeholder="Số điện thoại">
                </div>
                <div class="mb-3">
                    <label for="city" class="form-label">Thành Phố/Tỉnh <span class="required">*</span></label>
                    <select class="form-select" id="city">
                        <option selected>Lựa chọn Tỉnh/Thành Phố</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="district" class="form-label">Quận/Huyện <span class="required">*</span></label>
                    <select class="form-select" id="district">
                        <option selected>Lựa chọn Quận/Huyện</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="ward" class="form-label">Phường/Xã <span class="required">*</span></label>
                    <select class="form-select" id="ward">
                        <option selected>Lựa chọn Phường/Xã</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Địa Chỉ <span class="required">*</span></label>
                    <input type="text" class="form-control" id="address" placeholder="Nhập địa chỉ giao hàng">
                </div>
				<div class="mb-3">
                    <label for="address-note" class="form-label">Ghi chú <span class="required">*</span></label>
                    <input type="text" class="form-control" id="address-note" placeholder="Nhà có cây xoài to, gọi trước khi giao hàng">
                </div>
				<div class="mb-3">
                    <div class="form-check form-switch mb-3">
                        <input type="checkbox" class="form-check-input" id="defaultAddress" role="switch" aria-checked="false">
						<label class="form-check-label" for="defaultAddress">Đặt làm địa chỉ mặc định</label>
					</div>

					<button type="button" class="btn btn-custom mb-3">XÁC NHẬN ĐỊA CHỈ</button>
                </div>
            </form>
        </div>

        <!-- Payment Method Section -->
        <div class="col-md-6 col-12 right-body ps-md-5">
            <h2 class="section-title">Phương thức thanh toán</h2>
            <p class="text-danger" id="address-warning">Vui lòng nhập địa chỉ giao hàng</p>

            <!-- VNPAY Payment -->
            <div class="payment-method d-flex align-items-center justify-content-between disabled" id="vnpay">
                <div class="d-flex align-items-center">
                    <img src="https://via.placeholder.com/30" alt="VNPAY">
                    <span>
                        VNPAY<br>
                        <small>Thanh toán bằng Thẻ/Tài khoản ngân hàng, QR Pay, Ví điện tử</small>
                    </span>
                </div>
                <input type="radio" name="payment" class="form-check-input">
            </div>

            <!-- Cash on Delivery -->
            <div class="payment-method d-flex align-items-center justify-content-between disabled" id="cod">
                <div class="d-flex align-items-center">
                    <img src="https://via.placeholder.com/30" alt="Cash">
                    <span>Thanh Toán Khi Nhận Hàng</span>
                </div>
                <input type="radio" name="payment" class="form-check-input">
            </div>

            <!-- Discount Code Section -->
            <div class="mt-4 discount-section">
                <h4 class="section-title">Mã giảm giá</h4>
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Mã giảm giá">
                    <button class="btn" type="button">ÁP DỤNG</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection