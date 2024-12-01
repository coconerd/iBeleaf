<!-- File này chỉ lưu trữ code tạm trong quá trình code -->
@section("content")
<div class="profile-page-container-outer">
    <div class="profile-page-container">
        <div class="profile-page-main">
            <div class="left-container">
                <div class="left-container__top-cluster">
                    <a class="top-left-cluster" href="/profile">
                        <div class="avatar">
                            <div class="avatar__placeholder">
                                <svg enable-background="new 0 0 15 15" viewBox="0 0 15 15" x="0" y="0" class="svg-icon-headshot">
                                    <g class="group-svg">
                                        <circle cx="7.5" cy="4.5" fill="none" r="3.8" stroke-miterlimit="10"></circle>
                                        <path d="m1.5 14.2c0-3.3 2.7-6 6-6s6 2.7 6 6" fill="none" stroke-linecap="round" stroke-miterlimit="10"></path>
                                    </g>
                                </svg>
                            </div>
                        </div>
                    </a>

                    <div class="top-right-cluster">
                        <div class="top-right-cluster__username">Công Phan</div>
                        <div style="color: rgba(0,0,0,0.8); display: block; font-size: 14px; height: 16.8px; width: 115px; unicode-bidi: isolate; line-height: 16.8px; text-size-adjust: 100%">
                            <a class="pen-icon-modifiy-profile" href="/profile">
                                <svg width="12" height="12" viewBox="0 0 12 12" xmlns="http://www.w3.org/2000/svg" style="margin-right: 4px;">
                                    <path d="M8.54 0L6.987 1.56l3.46 3.48L12 3.48M0 8.52l.073 3.428L3.46 12l6.21-6.18-3.46-3.48" fill="#9B9B9B" fill-rule="evenodd"></path>                              
                                </svg>
                                Sửa hồ sơ
                            </a>
                        </div>
                    </div>
                </div>

                <div class="left-container__bottom-cluster">
                    <div class="stardust-dropdown">
                        <div class="stardust-dropdown__item-header">
                            <a class="stardust-dropdown__item" href="/profile">
                                <div class="stardust-dropdown__item-icon">
                                    <img src="{{asset('images/icons8-registration-50.png')}}" class="stardust-dropdown__item-icon-img">
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
                            <a class="stardust-dropdown__item" href="/user/purchase">
                                <div class="stardust-dropdown__item-icon">
                                    <img src="{{asset('images/icons8-access-50.png')}}" class="stardust-dropdown__item-icon-img">
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
                            <a class="stardust-dropdown__item" href="/user/purchase">
                                <div class="stardust-dropdown__item-icon">
                                    <img src="{{asset('images/icons8-order-50.png')}}" class="stardust-dropdown__item-icon-img">
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
                            <a class="stardust-dropdown__item" href="/user/purchase">
                                <div class="stardust-dropdown__item-icon">
                                    <img src="{{asset('images/icons8-return-purchase-50.png')}}" class="stardust-dropdown__item-icon-img">
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

                </div>
            </div>

            <div class="right-container">
                <div class="right-container-main" role="main">
                    <div style="color: rgba(0, 0, 0, 0.8); display: contents; font-size: 14px; height: auto; line-height: 16.8px; text-size-adjust: 100%; unicode-bidi: isolate; width: auto;">
                        <div class="right-container-main-inner">
                            <div class="right-container-main-inner__top">
                                <h1 style="color: rgb(51, 51, 51); display: block; font-size: 20px; font-weight: 500; height: 24px; line-height: 24px; margin-block-end: 0px; margin-block-start: 0px; margin-bottom: 0px; margin-inline-end: 0px; margin-inline-start: 0px; margin-left: 0px; margin-right: 0px; margin-top: 0px; text-size-adjust: 100%; text-transform: capitalize; unicode-bidi: isolate; width: 757px;">Hồ sơ của tôi</h1>
                                <div style="color: rgb(85, 85, 85); display: block; font-size: 14px; height: 17px; line-height: 17px; margin-top: 3px; text-size-adjust: 100%; unicode-bidi: isolate; width: 757px;">Quản lý thông tin hồ sơ để bảo mật tài khoản</div>
                            </div>
                            <div class="right-container-main-inner__bottom">
                                <div class="right-container-main-inner__bottom__left-cluster">
                                    <form style="color: rgba(0, 0, 0, 0.8); display: block; font-size: 14px; line-height: 16.8px; margin-top: 0px; text-size-adjust: 100%; unicode-bidi: isolate; width: 502px; height: 457px;">
                                        <table class="myprofile-table">
                                            <tbody>
                                                <tr class="myprofile-table-row" id="myprofile-table-row1">
                                                    <td class="myprofile-table-row-td1" id="myprofile-table-row1td1">
                                                        <label class="myprofile-table-row-td1__label">Tên đăng nhập</label>
                                                    </td>
                                                    <td class="myprofile-table-row-td2" id="myprofile-table-row1td2">
                                                        <div class="myprofile-table-row-td2__div" style="height: 64px;">
                                                            <div class="myprofile-table-row-td2__div-content">
                                                                <input type="text" placeholder="" class="profile-username-input" value="Công Phan" data-listener-added_f77ca63a="true">
                                                            </div>
                                                            <div class="myprofile-table-row-td2__div-note">Tên đăng nhập chỉ có thể thay đổi một lần</div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr class="myprofile-table-row" id="myprofile-table-row2">
                                                    <td class="myprofile-table-row-td1" id="myprofile-table-row2td1">
                                                        <label class="myprofile-table-row-td1__label">Tên</label>
                                                    </td>
                                                    <td class="myprofile-table-row-td2" id="myprofile-table-row2td2">
                                                        <div class="myprofile-table-row-td2__div" style="height: 40px;">
                                                            <div class="myprofile-table-row-td2__div-content">
                                                                <input type="text" placeholder="" class="profile-name-input" value="Phan Thành Công">
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
                                                            <div class="profile-email-show" style="width: 300px;">ph*************@gmail.com</div>
                                                            <button class="profile-attribute-modify-button">Thay đổi</button>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr class="myprofile-table-row" id="myprofile-table-row4">
                                                    <td class="myprofile-table-row-td1" id="myprofile-table-row4td1">
                                                        <label class="myprofile-table-row-td1__label">Số điện thoại</label>
                                                    </td>
                                                    <td class="myprofile-table-row-td2" id="myprofile-table-row4td2">
                                                        <div class="myprofile-table-row-td2__div-modificable">
                                                            <div class="profile-phonenumber-show" style="width: 300px;">*********75</div>
                                                            <button class="profile-attribute-modify-button">Thay đổi</button>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr class="myprofile-table-row" id="myprofile-table-row5">
                                                    <td class="myprofile-table-row-td1" id="myprofile-table-row5td1">
                                                        <label class="myprofile-table-row-td1__label">Giới tính</label>
                                                    </td>
                                                    <td class="myprofile-table-row-td2" id="myprofile-table-row5td2">
                                                        <div class="myprofile-table-row-td2__div__gender-ratio" style="height: 18 px;">
                                                            <label class="profile-gender-radio__label">
                                                                <input type="radio" name="gender" value="Nam" class="profile-gender-radio__input"> Nam
                                                            </label>
                                                            <label class="profile-gender-radio__label">
                                                                <input type="radio" name="gender" value="Nữ" class="profile-gender-radio__input"> Nữ
                                                            </label>
                                                            <label class="profile-gender-radio__label">
                                                                <input type="radio" name="gender" value="Khác" class="profile-gender-radio__input"> Khác
                                                            </label>
                                                            <!-- <label class="profile-gender-radio__label">
                                                                <input type="radio" name="gender" value="Nam" class="profile-gender-radio__input"
                                                                    {{ old('gender', auth()->check() ? auth()->user()->gender : '') == 'Nam' ? 'checked' : '' }}> Nam
                                                            </label>
                                                            <label class="profile-gender-radio__label">
                                                                <input type="radio" name="gender" value="Nữ" class="profile-gender-radio__input"
                                                                    {{ old('gender', auth()->check() ? auth()->user()->gender : '') == 'Nữ' ? 'checked' : '' }}> Nữ
                                                            </label>
                                                            <label class="profile-gender-radio__label">
                                                                <input type="radio" name="gender" value="Khác" class="profile-gender-radio__input"
                                                                    {{ old('gender', auth()->check() ? auth()->user()->gender : '') == 'Khác' ? 'checked' : '' }}> Khác
                                                            </label> -->
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr class="myprofile-table-row" id="myprofile-table-row6">
                                                    <td class="myprofile-table-row-td1" id="myprofile-table-row6td1">
                                                        <label class="myprofile-table-row-td1__label">Ngày sinh</label>
                                                    </td>
                                                    <td class="myprofile-table-row-td2" id="myprofile-table-row6td2">
                                                        <div class="myprofile-table-row-td2__div-modificable" style="display: flex; height: 17.6px; align-items: center;">
                                                            <div class="profile-birthday-show" style="width: 300px;">**/08/20**</div>
                                                            <button class="profile-attribute-modify-button">Thay đổi</button>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr class="myprofile-table-row" id="myprofile-table-row7">
                                                    <td class="myprofile-table-row-td1" id="myprofile-table-row7td1">
                                                        <label class="myprofile-table-row-td1__label"></label>
                                                    </td>
                                                    <td class="myprofile-table-row-td2" id="myprofile-table-row7td2">
                                                        <button type="button" class="profile-save-button" aria-disabled="false">Lưu</button>
                                                    </td>
                                                </tr>
                                                <tr class="myprofile-table-row__row7">
                                                    <td>
                                                        <label></label>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="profile-save-button">Lưu</button>
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
                                                <svg enable-background="new 0 0 15 15" viewBox="0 0 15 15" x="0" y="0" class="right-container-bottom-right-cluster-show-image__content-svgicon">
                                                    <g class="right-container-bottom-right-cluster-show-image__content-svg-group">
                                                        <circle cx="7.5" cy="4.5" fill="none" r="3.8" stroke-miterlimit="10"></circle>
                                                        <path d="m1.5 14.2c0-3.3 2.7-6 6-6s6 2.7 6 6" fill="none" stroke-linecap="round" stroke-miterlimit="10"></path>
                                                    </g>
                                                </svg>
                                            </div>
                                        </div>
                                        <input type="file" class="profile-avatar-upload-file-input" id="avatarUpload"/>
                                        <label for="avatarUpload" class="profile-avatar-choose-image-button">Chọn ảnh</label>
                                        <div class="right-container-bottom-right-cluster-note">
                                            <div class="right-container-bottom-right-cluster-note__content">Dung lượng file tối đa 1 MB</div>
                                            <div class="right-container-bottom-right-cluster-note__content">Định dạng: jpeg, jpg, png</div>
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
@endsection


