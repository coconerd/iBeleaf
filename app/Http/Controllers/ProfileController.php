<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rules\UsernameRule;
use App\Rules\FullnameRule;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\ReturnRefundItem;
use Exception;
use Illuminate\Support\Facades\Log;
use App\Providers\DBConnService; // Import DBConnService của ducminh đã viết sẵn để khởi tạo đối tượng connection đến csdl
use App\Services\OrderService;
use App\Services\CredentialsValidatorService;

/* Import class CredentialsValidator của ducminh đã viết sẵn ở AuthController để sau này có thể sử dụng */
// class CredentialsValidator
// {
// 	protected DBConnService $dbConnService;

// 	public function __construct(DBConnService $dBConnService)
// 	{
// 		$this->dbConnService = $dBConnService;
// 	}

// 	public function validateAndReturnEmail(array $request_data, bool $is_login = false): mixed
// 	{
// 		// check empty email
// 		if (empty($request_data["email"])) {
// 			throw new Exception("email is required");
// 		}

// 		$email = $request_data["email"];

// 		// email length check
// 		if (strlen($email) > 255) {
// 			throw new Exception("email is too long");
// 		}

// 		// email format check
// 		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
// 			throw new Exception("email format is invalid");
// 		}

// 		// check email availability
// 		if (!$is_login) {
// 			try {
// 				$conn = $this->dbConnService->getDBConn();
// 				$sql = "select email from users where email = ?";
// 				$pstm = $conn->prepare($sql);
// 				$pstm->bind_param("s", $email);
// 				$pstm->execute();
// 				$result = $pstm->get_result();
// 				if ($result->num_rows > 0) {
// 					throw new Exception(message: "email has been taken");
// 				}
// 			} catch (Exception $e) {
// 				Log::error("Error occurred when validating email", [
// 					'error' => $e->getMessage(),
// 				]);
// 			} finally {
// 				$pstm->close();
// 			}
// 		}

// 		// not throwing any exception, all good
// 		return $email;
// 	}

// 	public function validateAndReturnName(array $request_data)
// 	{
// 		// check empty name
// 		if (empty($request_data["name"])) {
// 			throw new Exception("user's name is required");
// 		}

// 		$name = $request_data["name"];

// 		if (strlen($name) > 255) {
// 			throw new Exception("name is too long");
// 		}

// 		// not throwing any exception, all good
// 		return $name;
// 	}

// 	public function valdiateAndReturnPassword(array $request_data)
// 	{
// 		// check empty password
// 		if (empty($request_data["password"])) {
// 			throw new Exception("password is required");
// 		}

// 		$password = $request_data["password"];

// 		// password length check
// 		if (strlen($password) < 8) {
// 			throw new Exception(message: "password must be at least 8 characters");
// 		}

// 		// not throwing any exception, all good
// 		return $password;
// 	}
// }

class ProfileController extends Controller
{
	protected CredentialsValidatorService $credentialsValidatorService;
	protected DBConnService $dbConnService;
	protected OrderService $orderService;

	public function __construct(
		DBConnService $dbConnService,
		OrderService $orderService,
		CredentialsValidatorService $credentialsValidatorService
	) {
		$this->dbConnService = $dbConnService;
		$this->orderService = $orderService;
		$this->credentialsValidatorService = $credentialsValidatorService;
	}
	/*-----------------------------------------------------------------------------------------------------------------------------------------*/


	/* Hiển thị trang Hồ sơ của người dùng */
	public function showProfilePage()
	{
		// return view('profile.index'); /* Dùng này thì trong file index dùng trực tiếp auth()->user() thay cho biến $user */  ==> cách này không khuyến khích vì vi phạm nguyên tắc MVC
		/* auth()->user() thì luôn khả dụng, không cần import          ==> khuyến khích sử dụng
											   Auth::user() thì cần import Illuminate\Support\Facades\Auth;   ==> dễ bị lỗi các trường thông tin user bị null mặc dù user đã đăng nhập rồi
											*/
		$user = auth()->user();
		return view('profile.index', compact(var_name: 'user'));
	}
	/*-----------------------------------------------------------------------------------------------------------------------------------------*/


