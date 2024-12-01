<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

/* 
    Illuminate\Contracts\Validation\Rule là một giao diện yêu cầu định nghĩa hai phương thức bắt buộc:
        passes: Kiểm tra giá trị có hợp lệ hay không
        message: Trả về thông báo lỗi khi giá trị không hợp lệ
 */

 class UsernameRule implements Rule
 {
     private $currentUser;
 
     // Nhận $currentUser (toàn bộ thông tin người dùng hiện tại)
     public function __construct($currentUser)
     {
         $this->currentUser = $currentUser;
     }
 
     public function passes($attribute, $value)
     {
         // Kiểm tra username chỉ chứa chữ cái, số, khoảng trắng, không chỉ toàn khoảng trắng
         // Sử dụng \p{L} để hỗ trợ tất cả các ký tự chữ trong Unicode (bao gồm tiếng Việt)
         $isValidFormat = preg_match('/^(?=.*[\p{L}])[ \p{L}0-9]+$/u', $value) && trim($value) !== '';
 
         // Kiểm tra tính duy nhất trong cơ sở dữ liệu
         $isUnique = !DB::table('users')
             ->where('user_name', $value) // Kiểm tra username trùng
             ->where('user_id', '!=', $this->currentUser->user_id) // Loại trừ user hiện tại
             ->exists();
 
         return $isValidFormat && $isUnique;
     }
 
     public function message()
     {
         return 'Username không hợp lệ hoặc đã được sử dụng';
     }
 }