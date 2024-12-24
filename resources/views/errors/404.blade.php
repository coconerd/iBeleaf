<?php	
	// session_start();
	// use App\Providers\ProductData;
?>

@extends("layouts.layout")
@isset($title)
	@section("title", "$title")
@endisset
@isset($author)
	@section("author", "$author")
@endisset
@isset($author)
	@section("description", "$description")
@endisset

@section("style")
    <link rel="stylesheet" href="{{ asset("css/error/error.css") }}">
@endsection

@section("content")
    <div class="not-found-container">
        <img src="{{ asset("images/error/404.png") }}" alt="404 not found">
    </div>
@endsection

@push('scripts')

@endpush