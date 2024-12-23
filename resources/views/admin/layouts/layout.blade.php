<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>@yield('title', 'Admin Panel')</title>

	<!-- plugins:css -->
	<link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/vendors/ti-icons/css/themify-icons.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/vendors/font-awesome/css/font-awesome.min.css') }}">

	<!-- Import customized bootstrap theme -->
	<link rel="stylesheet" href="{{ asset('css/coconerd-bootstrap.css') }}">

	<!-- use FA-icons, bootstrap-icons-->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

	<!-- import jquery and bootstrap first -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
	
	@yield('lib')

	<!-- Layout styles -->
	<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
	@yield('head-script')
	@yield('style')
</head>

<body>
	<div class="container-scroller">
		@include('admin.layouts.sidebar')

		<div class="container-fluid page-body-wrapper">
			@include('admin.layouts.navbar')

			<div class="main-panel">
				<div class="content-wrapper">
					@yield('content')
				</div>

				@include('admin.layouts.footer')
			</div>
		</div>
	</div>

	<!-- plugins:js -->
	<script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
	<script src="{{ asset('assets/js/off-canvas.js') }}"></script>
	<script src="{{ asset('assets/js/hoverable-collapse.js') }}"></script>
	<script src="{{ asset('assets/js/misc.js') }}"></script>
	@yield('body-script')
</body>

</html>