<!DOCTYPE HTML>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>@yield('title')</title>

	<!-- CSRF Token khi gửi yêu cầu AJAX-->
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<!-- Import bootstrap lib, with customized color pallete-->
	<link rel="stylesheet" href="{{asset('css/coconerd-bootstrap.css')}}">

	<!-- Use FA-icons, bootstrap-icons-->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

	<!-- Header/Footer stylings -->
	<link rel="stylesheet" href="{{asset('css/layouts/header.css')}}">
	<link rel="stylesheet" href="{{asset('css/layouts/footer.css')}}">

	<!-- Scripts that needs to execute quickly on page load -->
	@yield('head-script')

	<!-- View-specific stylings -->
	@yield('style')
</head>

<body>

	@include("layouts.header")

	@yield('content')

	@yield('body-script')

	@include("layouts.footer")

	<!-- Định nghĩa stack scripts này để có thể nhúng file JS xử lý vào các file blade (đối với file blade nào có @push('scripts') -->
	@stack('scripts')
</body>

</html>