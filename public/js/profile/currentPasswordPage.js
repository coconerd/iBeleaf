/* ---------------------------------Xử lý sự kiện nhấn nút XÁC NHẬN ở form currentPasswordInput.blade.php----------------------------------------------------- */
document.addEventListener('DOMContentLoaded', function() {
    // Tạo MutationObserver để theo dõi sự thay đổi trong DOM
    const observer = new MutationObserver(function(mutationsList) {
        for(const mutation of mutationsList) {
            if (mutation.type === 'childList' || mutation.type === 'subtree') {
                const submitButton = document.getElementById('current-password-submit-button');
                // Chỉ gắn sự kiện nếu submitButton tồn tại và chưa được gắn sự kiện
                if (submitButton && !submitButton.dataset.eventAdded) {
                    submitButton.dataset.eventAdded = true; // Đánh dấu sự kiện đã được thêm
                    if (submitButton) {
                        submitButton.addEventListener('click', function(event) {
                            console.log('Button clicked!');
                            event.preventDefault();

                            // Disable button và đổi text
                            submitButton.disabled = true;
                            const originalText = submitButton.textContent; // Lưu text ban đầu
                            submitButton.textContent = 'Đang xác thực...';

                            fetch('/profile/currentPassword-verify', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'X-Requested-With': 'XMLHttpRequest'
                                },
                                body: JSON.stringify({ 
                                    password: document.getElementById('current-password-field').value 
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                console.log('Server Response:', data);
                                if (data.success) {
                                    console.log("Password verified successfully");

                                    const container = document.querySelector('#right-container-main-inner__bottom');
                                    if (container) {
                                        container.innerHTML = data.html;
                                    } else {
                                        console.error("Element '.right-container-main-inner__bottom' không tồn tại.");
                                    }
                                    document.getElementById("right-container-main-inner__top-h1").textContent = "Đổi mật khẩu";
                                    document.getElementById("right-container-main-inner__top-div").textContent = "Thay đổi mật khẩu hiện tại để đảm bảo sự an toàn cho tài khoản của bạn";
                                    loadNewPasswordForm();
                                } else {
                                    console.log("Password verification failed");
                                    if(!data.html){
                                        if (data.message) {
                                            document.querySelector('.current-password-error').textContent = data.message;
                                        }
                                    } else{
                                        if (data.message) {
                                            document.querySelector('.current-password-error').textContent = data.message;
                                        }                                   
                                    }
                                    // const container = document.querySelector('#right-container-main-inner__bottom');
                                    // if (container) {
                                    //     container.innerHTML = data.html;
                                    // } else {
                                    //     console.error("Element '.right-container-main-inner__bottom' không tồn tại.");
                                    // }
                                    // document.getElementById("right-container-main-inner__top-h1").textContent = "Xác minh tài khoản";
                                    // document.getElementById("right-container-main-inner__top-div").textContent = "Hãy xác minh thông tin để tăng cường tính bảo mật cho tài khoản của bạn";
                                    // if (data.message) {
                                    //     document.querySelector('.current-password-error').textContent = data.message;
                                    // }
                                    // loadCurrentPasswordFormScripts();
                                }
                            })
                            .catch(error => {
                                console.error('Lỗi:', error);
                                alert('Đã xảy ra lỗi, vui lòng thử lại sau.');
                            })
                            .finally(() => {
                                // Enable button và khôi phục text ban đầu
                                submitButton.disabled = false;
                                submitButton.textContent = originalText;
                            });
                        });
                    } else {
                        console.error('Button không tồn tại');
                    }
                }
            }
        }
    });

    // Cấu hình observer để theo dõi sự thay đổi trong DOM
    const config = { childList: true, subtree: true };

    // Bắt đầu theo dõi sự thay đổi trong phần tử có id là 'right-container-main-inner__bottom'
    const targetNode = document.querySelector('#right-container-main-inner__bottom');
    if (targetNode) {
        observer.observe(targetNode, config);
    }
});
/* -------------------------------------------------------------------------------------------------------------------------------------------------------------- */

/* Hàm thêm các sự kiện cho form verifyNewPasswordInput.blade.php khi được load sau khi nhấn nút XÁC NHẬN ở form currentPasswordInput.blade.php */
function loadNewPasswordForm() 
{
    // Toggle mật khẩu mới
    const newPasswordInput = document.getElementById("new_password");

    // Toggle nhập lại mật khẩu
    const confirmPasswordInput = document.getElementById("confirm_password");

    // Ẩn thông báo lỗi khi người dùng bắt đầu nhập
    const newPasswordError = document.querySelector(".new-password-error");
    const confirmPasswordError = document.querySelector(".confirm-password-error");
    newPasswordInput.addEventListener("input", function () {
        newPasswordError.textContent = ""; // Xóa lỗi
    });
    confirmPasswordInput.addEventListener("input", function () {
        confirmPasswordError.textContent = ""; // Xóa lỗi
    });

    // Xử lý sự kiện để kích hoạt/vô hiệu hóa nút submit của form nhập mật khẩu mới
    document.getElementById('new-password-form').addEventListener('input', function() {
        var newPassword = document.getElementById('new_password').value;
        var confirmPassword = document.getElementById('confirm_password').value;
        var submitButton = document.getElementById('new-password-submit-button');
        
        // Kiểm tra nếu cả hai trường mật khẩu có giá trị và không phải là chuỗi toàn kí tự khoảng trắng
        if (newPassword.trim() !== '' && confirmPassword.trim() !== '') {
            submitButton.disabled = false; // Kích hoạt nút submit
        } else {
            submitButton.disabled = true; // Vô hiệu hóa nút submit
        }
    });
}
/* -------------------------------------------------------------------------------------------------------------------------------------------------------------- */