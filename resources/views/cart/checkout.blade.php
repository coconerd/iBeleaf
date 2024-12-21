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
						<label class="form-check-label" for="defaultAddress">Đặt làm thông tin mặc định</label>
					</div>
					<button type="button" class="btn btn-custom mb-3">XÁC NHẬN ĐỊA CHỈ</button>
				</div>
			</form>
        </div>

        <div class="col-md-6 col-12 right-body ps-md-5">
			<!--Order Summary-->
			<div class="container mt-5" id="order-summary">
				<div class="title-icon">
					<span class="section-title">Thông tin đơn hàng</span>
					<i class="fa-solid fa-seedling" style="color: #469636;"></i>
				</div>
				<hr class="line">
				<div class="mt-4">
					<div class="order-items">
					<!-- Single Order Item -->
						<div class="d-flex gap-3">
							<div class="order-item-image position-relative">
								<span class="item-quantity-badge">1</span>
								<img src="https://via.placeholder.com/150" alt="Product Image">
							</div>
							<div class="order-item-details">
								<h5 class="item-title">Tempting Tuna Cat Treats</h5>
								<p class="item-variant">Brand: Temptations</p>
								<div class="d-flex justify-content-between align-items-center">
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
							<span id="shipping-fee">Chưa Tính Toán</span>
						</div>
						<div class="summary-row total">
							<span>Tổng tiền (Đã bao gồm VAT)</span>
							<span class="total-amount">799.000 VNĐ</span>
						</div>
					</div>

					<button class="btn btn-custom w-100">Thanh toán</button>
				</div>
			</div>

			<!--Shipping Privacy-->
			<div id="shipping-privacy">
				<div class="title-icon">
					<span class="section-title">Qui định về phí vận chuyển</span>
					<i class="fa-solid fa-seedling" style="color: #469636;"></i>
				</div>
				<hr class="line">
				<div class="shipping-privacy-content">
					<p>
						<i class="fa-solid fa-circle-info" style="color: #c78b5e;"></i>
						<span>Chỉ nhận giao ngoại thành đối với các sản phẩm là CHẬU</span>
					</p>
					<p>
						<i class="fa-solid fa-circle-info" style="color: #c78b5e;"></i>
						<span>Phí vận chuyển ngoại thành sẽ được tính dựa trên <b>cân nặng sản phẩm và địa điểm nhận hàng</b></span>
					</p>
					<p>
						<i class="fa-solid fa-circle-info" style="color: #c78b5e;"></i>
						<span>Phí vận chuyển nội thành TP.HCM đồng giá 30,000 VND</span>
					</p>
				</div>
			</div>
			
			<!--Payment Method-->
			<div id="payment-body">
				<div class="title-icon">
					<span class="section-title">Phương thức thanh toán</span>
					<i class="fa-solid fa-seedling" style="color: #469636;"></i>
				</div>
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

					<div class="payment-method d-flex align-items-center justify-content-between disabled" class="type">
						<div class="d-flex align-items-center">
							<img src="https://www.muji.com.vn/_next/static/media/cod.6f018bcd.svg" alt="Cash">
							<span class="payment-type">Thanh Toán Khi Nhận Hàng</span>
						</div>
						<input type="radio" name="payment" class="form-check-input">
					</div>
			</div>
        </div>
    </div>
</div>
@endsection