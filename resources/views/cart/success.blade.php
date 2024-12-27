@extends('layouts.layout')
@section('title', 'checkout-success')

@section('style')
    <link rel="stylesheet" href="{{ asset('css/cart/checkoutSuccess.css') }}">
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center align-items-center min-vh-100">
        <div class="col-12 text-center p-0">
            <img src="{{ asset('images/checkout-success.png') }}"
                alt="Success"
                class="img-fluid">
            <div class="position-fixed">
                <a href='/' class="btn continue-button">
                    Tiếp tục mua sắm
                    <i class="fa-solid fa-cart-shopping" style="color: #f8f3e7;"></i>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection