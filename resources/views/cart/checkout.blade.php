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

            <span class="section-title">Phương thức thanh toán</span>
			<hr class="line">
            <p class="text-danger" id="address-warning">Vui lòng nhập địa chỉ giao hàng</p>
			
            <!-- Banking Payment -->
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

			<!--Order Summary-->
			<div class="container mt-5">
				<span class="section-title">Thông tin đơn hàng</span>
				<hr class="line">
				<div class="order-summary mt-4">
					<div class="d-flex">
						<img src="https://via.placeholder.com/150" alt="Ao Cardigan">
						<div class="ml-3">
							<h5>Áo Cardigan Cổ V MUJI</h5>
							<p>Màu Sắc: Nâu Mocha Đậm</p>
							<p>Kích Cỡ: L</p>
							<p>Số lượng: 1</p>
						</div>
					</div>
					<div class="mt-3">
						<p>Tạm tính (1 mặt hàng): <span class="float-right">799.000 VNĐ</span></p>
						<p>Phí vận chuyển: <span class="float-right">Chưa Tính Toán</span></p>
						<p>Tổng tiền: <span class="total float-right">799.000 VNĐ</span> (Đã bao gồm VAT)</p>
					</div>
					<button class="btn btn-custom btn-block mt-4">Thanh toán</button>
				</div>
			</div>
        </div>
    </div>
</div>
</div>
@endsection