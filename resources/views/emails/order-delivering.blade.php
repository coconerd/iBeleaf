<!DOCTYPE html>
<html>

<head>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

	<style>
		body {
			font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
			line-height: 1.6;
			color: #1a1a1a;
			margin: 0;
			padding: 0;
			/* background-color: #f5f5f5; */
		}

		.container {
			max-width: 600px;
			margin: 0 auto;
			padding: 40px 20px;
			background-color: #f7f4f0;
			/* background: #ffffff; */
			box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
		}

		.header {
			text-align: center;
			padding-bottom: 35px;
			margin-bottom: 35px;
			border-bottom: 1px solid #eaeaea;
		}

		.header img {
			width: 140px;
			height: auto;
			margin-bottom: 15px;
		}

		.vintage-border {
			border-radius: 12px;
			padding: 30px;
			background: #f8f9fa;
			margin: 25px 0;
			box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
		}

		.order-status {
			display: flex;
			justify-content: space-between;
			position: relative;
			margin: 50px 0;
			padding: 0 15px;
		}

		.status-item {
			position: relative;
			display: flex;
			align-items: center;
			flex: 1;
		}

		.status-item:last-child {
			flex: 0;
		}

		.status-point {
			width: 24px;
			height: 24px;
			border-radius: 50%;
			background: #e9ecef;
			position: relative;
			z-index: 2;
			box-shadow: 0 0 0 4px rgba(35, 66, 52, 0.1);
		}

		.status-label {
			font-size: 13px;
			color: #495057;
			margin-left: 12px;
			font-weight: 500;
			white-space: nowrap;
		}

		.status-connector {
			flex-grow: 1;
			height: 2px;
			margin: 0 10px;
			position: relative;
			background: transparent;
		}

		.status-connector::after {
			content: '';
			position: absolute;
			top: 50%;
			left: 0;
			right: 0;
			border-top: 2px dashed #e9ecef;
			transform: translateY(-50%);
		}

		.status-item.completed .status-point {
			background: #28a745;
		}

		.status-item.completed .status-connector::after {
			border-color: #28a745;
		}

		.status-item.active .status-point {
			background: #234234;
		}

		.status-item.active .status-connector::after {
			border-color: #234234;
		}

		.detail-row {
			display: flex;
			justify-content: space-between;
			padding: 16px 0;
			border-bottom: 1px solid #eaeaea;
		}

		.detail-row:last-child {
			border-bottom: none;
		}

		.detail-row span {
			color: #6c757d;
		}

		.detail-row strong {
			color: #234234;
			font-weight: 600;
		}

		.btn {
			display: inline-block;
			padding: 14px 28px;
			background: #234234;
			color: white;
			text-decoration: none;
			border-radius: 8px;
			margin: 20px 0;
			font-weight: 500;
			transition: all 0.3s ease;
			box-shadow: 0 2px 4px rgba(35, 66, 52, 0.2);
		}

		.btn:hover {
			background: #2c5242;
			transform: translateY(-1px);
		}

		h1 {
			color: #234234;
			font-family: 'SF Pro Display', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
			font-size: 28px;
			font-weight: 600;
			margin-top: 20px;
			letter-spacing: -0.5px;
		}

		.greeting {
			font-size: 18px;
			color: #495057;
			margin-bottom: 25px;
		}

		.footer-text {
			text-align: center;
			color: #6c757d;
			font-size: 14px;
			margin-top: 50px;
			padding-top: 30px;
			border-top: 1px solid #eaeaea;
		}
	</style>
</head>

<body>
	<div class="container">
		<div class="header">
			{{-- Convert logo to base64 or use absolute URL --}}
			<img src="{{ $message->embed(public_path('images/logos/logo-with-text.png')) }}" alt="Plant Paradise Logo"
				width="140">
			<h1>
				<i class="bi bi-truck"></i>&nbsp;
				Đơn Hàng Đang Được Giao
			</h1>
		</div>

		<p class="greeting">
			Xin chào {{ $order->user->full_name }},<br>
			Đơn hàng của bạn đang được giao đến địa chỉ: <strong>{{ $order->deliver_address }}</strong>.
		</p>

		<div class="vintage-border">
			<div class="detail-row">
				<span>Mã đơn hàng:&nbsp;</span>
				<strong>#{{ $order->order_id }}</strong>
			</div>
			<div class="detail-row">
				<span>Ngày đặt:&nbsp;</span>
				<strong>{{ $order->created_at->format('d/m/Y H:i') }}</strong>
			</div>
			<div class="detail-row">
				<span>Tổng tiền:&nbsp;</span>
				<strong>{{ number_format($order->total_price, 0, ',', '.') }}₫</strong>
			</div>
		</div>

		<div class="order-status">
			<div class="status-item completed">
				<div class="status-point"></div>
				<div class="status-label">Đã xác nhận</div>
				<div class="status-connector"></div>
			</div>
			<div class="status-item active">
				<div class="status-point"></div>
				<div class="status-label">Đang giao hàng</div>
				<div class="status-connector"></div>
			</div>
			<div class="status-item">
				<div class="status-point"></div>
				<div class="status-label">Đã giao</div>
			</div>
		</div>

		<div style="text-align: center; margin: 40px 0; color: white !important;">
			<a href="{{ route('orders.detail', $order->order_id) }}" class="btn" style="color: white !important;">
				Xem Chi Tiết Đơn Hàng
			</a>
		</div>

		<p class="footer-text">
			Cảm ơn bạn đã mua sắm tại Plant Paradise. Đừng ngần ngại liên hệ chúng tôi nếu bạn cần hỗ trợ.
		</p>
		<p class="footer-text">
			Hotline: <strong>18-1234-5678</strong><br>
			Email: <a href="mailto:hotro@plantparadise.com">hotro@plantparadise.com</a>
		</p>
	</div>
</body>

</html>