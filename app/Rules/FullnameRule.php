<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

/* 
    Illuminate\Contracts\Validation\Rule là một giao diện yêu cầu định nghĩa hai phương thức bắt buộc:
        passes: Kiểm tra giá trị có hợp lệ hay không
        message: Trả về thông báo lỗi khi giá trị không hợp lệ
 */

class FullnameRule implements Rule
{
    public function passes($attribute, $value)
    {
        // Kiểm tra fullname chỉ chứa chữ cái và khoảng trắng
        // Không chỉ toàn khoảng trắng
        // Sử dụng \p{L} để hỗ trợ tất cả các ký tự chữ trong Unicode (bao gồm tiếng Việt)
        return preg_match('/^(?=.*[\p{L}])[ \p{L}]+$/u', $value) && trim($value) !== '';
    }

    public function message()
    {
        return 'Họ tên không hợp lệ';
    }
}