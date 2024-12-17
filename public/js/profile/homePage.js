/* Do file index.blade.php thiết kế hiển thị email, số điện thoại, ngày sinh ở dạng thẻ div chứ không phải thẻ input, vì vậy khi gửi form (nhấn nút Lưu ở giao diện Hồ sơ) thì các thông tin email, số điện thoại, ngày sinh sẽ không được form gửi đến server ==> Dùng input ẩn để lấy giá trị của các trường này cho form có thể gửi câc giá trị từ thẻ div đến server */
// Lấy giá trị của các trường email, số điên thoại và ngày sinh từ thẻ div và gán vào input ẩn để khi người dùng nhấn OK ở modal thì lấy giá trị từ input ẩn để gửi form
document.querySelector('.profile-save-button').addEventListener('click', function (event) {
	document.getElementById('hidden-email').value = document.getElementById('profile-show-email').textContent.trim();
	document.getElementById('hidden-phone').value = document.getElementById('profile-show-phone').textContent.trim();
	document.getElementById('hidden-dob').value = document.getElementById('profile-show-dob').textContent.trim();
});
/*-----------------------------------------------------------------------------------------------------------------------------------------*/


/* Xử lý các modal khi nhấn vào "Thay đổi" ở các trường email, số điện thoại, ngày sinh tại giao diện Hồ sơ */
$(document).ready(function () {
	// Cấu hình global cho các yêu cầu AJAX để gửi CSRF token
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

	// Thực hiện yêu cầu AJAX khi người dùng nhấn OK ở modal
	function handleFieldChange(field) {
		$(`#${field}SaveBtn`).off('click').on('click', function () {
			const value = $(`#${field}Input`).val();

			// Disable button và hiển thị trạng thái đang xử lý
			$(`#${field}SaveBtn`).prop('disabled', true).text('Đang xử lý...');

			$.ajax({
				url: `/profile/validate/${field}`,
				method: 'POST',
				data: { [field]: value },
				success: function (response) {
					console.log('Response:', response);
					if (response.valid) {
						$(`#${field}Modal`).modal('hide');
						$(`#profile-show-${field}`).text(value);
						$(`#${field}Error`).hide();
					} else {
						$(`#${field}Error`).text(response.errors).show();
					}
				},
				error: function (xhr, status, error) {
					console.error('AJAX Error:', xhr.responseText);
					alert('Có lỗi xảy ra. Vui lòng thử lại');
				},
				complete: function () {
					$(`#${field}SaveBtn`).prop('disabled', false).text('OK');
				}
			});
		});
	}

	// Kích hoạt handleFieldChange khi modal được mở
	$('#emailModal').on('show.bs.modal', function () {
		handleFieldChange('email');
	});

	$('#phoneModal').on('show.bs.modal', function () {
		handleFieldChange('phone');
	});

	$('#dobModal').on('show.bs.modal', function () {
		handleFieldChange('dob');
	});

	$('.orders-link').on('click', function (e) {
		e.preventDefault();
		$.ajax({
			url: $(this).attr('href'),
			method: 'GET',
			success: function (response) {
				$('#right-container-main-inner__bottom').html(response.html);
				$('#right-container-main-inner__top-h1').text('Đơn mua hàng của bạn');
				$('#right-container-main-inner__top-div').text('Quản lý các đơn hàng của bạn');
				$('.profile-page-container').css('height', '900px');
				$('.right-container').css('height', '96vh');
				// Trigger an event to let the JavaScript code know that the orders tab has been loaded
				const event = new Event('ordersTabLoaded');
				document.dispatchEvent(event);
			},
			error: function () {
				alert('Không thể tải đơn mua của bạn.');
			}
		});
	});

	$('.returns').on('click', function (e) {
		e.preventDefault();
		$.ajax({
			url: $(this).attr('href'),
			method: 'GET',
			success: function (response) {
				$('#right-container-main-inner__bottom').html(response.html);
				$('#right-container-main-inner__top-h1').text('Yêu cầu đổi trả hàng');
				$('#right-container-main-inner__top-div').text('Quản lý các yêu cầu đổi trả hàng của bạn');
				$('.profile-page-container').css('height', '900px');
				$('.right-container').css('height', '96vh');
			},
			error: function () {
				alert('Không thể tải yêu cầu đổi trả hàng của bạn.');
			}
		});
	});
});
/*-----------------------------------------------------------------------------------------------------------------------------------------*/


/* Đảm bảo thanh cuộn trang hiển thị lại khi đóng modal */
$(document).on('hidden.bs.modal', '.modal', function () {
	$('body').removeClass('modal-open');
	$('body').css({
		overflow: '',
		'padding-right': ''
	});
	$('.modal-backdrop').remove();
});
/*-----------------------------------------------------------------------------------------------------------------------------------------*/


/* Xử lý hiệu ứng cho thông báo session */
document.addEventListener('DOMContentLoaded', function () {
	// Tìm thông báo session
	setTimeout(() => {
		const alerts = document.querySelectorAll('.custom-alert-container .alert');
		alerts.forEach(alert => {
			alert.style.opacity = '0'; // Làm mờ
			setTimeout(() => alert.remove(), 500); // Xóa phần tử
		});
	}, 3000); // Ẩn sau 3 giây
});
/*-----------------------------------------------------------------------------------------------------------------------------------------*/


