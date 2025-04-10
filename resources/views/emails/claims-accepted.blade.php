<!DOCTYPE html>
<html>

<head>
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

		.claim-type {
			display: inline-block;
			padding: 8px 16px;
			border-radius: 50px;
			font-size: 0.9rem;
			font-weight: 500;
			margin-bottom: 20px;
		}

		.claim-type.refund {
			background-color: #FFE4E1;
			color: #D42426;
		}

		.claim-type.return {
			background-color: #E8F5E9;
			color: #2E7D32;
		}

		.next-steps {
			background: #f8f9fa;
			border-radius: 12px;
			padding: 20px;
			margin: 25px 0;
		}

		.next-steps h4 {
			color: #234234;
			margin-bottom: 15px;
		}

		.next-steps ol {
			margin: 0;
			padding-left: 20px;
		}

		.next-steps li {
			margin-bottom: 10px;
			color: #495057;
		}
	</style>
</head>

<body>
	<div class="container">
		<div class="header">
			<img src="{{ $message->embed(public_path('images/logos/logo-with-text.png')) }}" alt="Plant Paradise Logo"
				width="140">
			<h1>
				<i class="bi bi-check-circle"></i>&nbsp;
				Yêu Cầu Đã Được Chấp Nhận
			</h1>
		</div>

		<p class="greeting">
			Xin chào {{ $request->user->full_name }},<br>
			Chúng tôi đã chấp nhận yêu cầu {{ $request->type === 'refund' ? 'trả hàng, hoàn tiền' : 'đổi hàng' }} của
			bạn.
		</p>

		<div class="vintage-border">
			<div class="detail-row">
				<span>Mã yêu cầu:&nbsp;</span>
				<strong>#{{ $request->return_refund_id }}</strong>
			</div>
			<div class="detail-row">
				<span>Loại yêu cầu:&nbsp;</span>
				<span class="claim-type {{ $request->type }}">
					{{ $request->type === 'refund' ? 'Trả hàng & Hoàn tiền' : 'Đổi hàng' }}
				</span>
			</div>
			<div class="detail-row">
				<span>Sản phẩm:&nbsp;</span>
				<strong>{{ $request->order_item->product->name }}</strong>
			</div>
			<div class="detail-row">
				<span>Số lượng:&nbsp;</span>
				<strong>{{ $request->quantity }}</strong>
			</div>
		</div>

		<div class="next-steps">
			<h4>Các bước tiếp theo:&nbsp;</h4>
			<ol>
				@if($request->type === 'refund')
					<li>Đóng gói sản phẩm cẩn thận và gửi lại cho chúng tôi.</li>
					<li>Sau khi chúng tôi nhận được hàng trả lại, chúng tôi sẽ tiến hành hoàn tiền.</li>
					<li>Tiền hoàn trả sẽ được chuyển vào tài khoản của bạn trong vòng 3-5 ngày làm việc.</li>
				@else
					<li>Đóng gói sản phẩm cần đổi và gửi lại cho chúng tôi.</li>
					<li>Sau khi nhận được sản phẩm, chúng tôi sẽ gửi sản phẩm mới cho bạn.</li>
					<li>Bạn sẽ nhận được thông báo khi sản phẩm mới được gửi đi.</li>
				@endif
			</ol>
		</div>

		<div style="text-align: center; margin: 40px 0;">
			<a href="{{ route('profile.returns') }}" class="btn" style="color: white !important;">
				Xem Chi Tiết Yêu Cầu
			</a>
		</div>
	</div>

	<p class="footer-text"></p>
	Cảm ơn bạn đã tin tưởng Plant Paradise. Chúng tôi sẽ xử lý yêu cầu của bạn một cách nhanh nhất.
	</p>
	<p class="footer-text"></p>
	Hotline: <strong>18-1234-5678</strong><br>
	Email: <a href="mailto:hotro@plantparadise.com">hotro@plantparadise.com</a>
	</p>
	</div>
</body>

</html>