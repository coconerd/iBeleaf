@extends('layouts.layout')
@section('title', 'Profile Page')

@section('head-script')
<!-- Font import -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,700;1,700&display=swap" rel="stylesheet">
@endsection

@section("style")
<link rel="stylesheet" href="{{ asset('css/profile/homePage.css') }}">
<link rel="stylesheet" href="{{ asset('css/profile/currentPasswordInputForm.css') }}">
<link rel="stylesheet" href="{{ asset('css/profile/verifyNewPasswordInputForm.css') }}">
<!-- Import Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/profile/orders.css') }}">
<style>
	/* Lúc đầu code CSS cho giao diện chính của trang hồ sơ ở đây, 
       nhưng code CSS quá dài nên tách ra thành file CSS riêng, lưu ở public/css/profile/homePage.css 
       và nhúng file đó thông qua link href 
    */
</style>
@endsection

@section("content")
<!-- Phần hiển thị thông báo -->
<div class="custom-alert-container">
	<!-- Phần hiển thị lỗi khi validate form (nếu có) -->
	@if ($errors->any())
		<div class="alert alert-danger" id="validationAlert">
			<ul>
				@foreach ($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ul>
		</div>
	@endif
	<!-- Phần hiển thị thông báo từ session (nếu có) -->
	@if (session('success'))
		<div class="alert alert-success">{{ session('success') }}</div>
	@endif
	@if (session('info'))
		<div class="alert alert-info">{{ session('info') }}</div>
	@endif
	@if (session('error'))
		<div class="alert alert-danger">{{ session('error') }}</div>
	@endif
</div>

<!-- Giao diện chính của trang profile -->
<div class="profile-page-container-outer">
	<div class="profile-page-container">
		<div class="profile-page-main">
			<div class="left-container">
				<div class="left-container__top-cluster">
					<a class="top-left-cluster" href="{{route('profile.homePage')}}">
						<div class="avatar">
							<div class="avatar__placeholder">
								<svg enable-background="new 0 0 15 15" viewBox="0 0 15 15" x="0" y="0"
									class="svg-icon-headshot">
									<g class="group-svg">
										<circle cx="7.5" cy="4.5" fill="none" r="3.8" stroke-miterlimit="10"></circle>
										<path d="m1.5 14.2c0-3.3 2.7-6 6-6s6 2.7 6 6" fill="none" stroke-linecap="round"
											stroke-miterlimit="10"></path>
									</g>
								</svg>
							</div>
						</div>
					</a>

					<div class="top-right-cluster">
						<div class="top-right-cluster__username">
							@if (auth()->check())
								{{$user->user_name}}
							@endif
						</div>
						<div
							style="color: rgba(0,0,0,0.8); display: block; font-size: 14px; height: 16.8px; width: 115px; unicode-bidi: isolate; line-height: 16.8px; text-size-adjust: 100%">
							<a class="pen-icon-modifiy-profile" href="{{route('profile.homePage')}}">
								<svg width="12" height="12" viewBox="0 0 12 12" xmlns="http://www.w3.org/2000/svg"
									style="margin-right: 4px;">
									<path
										d="M8.54 0L6.987 1.56l3.46 3.48L12 3.48M0 8.52l.073 3.428L3.46 12l6.21-6.18-3.46-3.48"
										fill="#9B9B9B" fill-rule="evenodd"></path>
								</svg>
								Sửa hồ sơ
							</a>
						</div>
					</div>
				</div>

				<div class="left-container__bottom-cluster">
					<div class="stardust-dropdown">
						<div class="stardust-dropdown__item-header">
							<a class="stardust-dropdown__item" id="stardust-dropdown__item-homepage"
								href="{{route('profile.homePage')}}">
								<div class="stardust-dropdown__item-icon">
									<img src="{{asset('images/icons8-registration-50.png')}}"
										class="stardust-dropdown__item-icon-img">
								</div>
								<div class="stardust-dropdown__item-text">
									<span class="stardust-dropdown__item-text-span">Hồ sơ</span>
								</div>
							</a>
						</div>
						<div class="stardust-dropdown__item-body" style="opacity: 0;">
							<div class="stardust-dropdown__item-body-padding"></div>
						</div>
					</div>

					<div class="stardust-dropdown">
						<div class="stardust-dropdown__item-header">
							<a class="stardust-dropdown__item" id="stardust-dropdown__item-changePassword"
								href="{{route('profile.currentPassword')}}">
								<div class="stardust-dropdown__item-icon">
									<img src="{{asset('images/icons8-access-50.png')}}"
										class="stardust-dropdown__item-icon-img">
								</div>
								<div class="stardust-dropdown__item-text">
									<span class="stardust-dropdown__item-text-span">Đổi mật khẩu</span>
								</div>
							</a>
						</div>
						<div class="stardust-dropdown__item-body" style="opacity: 0;">
							<div class="stardust-dropdown__item-body-padding"></div>
						</div>
					</div>

					<div class="stardust-dropdown">
						<div class="stardust-dropdown__item-header">
							<a class="stardust-dropdown__item orders-link" href="{{ route('profile.showOrdersForm') }}">
								<div class="stardust-dropdown__item-icon">
									<img src="{{asset('images/icons8-order-50.png')}}"
										class="stardust-dropdown__item-icon-img">
								</div>
								<div class="stardust-dropdown__item-text">
									<span class="stardust-dropdown__item-text-span">Đơn mua</span>
								</div>
							</a>
						</div>
						<div class="stardust-dropdown__item-body" style="opacity: 0;">
							<div class="stardust-dropdown__item-body-padding"></div>
						</div>
					</div>

					<div class="stardust-dropdown">
						<div class="stardust-dropdown__item-header">
							<a class="stardust-dropdown__item returns" href="{{route('profile.returns')}}">
								<div class="stardust-dropdown__item-icon">
									<img src="{{asset('images/icons8-return-purchase-50.png')}}"
										class="stardust-dropdown__item-icon-img">
								</div>
								<div class="stardust-dropdown__item-text">
									<span class="stardust-dropdown__item-text-span">Đổi trả hàng</span>
								</div>
							</a>
						</div>
						<div class="stardust-dropdown__item-body" style="opacity: 0;">
							<div class="stardust-dropdown__item-body-padding"></div>
						</div>
					</div>

					<!-- Logout button -->
					<div class="stardust-dropdown" style="margin-top: auto;">
						<div class="stardust-dropdown__item-header">
							<form method="POST" action="{{ route('auth.logout') }}" style="width: 100%;">
								@csrf
								<button type="submit" class="stardust-dropdown__item" style="width: 100%; background: none; border: none; text-align: left;">
									<div class="stardust-dropdown__item-icon">
									</div>
										<i class="fa-solid fa-right-from-bracket" style="font-size: 1.2rem; color: #384da3; position: relative; right: 32px;"></i>
									<div class="stardust-dropdown__item-text d-flex" style="position:relative; right: 25px;">
										<span class="stardust-dropdown__item-text-span">Đăng xuất</span>
									</div>
								</button>
							</form>
						</div>
						<div class="stardust-dropdown__item-body" style="opacity: 0;">
							<div class="stardust-dropdown__item-body-padding"></div>
						</div>
					</div>
				</div>
			</div>

			<div class="right-container">
				<div class="right-container-main" role="main">
					<div
						style="color: rgba(0, 0, 0, 0.8); display: contents; font-size: 14px; height: auto; line-height: 16.8px; text-size-adjust: 100%; unicode-bidi: isolate; width: auto;">
						<div class="right-container-main-inner">
							<div class="right-container-main-inner__top">
								<h1 id="right-container-main-inner__top-h1">Hồ sơ của tôi</h1>
								<div id="right-container-main-inner__top-div">Quản lý thông tin hồ sơ để bảo mật tài
									khoản</div>
							</div>
							<div class="right-container-main-inner__bottom" id="right-container-main-inner__bottom">
								<div class="right-container-main-inner__bottom__left-cluster">
									<form method="POST" action="{{ route('profile.update') }}"
										style="color: rgba(0, 0, 0, 0.8); display: block; font-size: 14px; line-height: 16.8px; margin-top: 0px; text-size-adjust: 100%; unicode-bidi: isolate; width: 502px; height: 457px;">
										@csrf
										<table class="myprofile-table">
											<tbody>
												<tr class="myprofile-table-row" id="myprofile-table-row1">
													<td class="myprofile-table-row-td1" id="myprofile-table-row1td1">
														<label class="myprofile-table-row-td1__label">Tên đăng
															nhập</label>
													</td>
													<td class="myprofile-table-row-td2" id="myprofile-table-row1td2">
														<div class="myprofile-table-row-td2__div" style="height: 64px;">
															<div class="myprofile-table-row-td2__div-content" style="border-radius: 7px;">
																<!-- <input type="text" placeholder="" class="profile-username-input" value="{{ auth()->check() ? auth()->user()->user_name : '' }}" > -->
																<input type="text" name="username" placeholder=""
																	class="profile-username-input"
																	value="{{ auth()->check() ? $user->user_name : '' }}">
															</div>
															<div class="myprofile-table-row-td2__div-note">Tên đăng nhập
																chỉ có thể thay đổi một lần</div>
														</div>
													</td>
												</tr>
												<tr class="myprofile-table-row" id="myprofile-table-row2">
													<td class="myprofile-table-row-td1" id="myprofile-table-row2td1">
														<label class="myprofile-table-row-td1__label">Tên</label>
													</td>
													<td class="myprofile-table-row-td2" id="myprofile-table-row2td2">
														<div class="myprofile-table-row-td2__div" style="height: 40px;">
															<div class="myprofile-table-row-td2__div-content" style="border-radius: 7px;">
																<input type="text" name="fullname" placeholder=""
																	class="profile-name-input"
																	value="{{ auth()->check() ? $user->full_name : '' }}">
															</div>
														</div>
													</td>
												</tr>
												<tr class="myprofile-table-row" id="myprofile-table-row3">
													<td class="myprofile-table-row-td1" id="myprofile-table-row3td1">
														<label class="myprofile-table-row-td1__label">Email</label>
													</td>
													<td class="myprofile-table-row-td2" id="myprofile-table-row3td2">
														<div class="myprofile-table-row-td2__div-modificable">
															<div class="profile-email-show" id="profile-show-email"
																style="width: 300px;">
																<!-- @if (auth()->check())
                                                                    {{auth()->user()->email}}
                                                                @endif -->
																@if (auth()->check())
																	{{$user->email}}
																@endif
															</div>
															<input type="hidden" name="email" id="hidden-email">
															<!-- data-bs-toggle và data-bs-target dành cho bootstrap 5 -->
															<button type="button"
																class="profile-attribute-modify-button"
																data-bs-toggle="modal" data-bs-target="#emailModal">Thay
																đổi</button>
														</div>
													</td>
												</tr>
												<tr class="myprofile-table-row" id="myprofile-table-row4">
													<td class="myprofile-table-row-td1" id="myprofile-table-row4td1">
														<label class="myprofile-table-row-td1__label">Số điện
															thoại</label>
													</td>
													<td class="myprofile-table-row-td2" id="myprofile-table-row4td2">
														<div class="myprofile-table-row-td2__div-modificable">
															<div class="profile-phonenumber-show"
																id="profile-show-phone" style="width: 300px;">
																<!-- @if (auth()->check())
                                                                    {{auth()->user()->phone_number}}
                                                                @endif -->
																@if (auth()->check())
																	{{$user->phone_number}}
																@endif
															</div>
															<input type="hidden" name="phone" id="hidden-phone">
															<!-- data-bs-toggle và data-bs-target dành cho bootstrap 5 -->
															<button type="button"
																class="profile-attribute-modify-button"
																data-bs-toggle="modal" data-bs-target="#phoneModal">Thay
																đổi</button>
														</div>
													</td>
												</tr>
												<tr class="myprofile-table-row" id="myprofile-table-row5">
													<td class="myprofile-table-row-td1" id="myprofile-table-row5td1">
														<label class="myprofile-table-row-td1__label">Giới tính</label>
													</td>
													<td class="myprofile-table-row-td2" id="myprofile-table-row5td2">
														<div class="myprofile-table-row-td2__div__gender-ratio"
															style="height: 18 px;">
															<label class="profile-gender-radio__label">
																<input type="radio" name="gender" value="Nam"
																	class="profile-gender-radio__input" {{ old('gender', auth()->check() ? $user->gender : '') == 'Nam' ? 'checked' : '' }}> Nam
															</label>
															<label class="profile-gender-radio__label">
																<input type="radio" name="gender" value="Nữ"
																	class="profile-gender-radio__input" {{ old('gender', auth()->check() ? $user->gender : '') == 'Nữ' ? 'checked' : '' }}> Nữ
															</label>
															<label class="profile-gender-radio__label">
																<input type="radio" name="gender" value="Khác"
																	class="profile-gender-radio__input" {{ old('gender', auth()->check() ? $user->gender : '') == 'Khác' ? 'checked' : '' }}> Khác
															</label>
														</div>
													</td>
												</tr>
												<tr class="myprofile-table-row" id="myprofile-table-row6">
													<td class="myprofile-table-row-td1" id="myprofile-table-row6td1">
														<label class="myprofile-table-row-td1__label">Ngày sinh</label>
													</td>
													<td class="myprofile-table-row-td2" id="myprofile-table-row6td2">
														<div class="myprofile-table-row-td2__div-modificable"
															style="display: flex; height: 17.6px; align-items: center;">
															<div class="profile-birthday-show" id="profile-show-dob"
																style="width: 300px;">
																<!-- @if(auth()->check())
                                                                    {{auth()->user()->date_of_birth}}
                                                                @endif -->
																@if (auth()->check())
																	{{ date('Y-m-d', strtotime($user->date_of_birth)) }}
																@endif
															</div>
															<input type="hidden" name="dob" id="hidden-dob">
															<!-- data-bs-toggle và data-bs-target dành cho bootstrap 5 -->
															<button type="button"
																class="profile-attribute-modify-button"
																data-bs-toggle="modal" data-bs-target="#dobModal">Thay
																đổi</button>
														</div>
													</td>
												</tr>
												<tr class="myprofile-table-row__row7">
													<td>
														<label></label>
													</td>
													<td>
														<button type="submit" class="profile-save-button">Lưu</button>
													</td>
												</tr>
											</tbody>
										</table>
									</form>
								</div>
								<div class="right-container-main-inner__bottom__right-cluster">
									<div class="right-container-main-inner__bottom__right-cluster__div">
										<div class="right-container-bottom-right-cluster-show-image__div" role="header">
											<div class="right-container-bottom-right-cluster-show-image__content">
												<svg enable-background="new 0 0 15 15" viewBox="0 0 15 15" x="0" y="0"
													class="right-container-bottom-right-cluster-show-image__content-svgicon">
													<g
														class="right-container-bottom-right-cluster-show-image__content-svg-group">
														<circle cx="7.5" cy="4.5" fill="none" r="3.8"
															stroke-miterlimit="10"></circle>
														<path d="m1.5 14.2c0-3.3 2.7-6 6-6s6 2.7 6 6" fill="none"
															stroke-linecap="round" stroke-miterlimit="10"></path>
													</g>
												</svg>
											</div>
										</div>
										<input type="file" class="profile-avatar-upload-file-input" id="avatarUpload" />
										<label for="avatarUpload" class="profile-avatar-choose-image-button">Chọn
											ảnh</label>
										<div class="right-container-bottom-right-cluster-note">
											<div class="right-container-bottom-right-cluster-note__content">Dung lượng
												file tối đa 1 MB</div>
											<div class="right-container-bottom-right-cluster-note__content">Định dạng:
												jpeg, jpg, png</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Phần modal đối với Bootstrap 5 -->
<!-- Modal cho Email -->
<div class="modal fade" id="emailModal" tabindex="-1">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="emailModalLabel">Thay đổi Email</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="emailChangeForm">
					<div class="form-group">
						<label for="email">Email</label>
						<input type="email" class="form-control" id="emailInput" name="email"
							value="{{ $user->email }}">
						<small class="text-danger" id="emailError" style="display:none;"></small>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
				<button type="button" class="btn btn-primary" id="emailSaveBtn">OK</button>
			</div>
		</div>
	</div>
</div>

<!-- Modal cho Số điện thoại -->
<div class="modal fade" id="phoneModal" tabindex="-1">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="phoneModalLabel">Thay đổi Số điện thoại</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="phoneChangeForm">
					<div class="form-group">
						<label for="phone">Số điện thoại</label>
						<input type="text" class="form-control" id="phoneInput" name="phone"
							value="{{ $user->phone_number }}">
						<small class="text-danger" id="phoneError" style="display:none;"></small>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
				<button type="button" class="btn btn-primary" id="phoneSaveBtn">OK</button>
			</div>
		</div>
	</div>
</div>

<!-- Modal cho Ngày sinh -->
<div class="modal fade" id="dobModal" tabindex="-1">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="dobModalLabel">Thay đổi Ngày sinh</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="dobChangeForm">
					<div class="form-group">
						<label for="date_of_birth">Ngày sinh</label>
						<input type="date" class="form-control" id="dobInput" name="date_of_birth"
							value="{{ date('Y-m-d', strtotime($user->date_of_birth)) }}">
						<small class="text-danger" id="dobError" style="display:none;"></small>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
				<button type="button" class="btn btn-primary" id="dobSaveBtn">OK</button>
			</div>
		</div>
	</div>
</div>
@endsection

@section('body-script')
@push('scripts')

	<!-- Bootstrap 5 -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

	<!--Import file profile.js từ thư mục public/js -->
	<script src="{{ asset('js/profile/homePage.js') }}" defer></script>
	<script src="{{ asset('js/profile/currentPasswordPage.js') }}" defer></script>
	<script src="{{ asset('js/profile/verifyNewPasswordPage.js') }}" defer></script>
	<script src="{{ asset('js/profile/orders.js') }}" defer></script>
@endpush