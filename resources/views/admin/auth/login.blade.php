<!DOCTYPE html>
<html>

<head>
	<title>Admin Login</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
	<link rel="stylesheet" href="{{ asset('css/admin/auth/login.css') }}">
</head>

<body>
	<div class="admin-login-container">
		<div class="admin-login-header">
			<h1>Plant Paradise Admin</h1>
			<p class="text-muted">Nhập thông tin để vào bảng điều khiển admin</p>
		</div>

		@if($errors->any())
			<div class="alert alert-danger">
				@switch ($errors->first())
					@case('Invalid credentials')
						Thông tin đăng nhập không chính xác.
						@break
					@default
						Có lỗi xảy ra. Vui lòng thử lại sau.
						@break
				@endswitch
			</div>
		@endif

		<form method="POST" action="{{ route('admin.auth.handleLogin') }}">
			@csrf
			<div class="mb-3">
				<input type="text" class="form-control" placeholder="Tên đăng nhập" name="username" required>
			</div>
			<div class="mb-3">
				<div class="input-group">
					<input type="password" style="" class="form-control" placeholder="Mật khẩu" name="password"
						id="adminPassword">
				</div>
			</div>
			<button type="submit" class="btn btn-admin-login">Login</button>
		</form>
	</div>

	<script>
		function toggleAdminPasswordVisibility() {
			const passwordInput = document.getElementById('adminPassword');
			const icon = document.getElementById('toggleAdminPasswordIcon');

			if (passwordInput.type === 'password') {
				passwordInput.type = 'text';
				icon.classList.remove('bi-eye-slash');
				icon.classList.add('bi-eye');
			} else {
				passwordInput.type = 'password';
				icon.classList.remove('bi-eye');
				icon.classList.add('bi-eye-slash');
			}
		}
	</script>
</body>

</html>