<!-- Bootstrap 4 CSS -->
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css"> -->
 <!-- Bootstrap 4 -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js"></script> -->
@section("content")
<!-- Phần modal đối với Bootstrap 4 -->
 <!-- Modal cho Email -->
<div class="modal fade" id="emailModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thay đổi Email</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="emailChangeForm">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="emailInput" name="email" value="{{ $user->email }}">
                        <small class="text-danger" id="emailError" style="display:none;"></small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary" id="emailSaveBtn">OK</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal cho Số điện thoại -->
<div class="modal fade" id="phoneModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thay đổi Số điện thoại</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="phoneChangeForm">
                    <div class="form-group">
                        <label for="phone">Số điện thoại</label>
                        <input type="text" class="form-control" id="phoneInput" name="phone" value="{{ $user->phone_number }}">
                        <small class="text-danger" id="phoneError" style="display:none;"></small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary" id="phoneSaveBtn">OK</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal cho Ngày sinh -->
<div class="modal fade" id="dobModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thay đổi Ngày sinh</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="dobChangeForm">
                    <div class="form-group">
                        <label for="date_of_birth">Ngày sinh</label>
                        <input type="date" class="form-control" id="dobInput" name="date_of_birth" value="{{ date('Y-m-d', strtotime($user->date_of_birth)) }}">
                        <small class="text-danger" id="dobError" style="display:none;"></small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary" id="dobSaveBtn">OK</button>
            </div>
        </div>
    </div>
