<form id="new-password-form" action="{{ route('profile.verifyNewPassword') }}" method="POST">
    @csrf
    <!-- Mật khẩu mới -->
    <div class="form-group">
        <label for="password">Mật khẩu mới</label>
        <div class="input-group">
            <input type="password" name="password" id="new_password" class="form-control" placeholder="Mật khẩu mới" required>
        </div>
        <small class="text-danger new-password-error"></small>
    </div>

    <!-- Nhập lại mật khẩu mới -->
    <div class="form-group">
        <label for="confirm_password">Nhập lại mật khẩu mới</label>
        <div class="input-group">
            <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Mật khẩu phải trùng khớp" required>
        </div>
        <small class="text-danger confirm-password-error"></small>
    </div>

    <button type="submit" id="new-password-submit-button" class="btn btn-primary" disabled>Lưu mật khẩu</button>
</form>