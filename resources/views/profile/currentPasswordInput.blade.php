<form id="current-password-form" action="{{ route('profile.verifyCurrentPassword') }}" method="POST">
    @csrf
    <div class="current-password-container">
        <div class="current-password-input-wrapper">
            <div class="current-password-input-label">
                <label for="current-password-field">Nhập mật khẩu hiện tại của bạn</label>
            </div>
            <div class="current-password-input-field">
                <input id="current-password-field" class="current-password-input" type="password" placeholder="Enter password" name="password" value="{{ old('password') }}" required>
                <!-- @error('password')
                    <small class="text-danger">{{ $message }}</small>
                @enderror -->
                <!-- @if (isset($errors) && $errors->has('password'))
                    <small class="text-danger">{{ $errors->first('password') }}</small>
                @endif -->
            </div>
            <small class="text-danger current-password-error"></small>
        </div>
        <button class="current-password-submit-button" id="current-password-submit-button" disabled type="submit">XÁC NHẬN</button>
    </div>
</form>

<div class="faq-section">
    <div class="faq-item">
        <p class="faq-question">Câu hỏi: Tại sao cần nhập mật khẩu?</p>
        <p class="faq-answer">Trả lời: Để xác minh bạn là chủ sở hữu tài khoản trước khi thực hiện thay đổi mật khẩu.</p>
    </div>
    <div class="faq-item">
        <p class="faq-question">Câu hỏi: Tôi nên làm gì nếu quên mật khẩu?</p>
        <p class="faq-answer">Trả lời: Vui lòng liên hệ Bộ phận CSKH của COCONERD để được hỗ trợ khôi phục quyền truy cập.</p>
    </div>
</div>