<!DOCTYPE HTML>
<html>
	
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content= "@yield('author', 'CocoNerd')">
    <meta name="description" content= "@yield('description', 'This is the best place to ...')">
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<title>@yield('title', "Plant Ecommerce")</title>
	<link rel="shortcut icon" href="{{asset('images/favicon/plantEcommerce.png')}}" type="image/x-icon">

	<!-- Import Jquery Jquery --->
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>

	<!-- Import bootstrap lib, with customized color pallete-->
	<link rel="stylesheet" href="{{asset('css/coconerd-bootstrap.css')}}">

	<!-- Use FA-icons, bootstrap-icons-->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

	<!-- Header/Footer stylings -->
	<link rel="stylesheet" href="{{asset('css/layouts/header.css')}}">
	<link rel="stylesheet" href="{{asset('css/layouts/footer.css')}}">

	<!-- View-specific stylings -->
	@yield(section: "style")

	<!-- JavaScript files -->	
	@stack('scripts')

</head>

<body>
	@include("layouts.header")

	@yield(section: "content")

	@include("layouts.footer")
</body>

</html>