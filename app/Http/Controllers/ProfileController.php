<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rules\UsernameRule;
use App\Rules\FullnameRule;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Log;
use App\Providers\DBConnService; // Import DBConnService của ducminh đã viết sẵn để khởi tạo đối tượng connection đến csdl

class ProfileController extends Controller
{
    protected DBConnService $dbConnService;

	public function __construct(DBConnService $dbConnService)
	{
		$this->dbConnService = $dbConnService;
	}

    public function showProfilePage()
    {
        // return view('profile.index'); /* Dùng này thì trong file index dùng trực tiếp auth()->user() thay cho biến $user */  ==> cách này không khuyến khích vì vi phạm nguyên tắc MVC
        /* auth()->user() thì luôn khả dụng, không cần import          ==> khuyến khích sử dụng
           Auth::user() thì cần import Illuminate\Support\Facades\Auth;   ==> dễ bị lỗi các trường thông tin user bị null mặc dù user đã đăng nhập rồi
        */
        $user = auth()->user();
        return view('profile.index', compact('user'));
    }

    public function validateField(Request $request, $field)
    {
        try {
            switch($field) {
                case 'email':
                    $validator = Validator::make($request->all(), [
                        'email' => [
                            'required', 
                            'email', 
                            'regex:/^[\w\.-]+@(gmail\.com|gm\.uit\.edu\.vn|uit\.edu\.vn)$/'
                        ]
                    ], [
                        'email.required' => 'Vui lòng nhập email',
                        'email.email' => 'Địa chỉ email không hợp lệ',
                        'email.regex' => 'Email phải thuộc trong những định dạng tên miền là gmail.com, gm.uit.edu.vn, uit.edu.vn'
                    ]);
                    break;
                
                case 'phone':
                    $validator = Validator::make($request->all(), [
                        'phone' => [
                            'required', 
                            'regex:/^[0-9]{10}$/'
                        ]
                    ], [
                        'phone.required' => 'Vui lòng nhập số điện thoại',
                        'phone.regex' => 'Số điện thoại phải có 10 chữ số'
                    ]);
                    break;
                
                case 'dob':
                    $validator = Validator::make($request->all(), [
                        'dob' => [
                            'required',
                            'date_format:Y-m-d',
                            'before_or_equal:today'
                        ]
                    ], [
                        'dob.required' => 'Vui lòng nhập ngày sinh',
                        'dob.date_format' => 'Định dạng ngày sinh không hợp lệ',
                        'dob.before_or_equal' => 'Ngày sinh không được lớn hơn ngày hiện tại'
                    ]);
                    break;
                
                default:
                    return response()->json([
                        'valid' => false, 
                        'errors' => 'Trường không hợp lệ'
                    ]);
            }
    
            // Kiểm tra validation
            if ($validator->fails()) {
                return response()->json([
                    'valid' => false,
                    'errors' => $validator->errors()->first($field)
                ]);
            }
    
            // Nếu validate thành công, trả về giá trị để hiển thị trên giao diện
            return response()->json([
                'valid' => true,
                'value' => $request->input($field)
            ]);
    
        } catch (Exception $e) {
            // // Ghi log lỗi
            // Log::error('Lỗi validate field: ' . $e->getMessage());
            dd($e);
            return response()->json([
                'valid' => false,
                'errors' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateProfile(Request $request)
    {
        // dd($request->all()); // in ra dữ liệu gửi đến server khi nhấn nút Lưu từ form

        $fieldMapping = [
            'username' => 'user_name',
            'fullname' => 'full_name',
            'email' => 'email',
            'phone' => 'phone_number',
            'dob' => 'date_of_birth',
            'gender' => 'gender',
        ];

        $currentUser = auth()->user();

        /* Sử dụng hàm $request->validate thì khi validate phát hiện có lỗi sẽ tự động điều hướng về trang trước đó. 
        Nếu validate hợp lệ thì trả về kết quả đã được validate */
        $validatedData = $request->validate([
            'username' => ['required', new UsernameRule($currentUser)],
            'fullname' => ['required', new FullnameRule()],
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($currentUser->user_id, 'user_id'),
                'regex:/^[\w\.-]+@(gmail\.com|gm\.uit\.edu\.vn|uit\.edu\.vn)$/'
            ],
            'phone' => [
                'required',
                'regex:/^[0-9]{10}$/'
            ],
            'gender' => [
                'required',
                 Rule::in(['Nam', 'Nữ', 'Khác'])
            ],
            'dob' => [
                'required',
                'date',
                'before_or_equal:today'
            ]
        ], [
            'username.required' => 'Vui lòng nhập username',
            'fullname.required' => 'Vui lòng nhập họ tên',
            'email.regex' => 'Email không hợp lệ',
            'phone.regex' => 'Số điện thoại phải có 10 chữ số',
            'dob.before_or_equal' => 'Ngày sinh không được lớn hơn ngày hiện tại',
            'gender.required' => 'Vui lòng chọn giới tính'
        ]);

        // dd($validatedData); // in ra dữ liệu đã được validate thành công

        $hasChanged = false;
        foreach ($validatedData as $field => $value) {
            $dbField = $fieldMapping[$field] ?? null;
            if ($dbField) {
                $currentValue = $currentUser->$dbField;

                // Xử lý định dạng cho các trường đặc biệt
                if ($field === 'dob') {
                    $currentValue = $currentValue->format('Y-m-d'); // Đồng nhất định dạng dob trong database và cách hiển thị dob trên giao diện
                }

                if ((string) $currentValue !== (string) $value) {
                    $hasChanged = true;
                    break;
                }
            }
        }

        if (!$hasChanged) {
            return redirect()->route('profile.homepage')->with('info', 'Không có thay đổi nào để cập nhật');
        }

        $updateData = [];
        foreach ($validatedData as $field => $value) {
            $dbField = $fieldMapping[$field] ?? null;
            if ($dbField) {
                $updateData[$dbField] = $value;
            }
        }

        $mysqli = $this->dbConnService->getDBConn(); // trong đối tượng connection được trả về bởi hàm getDBConn() đã được khởi tạo sẵn các kết nối và có kiểm tra kết nối có thất bại hay không luôn rồi

        // if ($mysqli->connect_error) {
        //     die('Kết nối thất bại: ' . $mysqli->connect_error);
        // }

        $setClause = implode(', ', array_map(fn($key) => "$key = ?", array_keys($updateData)));
        $stmt = $mysqli->prepare("UPDATE users SET $setClause WHERE user_id = ?");

        $params = array_merge(array_values($updateData), [$currentUser->user_id]);
        $stmt->bind_param(str_repeat('s', count($updateData)) . 'i', ...$params);


        Log::info('Set clause: ', ['setClause' => $setClause]);
        Log::info('Câu lệnh SQL: ', ['sql' => "UPDATE users SET $setClause WHERE user_id = ?"]);
        Log::info('Giá trị truyền vào các dấu ? của preparedstatement SQL: ', $params);

        if ($stmt->execute()) {
            $stmt->close();
            $mysqli->close();
            return redirect()->route('profile.homepage')->with('success', 'Cập nhật thông tin thành công');
        } else {
            $stmt->close();
            $mysqli->close();
            return redirect()->route('profile.homepage')->with('error', 'Có lỗi xảy ra. Vui lòng thử lại');
        }
    }
}
