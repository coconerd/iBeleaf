@extends('layouts.app')

@section('content')
<div class="order-success-container">
    <div class="success-header">
        <i class="fas fa-check-circle"></i>
        <h1>Đặt hàng thành công!</h1>
        <p>Mã đơn hàng: #{{ $order->order_id }}</p>
    </div>

    <div class="order-details">
        <h2>Chi tiết đơn hàng</h2>
        <div class="details-grid">
            <div class="detail-item">
                <span>Tổng tiền:</span>
                <strong>{{ number_format($order->total_price) }} VND</strong>
            </div>
            <div class="detail-item">
                <span>Phương thức thanh toán:</span>
                <strong>{{ $order->payment_method }}</strong>
            </div>
        </div>
    </div>

    <div class="action-buttons">
        <a href="{{ route('home') }}" class="btn continue-shopping">
            <i class="fas fa-shopping-cart"></i> Tiếp tục mua sắm
        </a>
    </div>
</div>

<style>
.order-success-container {
    max-width: 800px;
    margin: 50px auto;
    padding: 30px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.1);
}

.success-header {
    text-align: center;
    margin-bottom: 40px;
}

.success-header i {
    font-size: 80px;
    color: #4CAF50;
    margin-bottom: 20px;
}

.order-details {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    margin: 30px 0;
}

.details-grid {
    display: grid;
    gap: 15px;
    margin-top: 20px;
}

.detail-item {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid #eee;
}

.action-buttons {
    display: flex;
    justify-content: center;
    gap: 20px;
    margin-top: 40px;
}

.btn {
    padding: 12px 25px;
    border-radius: 5px;
    text-decoration: none;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
}

.track-order {
    background: #4CAF50;
    color: white;
}

.continue-shopping {
    background: #2196F3;
    color: white;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}
</style>
@endsection