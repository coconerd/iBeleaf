@extends('layouts.layout')
@section('title', 'checkout-page')

@section("body-script")
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/cart/checkOutManager.js') }}"></script>
@endsection

@section('style')
	<link rel="stylesheet" href="{{ asset('css/cart/checkout.css') }}">
@endsection

@section('content')
<div class="container mt-4 checkout-container">
    <div class="row">

        <!-- Shipping Address Section -->
        <div class="col-md-6 col-12 mb-4 mb-4 mb-md-0 left-body">
			<div class="d-flex align-items-center">
				<i class="fas fa-shipping-fast me-3 fa-2x" style="color: #c78b5e;"></i>
				<span class="section-title">Địa Chỉ Giao Hàng</span>
			</div>
			<hr class="line">
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
					<label for="province" class="form-label">Thành Phố/Tỉnh <span class="required">*</span></label>
					<select class="form-select" id="province">
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
        <class class="col-md-6 col-12 right-body ps-md-5">
			<!-- Shipping privacy-->
			<div id="shipping-privacy">
            	<span class="section-title">Qui định về phí vận chuyển</span>
				<hr class="line">
				<div class="shipping-privacy-content">
					<p>Phí vận chuyển sẽ được tính dựa trên địa chỉ giao hàng và loại sản phẩm bạn mua!</p>
					<p><i class="fa-solid fa-circle-info" style="color: #c78b5e;"></i> Dieu 1</p>
					<p><i class="fa-solid fa-circle-info" style="color: #c78b5e;"></i> Dieu 2</p>
					<p><i class="fa-solid fa-circle-info" style="color: #c78b5e;"></i> Dieu 3</p>
				</div>
			</div>
			
			<div id="payment-body">
				<span class="section-title">Phương thức thanh toán</span>
				<hr class="line">
				<p class="text-danger" id="address-warning">Vui lòng nhập địa chỉ giao hàng</p>
					<div class="payment-method d-flex align-items-center justify-content-between disabled" class="type">
						<div class="d-flex align-items-center">
							<img src="https://www.muji.com.vn/_next/static/media/payment_online.20c772c1.svg" alt="BANKING">
							<span>
								<span class="payment-type">BANKING</span><br>
								<small>Chuyển khoản ngân hàng, MOMO, VNPAY, Apple Pay</small>
							</span>
						</div>
						<input type="radio" name="payment" class="form-check-input">
					</div>

					<!-- Cash on Delivery -->
					<div class="payment-method d-flex align-items-center justify-content-between disabled" class="type">
						<div class="d-flex align-items-center">
							<img src="https://www.muji.com.vn/_next/static/media/cod.6f018bcd.svg" alt="Cash">
							<span class="payment-type">Thanh Toán Khi Nhận Hàng</span>
						</div>
						<input type="radio" name="payment" class="form-check-input">
					</div>
			</div>
			<!--Order Summary-->
			<div class="container mt-5" id="order-summary">
				<span class="section-title">Thông tin đơn hàng</span>
				<hr class="line">
				<div class="mt-4">
					<div class="order-items">
					<!-- Single Order Item -->
					<div class="order-item">
						<div class="d-flex gap-3">
							<div class="order-item-image position-relative">
								<span class="item-quantity-badge">1</span>
								<img src="https://via.placeholder.com/150" alt="Product Image">
							</div>
							<div class="order-item-details flex-grow-1">
								<div class="d-flex justify-content-between align-items-start">
									<div>
										<h5 class="item-title mb-1">Tempting Tuna Cat Treats</h5>
										<p class="item-brand">Temptations</p>
									</div>
									<span class="item-price">$5.99</span>
								</div>
							</div>
						</div>
					</div>

				<div class="order-summary-details">
					<div class="summary-row">
						<span>Tạm tính (1 mặt hàng)</span>
						<span>799.000 VNĐ</span>
					</div>
					<div class="summary-row">
						<span>Phí vận chuyển</span>
						<span class="shipping-cost">Chưa Tính Toán</span>
					</div>
					<div class="summary-row total">
						<span>Tổng tiền (Đã bao gồm VAT)</span>
						<span class="total-amount">799.000 VNĐ</span>
					</div>
				</div>

				<button class="btn btn-custom w-100">Thanh toán</button>
				</div>
			</div>
        </div>
    </div>
</div>
</div>
@endsection