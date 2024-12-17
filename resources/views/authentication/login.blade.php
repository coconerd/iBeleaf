@extends("layouts.layout")
@section("title", "authentication")
@section("style")
<style>
	/* Container and background styling */
	.login-page {
		min-height: 100vh;
		display: flex;
		align-items: center;
		justify-content: center;
		background-color: #eee;
		background-image: url('https://sunshinecoastplants.com.au/cdn/shop/articles/Tropical-plants_1_bb553c55-286a-4b47-bcb8-21716e7b1495_1567x.jpg?v=1646363061');
		background-size: cover;
		filter: saturate(1.5);
		background-repeat: FALSE;
		padding: 10px;
		margin-bottom: 15px;
		margin-top: 15px;
	}

	/* Left section styling */
	.login-left {
		background-color: #ffffff;
		padding: 40px;
		text-align: center;
	}

	.login-left h2 {
		font-size: 2rem;
		color: #234234;
		font-weight: bold;
	}

	.login-left p {
		font-size: 1rem;
		color: #666;
		margin-bottom: 1.5rem;
	}

	.login-left .btn-google,
	.btn-email {
		background-color: #f5f5f5;
		color: #234234;
		font-weight: 500;
		margin-bottom: 1rem;
		width: 100%;
		text-align: left;
		padding: 0.8rem;
	}

	.login-left .btn-google i,
	.btn-email i {
		font-size: 1.2rem;
		margin-right: 0.5rem;
	}

	.plant-image {
		margin-top: 2rem;
		max-width: 70%;
	}

	/* Right section styling */
	.login-right {
		background-color: #234234;
		padding: 60px;
		color: #fff;
		display: flex;
		align-items: center;
		justify-content: center;
		position: relative;
	}

	.login-box {
		background-color: rgba(255, 255, 255, 0.1);
		border-radius: 12px;
		padding: 30px;
		border-radius: 8px;
		width: 100%;
		max-width: 400px;
		z-index: 100;
	}

	.login-box h4 {
		font-size: 1.1rem;
		font-weight: 500;
		color: #ffffffcc;
		text-align: center;
		margin-bottom: 1.5rem;
	}

	.login-box .form-control {
		border-radius: 5px;
		background-color: #ffffff;
		border: none;
		padding: 0.8rem;
		font-size: 1rem;
	}

	.login-box .btn-login {
		background-color: #234234;
		color: white;
		font-weight: 500;
		border-radius: 20px;
		padding: 0.8rem;
		width: 100%;
		margin-top: 1rem;
	}

	.login-box .forgot-password,
	.register-link {
		color: #d5d5d5;
		font-size: 0.9rem;
	}

	.leaf-decorations {
		position: absolute;
		top: 0;
		right: 0;
		max-height: 100%;
		opacity: 0.3;
	}

	.container-fluid {
		padding: 0;
	}
</style>
@endsection
@section("content")
<div class="container-fluid login-page">
	<div class="row g-0">
		<!-- Left side with welcome text -->
		<div class="col-md-6 login-left d-flex flex-column justify-content-center align-items-center">
			<h2>Welcome Back!</h2>
			<p>We source the healthiest and most beautiful plants to bring natures finest to your home. We provide
				expert care advice to ensure your plants thrive.</p>
			<button onclick="event.preventDefault(); document.getElementById('google-login-form').submit();"
				type="submit" class="btn btn-google d-flex align-items-center justify-content-center">
				<i class="bi bi-google"></i> Đăng nhập với Google
			</button>
			<span>or</span>
			<button onclick="event.preventDefault(); document.getElementById('facebook-login-form').submit();"
				type="submit" class="btn btn-google d-flex align-items-center justify-content-center">
				<i class="bi bi-facebook"></i> Đăng nhập với Facebook
			</button>
			<form id="google-login-form" action="/auth/login/google" method="POST" style="display: none;">
				@csrf
			</form>
			<form id="facebook-login-form" action="/auth/login/facebook" method="POST" style="display: none;">
				@csrf
			</form>
			<img src="{{asset(path: 'images/transparent-plant-pot.png')}}" alt="Plant" height="300" class="plant-image">
		</div>

		<!-- Right side with login form -->
		<div class="col-md-6 login-right">
			<!-- <img src="https://wallpaper.dog/large/20474544.jpg" alt="Leaf decorations" class="leaf-decorations"> -->
			<div class="login-box">
				<h4>Vui lòng nhập thông tin đăng nhập</h4>
				<form method="POST" action="/auth/login">
					@csrf
					<div class="mb-3">
						<input type="email" class="form-control" placeholder="Địa chỉ email" name="email">
					</div>
					<div class="mb-3">
						<input type="password" style="border: none;" class="form-control" placeholder="Mật khẩu"
							name="password" id="password">
					</div>
					<div class="d-flex justify-content-between">
						<span class="forgot-password">Quên mật khẩu?</span>
					</div>
					<button type="submit" class="btn btn-login">Đăng nhập</button>
					<div class="text-center mt-3">
						<span class="register-link">Bạn chưa có tài khoản? <a class="text-teal-500"
								href="/auth/register">Đăng ký</a>
						</span>
					</div>
					@if (session('registerSuccess'))
						<div class="mt-2 alert alert-success">
							{{ session(key: 'registerSuccess') }}
						</div>
					@endif
					@if ($errors->any())
						<div class="mt-2 alert alert-danger">
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif
				</form>
			</div>
		</div>
	</div>
</div>
@endsection