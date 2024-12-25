@extends('layouts.layout')
@section('title', 'checkout-success')

@section('style')
    <link rel="stylesheet" href="{{ asset('css/cart/success.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="container py-5">
    <div class="row min-vh-80 d-flex align-items-center justify-content-center" id="success-container">
        <div class="col-md-8 text-center">
            <!-- Success Animation -->
            <div class="success-animation mb-4">
                <div class="checkmark-circle">
                    <i class="fas fa-check text-success" style="font-size: 50px;"></i>
                </div>
            </div>

            <!-- Success Message -->
            <h1 class="display-4 mb-3 title">Mua sắm thành công!</h1>
            <p class="text-muted lead mb-4">
                Cảm ơn bạn vì đã tin tưởng <span class="h6 d-inline" id="website-name">Plant Paradise 🌱</span>. Chúc bạn có những trải nghiệm mua sắm tuyệt vời!
            </p>

            <!-- Action Buttons -->
            <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                <a href="/" class="btn btn-primary px-4 py-2 me-sm-3">
                    Quay lại mua sắm  ️🛒
                </a>
            </div>
        </div>
    </div>
    </div>
</div>
@endsection