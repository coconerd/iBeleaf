// Lấy giá trị của các trường email, số điên thoại và ngày sinh trong thẻ div vào input ẩn để khi người dùng nhấn OK ở modal thì lấy giá trị từ input ẩn để gửi form
document.querySelector('.profile-save-button').addEventListener('click', function (event) {
    // Lấy giá trị từ các thẻ div và gán vào input ẩn
    document.getElementById('hidden-email').value = document.getElementById('profile-show-email').textContent.trim();
    document.getElementById('hidden-phone').value = document.getElementById('profile-show-phone').textContent.trim();
    document.getElementById('hidden-dob').value = document.getElementById('profile-show-dob').textContent.trim();
});

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
                    alert('Có lỗi xảy ra. Vui lòng thử lại.');
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
});

// Đảm bảo thanh cuộn trang hiển thị lại khi đóng modal
$(document).on('hidden.bs.modal', '.modal', function () {
    $('body').removeClass('modal-open');
    $('body').css({
        overflow: '',
        'padding-right': ''
    });
    $('.modal-backdrop').remove();
});

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