document.addEventListener("DOMContentLoaded", function () {
    // Tạo MutationObserver để theo dõi sự thay đổi trong DOM
    const observer = new MutationObserver(function(mutationsList) {
        for(const mutation of mutationsList) {
            if (mutation.type === 'childList' || mutation.type === 'subtree') {
                const saveNewPassWordButton = document.getElementById('new-password-submit-button');
                // Chỉ gắn sự kiện nếu saveNewPassWordButton tồn tại và chưa được gắn sự kiện
                if (saveNewPassWordButton && !saveNewPassWordButton.dataset.eventAdded) {
                    saveNewPassWordButton.dataset.eventAdded = true; // Đánh dấu sự kiện đã được thêm
                    if(saveNewPassWordButton){
                        saveNewPassWordButton.addEventListener('click', function(e) { 
                            console.log('new-password-submit-button Button clicked!');
                            e.preventDefault();
                    
                            // Disable button và đổi text
                            saveNewPassWordButton.disabled = true;
                            const originalText = saveNewPassWordButton.textContent; // Lưu text ban đầu
                            saveNewPassWordButton.textContent = 'Đang xử lý...';
                    
                            const password = document.getElementById("new_password").value;
                            const confirmPassword = document.getElementById("confirm_password").value;
                    
                            if (password !== confirmPassword) {
                                document.querySelector(".confirm-password-error").textContent = "Mật khẩu nhập lại không khớp. Vui lòng kiểm tra lại";
                                // Enable button và khôi phục text ban đầu
                                saveNewPassWordButton.disabled = false;
                                saveNewPassWordButton.textContent = originalText;
                
                            } else {
                                document.querySelector(".confirm-password-error").textContent = "";
                
                                fetch('/profile/verify-newpassword', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                        'X-Requested-With': 'XMLHttpRequest'
                                    },
                                    body: JSON.stringify({ 
                                        password: password,
                                        password_confirmation: confirmPassword
                                    })
                                })
                                .then(response => response.json())
                                .then(data => {
                                    console.log('Server Response:', data);
                                    if (data.success) {
                                        // console.log("Save new password successfully")
                                        // if(data.message){
                                        //     console.log("Mật khẩu không có thay đổi nào để cập nhật");
                                        // } else {
                                        //     console.log("Save new password successfully");
                                        // }
                                        console.log(data.message);
                                        if (data.redirect_url) {
                                            window.location.href = data.redirect_url;
                                        }
                                        
                                    } else {
                                        console.log("New password value invalid");
                                        if (data.message) {
                                            document.querySelector(".new-password-error").textContent = data.message;
                                        }
                                    }
                                })
                                .catch(error => {
                                    console.error('Lỗi:', error);
                                    alert('Đã xảy ra lỗi, vui lòng thử lại sau');
                                })
                                .finally(() => {
                                    // Enable button và khôi phục text ban đầu
                                    saveNewPassWordButton.disabled = false;
                                    saveNewPassWordButton.textContent = originalText;
                                });
                            }
                        });
                    } else{
                        console.error('new-password-submit-button không tồn tại');
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