	/* Xử lý validate cho các modal Email, Số điện thoại, Ngày sinh (Khi nhấn vào "Thay đổi" ở các trường email, số điện thoại, ngày sinh của giao diện trang Hồ sơ) */
	public function validateField(Request $request, $field)
	{
		try {
			switch ($field) {
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
	/*-----------------------------------------------------------------------------------------------------------------------------------------*/


	/* Xử lí sự kiện khi nhấn nút Lưu ở form cập nhật thông tin cá nhân ở giao diện Hồ sơ */
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
			return redirect()->route('profile.homePage')->with('info', 'Không có thay đổi nào để cập nhật');
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
			return redirect()->route('profile.homePage')->with('success', 'Cập nhật thông tin thành công');
		} else {
			$stmt->close();
			$mysqli->close();
			return redirect()->route('profile.homePage')->with('error', 'Có lỗi xảy ra. Vui lòng thử lại');
		}
	}
	/*-----------------------------------------------------------------------------------------------------------------------------------------*/


	/* Xử lí yêu cầu AJAX từ sự kiện javascript (code ở file homePage.js) 
					  Xử lý sự kiện khi nhấn vào dropdown "Đổi mật khẩu" ở giao diện trang hồ sơ
					  */
	public function showCurrentPasswordForm(Request $request)
	{
		// Kiểm tra nếu là yêu cầu AJAX
		if ($request->ajax()) {
			// Render phần form xác nhận mật khẩu hiện tại, form này sẽ thay thế cho thẻ div class="right-container-main-inner__bottom" của giao diện trang hồ sơ
			return response()->json([
				'html' => view('profile.currentPasswordInput')->render() // Đây là phần nội dung sẽ thay thế
			]);
		}
		// Trường hợp không phải AJAX, trả về lỗi 404
		abort(404);
	}
	/*-----------------------------------------------------------------------------------------------------------------------------------------*/


	/* Xử lí sự kiện khi nhấn nút XÁC NHẬN mật khẩu hiện tại ở form currentPasswordInput.blade.php */
	public function handleCurrentPasswordVerification(Request $request)
	{
		$currentUser = auth()->user();
		// dd($request->all()); // in ra dữ liệu gửi đến server khi nhấn nút Lưu từ form

		/* Sử dụng hàm $request->validate thì khi validate phát hiện có lỗi sẽ tự động điều hướng về trang trước đó. 
											Nếu validate hợp lệ thì trả về kết quả đã được validate */

		// $validatedData = $request->validate([
		//     'password' => [
		//         'required',
		//         'regex:/^[^\s]{8,20}$/'
		//     ]
		// ], [
		//     'password.required' => 'Vui lòng nhập password',
		//     'password.regex' => 'Password phải có độ dài từ 8-20 ký tự và không chứa khoảng trắng'
		// ]);

		// if (!password_verify($validatedData['password'], $currentUser->password)) {

		//     return response()->json([
		//         'html' => view('profile.currentPasswordInput')->render(),
		//         'success' => false,
		//         'message' => 'Mật khẩu hiện tại không chính xác'               
		//     ]);
		// }

		// return response()->json([
		//     'html' => view('profile.verifyNewPasswordInput')->render(),
		//     'success' => true
		// ]);

		$validator = Validator::make($request->all(), [
			'password' => [
				'required',
				'regex:/^[^\s]{8,20}$/'
			]
		], [
			'password.required' => 'Vui lòng nhập mật khẩu hiện tại',
			'password.regex' => 'Password phải có độ dài từ 8-20 ký tự và không chứa khoảng trắng'
		]);

		// Kiểm tra validation
		if ($validator->fails()) {
			return response()->json([
				'success' => false,
				'message' => $validator->errors()->first('password')
			]);
		} else {
			if (!password_verify($validator->validated()['password'], $currentUser->password)) {

				return response()->json([
					'html' => view('profile.currentPasswordInput')->render(),
					'success' => false,
					'message' => 'Mật khẩu hiện tại không chính xác'
				]);
			} else {
				return response()->json([
					'html' => view('profile.verifyNewPasswordInput')->render(),
					'success' => true
				]);
			}
		}

	}
	/*-----------------------------------------------------------------------------------------------------------------------------------------*/


	/* Xử lí yêu cầu AJAX từ sự kiện javascript (code ở file currentPasswordPage.js) 
					  Xử lý sự kiện khi nhấn vào nút XÁC NHẬN ở giao diện nhập mật khẩu hiện tại
					  */
	// public function showVerifyNewPasswordForm(Request $request)
	// {
	//     // Kiểm tra nếu là yêu cầu AJAX
	//     if ($request->ajax()) {
	//         // Render phần form xác nhận mật khẩu mới, form này sẽ thay thế cho thẻ div class="right-container-main-inner__bottom" của giao diện trang hồ sơ
	//         return response()->json([
	//             'html' => view('profile.verifyNewPasswordInput')->render() // Đây là phần nội dung sẽ thay thế
	//         ]);
	//     }
	//     // Trường hợp không phải AJAX, trả về lỗi 404
	//     abort(404);
	// }
/*-----------------------------------------------------------------------------------------------------------------------------------------*/


	/* Xử lí sự kiện khi nhấn nút Lưu mật khẩu mới ở form verifyNewPasswordInput.blade.php */
	public function handleVerifyNewPassword(Request $request)
	{
		// dd($request->all()); // in ra dữ liệu gửi đến server khi nhấn nút Lưu từ form
		$currentUser = auth()->user();

		if (password_verify($request->input('password_confirmation'), $currentUser->password)) {
			// Lưu thông báo vào session
			session()->flash('info', 'Mật khẩu không có thay đổi nào để cập nhật');

			return response()->json([
				'success' => true,
				'message' => '[Info] Mật khẩu không có thay đổi nào để cập nhật',
				'redirect_url' => route('profile.homePage')
			]);
			// return redirect()->route('profile.homePage')->with('info', 'Mật khẩu không có thay đổi nào để cập nhật');
		} else {

			$validator = Validator::make($request->all(), [
				'password' => [
					'regex:/^[^\s]{8,20}$/'
				]
			], [
				'password.regex' => 'Mật khẩu mới phải có độ dài từ 8-20 ký tự và không chứa khoảng trắng'
			]);

			if ($validator->fails()) {
				return response()->json([
					'success' => false,
					'message' => $validator->errors()->first('password')
				]);
			} else {
				$newPassword = password_hash($validator->validated()['password'], PASSWORD_DEFAULT);

				$mysqli = $this->dbConnService->getDBConn();
				$stmt = $mysqli->prepare("UPDATE users SET password = ? WHERE user_id = ?");
				$user_id = $currentUser->user_id;
				$stmt->bind_param('si', $newPassword, $user_id);

				if ($stmt->execute()) {
					$stmt->close();
					$mysqli->close();

					session()->flash('success', 'Cập nhật mật khẩu thành công');
					return response()->json([
						'success' => true,
						'message' => '[Success] Cập nhật mật khẩu thành công',
						'redirect_url' => route('profile.homePage')
					]);
					// return redirect()->route('profile.homePage')->with('success', 'Cập nhật thông tin thành công');
				} else {
					$stmt->close();
					$mysqli->close();
					return response()->json([
						'success' => false,
						'message' => 'Có lỗi xảy ra. Vui lòng thử lại'
					]);
				}
			}
		}
	}
	/*-----------------------------------------------------------------------------------------------------------------------------------------*/


	/* Xử lí yêu cầu AJAX từ sự kiện javascript (code ở file homePage.js) 
					  Xử lý sự kiện khi nhấn vào dropdown "Đơn mua" ở giao diện trang hồ sơ
					  */
	public function showOrdersForm(Request $request)
	{
		if ($request->ajax()) {
			return response()->json([
				'html' => view('profile.orders')->render()
			]);
		}
		abort(404);
	}
	/*-----------------------------------------------------------------------------------------------------------------------------------------*/

	public function showReturnsForm(Request $request)
	{
		if ($request->ajax()) {
			$returnRefundItems = ReturnRefundItem::where('user_id', auth()->id())
				->with(relations: [
						'order_item.product.product_images' => function ($query) {
							$query->where('image_type', 1)
								->select('product_image_url', 'product_id');
						}
					])
				->orderBy('created_at', 'desc')
				->get();

			Log::debug('Return refund items: ' . json_encode($returnRefundItems));
			return response()->json([
				'html' => view('profile.returns', compact('returnRefundItems'))->render()
			]);
		}
		abort(404);
	}
}