/* Xử lý hiệu ứng cho thông báo validation*/
document.addEventListener('DOMContentLoaded', function () {
	// Tìm thông báo validation
	const validationAlert = document.getElementById('validationAlert');

	if (validationAlert) {
		setTimeout(() => {
			validationAlert.style.opacity = '0'; // Hiệu ứng mờ dần
			setTimeout(() => validationAlert.remove(), 500); // Xóa phần tử sau khi ẩn
		}, 5000); // Sau 5 giây
	}
});
/*-----------------------------------------------------------------------------------------------------------------------------------------*/


/* Điều khiển CSS khi người dùng nhấn ở dropdown Hồ sơ hoặc Đổi mật khẩu thì nội dung bố cục các thành phần html 
trong thẻ div có class là right-container-main-inner__bottom sẽ thay đổi tương ứng */

document.getElementById("stardust-dropdown__item-homepage").addEventListener("click", () => {
	// Đổi tên class để áp dụng CSS "flex-direction: row" tương ứng đã được định nghĩa trong file homePage.css
	//document.getElementById("right-container-main-inner__bottom").className = "right-container-main-inner__bottom row";
	const element = document.getElementById("right-container-main-inner__bottom");
	if (element.classList.contains("column")) {
		element.classList.remove("column");
	}
	if (!element.classList.contains("row")) {
		element.classList.add("row");
	}
});

document.getElementById("stardust-dropdown__item-changePassword").addEventListener("click", () => {
	// Đổi tên class để áp dụng CSS "flex-direction: column" tương ứng đã được định nghĩa trong file homePage.css
	//document.getElementById("right-container-main-inner__bottom").className = "right-container-main-inner__bottom column"; 

	const element = document.getElementById("right-container-main-inner__bottom");
	if (element.classList.contains("row")) {
		element.classList.remove("row");
	}
	if (!element.classList.contains("column")) {
		element.classList.add("column");
	}
});
/*-----------------------------------------------------------------------------------------------------------------------------------------*/


/* ---------------- Xử lý sự kiện khi nhấn vào dropdown "Đổi mật khẩu" ở giao diện trang hồ sơ ---------------- */
document.getElementById('stardust-dropdown__item-changePassword').addEventListener('click', function (event) {
	event.preventDefault();  // Ngừng hành động mặc định của liên kết (không chuyển hướng)

	const url = this.getAttribute('href'); // Lấy URL từ href của thẻ a

	// Gửi AJAX request để lấy nội dung form
	fetch(url, {
		method: 'GET',
		headers: {
			'X-Requested-With': 'XMLHttpRequest'  // Xác nhận đây l�� yêu cầu AJAX
		}
	})
		.then(response => response.json())
		.then(data => {
			if (data.html) {
				// Thay thế nội dung của <div class="right-container-main-inner_bottom">
				document.querySelector('.right-container-main-inner__bottom').innerHTML = data.html;

				//Thay đổi tiêu đề của thẻ h1 và thẻ div trong <div class="right-container-main-inner_top">
				document.getElementById("right-container-main-inner__top-h1").textContent = "Xác minh tài khoản";
				document.getElementById("right-container-main-inner__top-div").textContent = "Hãy xác minh thông tin để tăng cường tính bảo mật cho tài khoản của bạn";

				// Sau khi thay thế nội dung, nạp lại script xử lý trong currentPasswordPage.js
				loadCurrentPasswordForm();
			}
		})
		.catch(error => console.error('Lỗi:', error));

});

/*-----------------------------------------------------------------------------------------------------------------------------------------*/
function loadCurrentPasswordForm() {
	// Mở và đóng icon con mắt khi click vào input password
	// document.getElementById('toggle-current-password').addEventListener('click', function () {
	// 	const passwordField = document.getElementById('current-password-field');
	// 	const eyeClosed = document.getElementById('eye-closed');
	// 	const eyeOpen = document.getElementById('eye-open');

	// 	if (passwordField.type === 'password') {
	// 		passwordField.type = 'text';
	// 		eyeClosed.style.display = 'none';
	// 		eyeOpen.style.display = 'inline';
	// 	} else {
	// 		passwordField.type = 'password';
	// 		eyeClosed.style.display = 'inline';
	// 		eyeOpen.style.display = 'none';
	// 	}
	// });

	// Nếu input password không rỗng thì enable button
	document.getElementById('current-password-field').addEventListener('input', function () {
		const password = document.getElementById('current-password-field').value;
		const submitButton = document.getElementById('current-password-submit-button');

		if (password.trim() !== '') { //nếu chỉ nhập vào input này khoảng trắng thì không enable button
			submitButton.disabled = false;
		} else {
			submitButton.disabled = true;
		}
	});

	// Ẩn thông báo lỗi khi người dùng bắt đầu nhập vào input
	document.getElementById("current-password-field").addEventListener("input", function () {
		document.querySelector(".current-password-error").textContent = ""; // Xóa lỗi
	});
}
/*-----------------------------------------------------------------------------------------------------------------------------------------*/