</div>

<!-- data-toggle và data-target dành cho bootstrap 4 -->
<!-- <button type="button" class="profile-attribute-modify-button" data-toggle="modal" data-target="#emailModal">Thay đổi</button> -->
<!-- data-toggle và data-target dành cho bootstrap 4 -->
<!-- <button type="button" class="profile-attribute-modify-button" data-toggle="modal" data-target="#phoneModal">Thay đổi</button> -->
<!-- data-toggle và data-target dành cho bootstrap 4 -->
<!-- <button type="button" class="profile-attribute-modify-button" data-toggle="modal" data-target="#dobModal">Thay đổi</button> -->
@endsection

<!-- Đoạn script xử lí trong trang hồ sơ -->
 <script>
$(document).ready(function() {
    // Cấu hình global cho các yêu cầu AJAX để gửi CSRF token
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Thực hiện yêu cầu AJAX khi người dùng nhấn OK ở modal
    function handleFieldChange(field) {
        $(`#${field}SaveBtn`).off('click').on('click', function() {
            const value = $(`#${field}Input`).val();

            // Disable button và hiển thị trạng thái đang xử lý
            $(`#${field}SaveBtn`).prop('disabled', true).text('Đang xử lý...');

            $.ajax({
                url: `/profile/validate/${field}`,
                method: 'POST',
                data: { [field]: value         
                        // _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')  // Do đã thêm CSRF vào Thêm CSRF token vào data 
                    },
                success: function(response) {
                    console.log('Response:', response);
                    if (response.valid) {
                        $(`#${field}Modal`).modal('hide');
                        $(`#profile-show-${field}`).text(value);
                        $(`#${field}Error`).hide();
                    } else {
                        // Hiển thị lỗi
                        $(`#${field}Error`).text(response.errors).show();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', xhr.responseText);
                    alert('Có lỗi xảy ra. Vui lòng thử lại.');
                },
                complete: function() {
                    $(`#${field}SaveBtn`).prop('disabled', false).text('OK');
                }
            });
        });
    }

    // Chỉ kích hoạt handleFieldChange khi modal được mở (Modal được mở khi nhấn "Thay đổi" ở các truong thông tin: email, số điện thoại, ngày sinh)
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

$(document).on('hidden.bs.modal', '.modal', function () {
    // Xóa trạng thái trên body
    $('body').removeClass('modal-open');
    $('body').css({
        overflow: '', // Khôi phục overflow. Nếu không sẽ bị mất thanh cuộn trang của trang web
        'padding-right': '' // padding-right được bootstrap tự động thêm vào giao diện toàn trang web khi thoát modal để hiển thị thanh cuộn trang. Vì vậy cần xoá padding-right này đi để không bị lệch toàn bộ trang web
    });
    // Loại bỏ backdrop
    $('.modal-backdrop').remove();
});

//Script tự động ẩn thông báo sau 4 giây
setTimeout(() => {
    const alert = document.querySelector('.alert');
    if (alert) {
        alert.style.display = 'none';
    }
}, 4000);
</script>