<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Cart;
use Illuminate\Support\Facades\Cache;
use Exception;
use Auth;
use App\Providers\DBConnService;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Log;
use App\Services\CredentialsValidatorService;

class AuthUtils

{
	public static function random_string(
		int $length = 64,
		string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
	): string {
		if ($length < 1) {
			throw new \RangeException("Length must be a positive integer");
		}
		$pieces = [];
		$max = mb_strlen($keyspace, '8bit') - 1;
		for ($i = 0; $i < $length; ++$i) {
			$pieces[] = $keyspace[random_int(0, $max)];
		}
		return implode('', $pieces);
	}

	public static function random_password(): string
	{
		return bcrypt(self::random_string());
	}

	public static function random_username(string $prefix): string
	{
		$prefixLen = strlen($prefix);

		if ($prefixLen > 50) {
			throw new \RangeException("Prefix length must be less than or equal to 50");
		}

		$usernameLen = min($prefixLen + 4, 50);
		$randomUsername = $prefix . self::random_string($usernameLen - strlen($prefix));
		return $randomUsername;
	}
}

class AuthController extends Controller
{
	protected CredentialsValidatorService $credentialsValidatorService;
	protected DBConnService $dbConnService;

	public function __construct(
		DBConnService $dbConnService,
		CredentialsValidatorService $credentialsValidatorService,
	)
	{
		$this->dbConnService = $dbConnService;
		$this->credentialsValidatorService = $credentialsValidatorService;
	}

	public function showLoginForm()
	{
		if (Auth::check()) {
			return redirect()->intended('/');
		}
		return view('authentication.login');
	}

	// Handle login
	public function handleLogin(Request $request)
	{
		$request_data = $_POST;
		$email = "";
		$password = "";

		try {
			$email = $this->credentialsValidatorService->validateAndReturnEmail($request_data, true);
			$password = $this->credentialsValidatorService->validateAndReturnPassword($request_data);
		} catch (Exception $e) {
			Log::error("An error occurred in func. handleLogin()", [
				'error' => $e->getMessage(),
				'request_data' => $request->all(), // Optional: log request data
			]);
			return redirect()->back()->withErrors($e->getMessage());
		}

		// check if user exists
		// check in cache first
		$user = Cache::get($email);
		if ($user) {
			// check password
			$is_correct_password = password_verify($password, $user->password);
			Log::info("User found in cache", [
				'user' => $user,
			]);
			if (!$is_correct_password) {
				// $this->sendErrorJSON("invalid credentials", 400);
				return redirect()->back()->withErrors("Thông tin đăng nhập sai");
			}
		} else {
			// retrieve from db
			try {
				$conn = $this->dbConnService->getDBConn();
				$sql = "select * from users where email = ?";
				$pstm = $conn->prepare($sql);
				$pstm->bind_param("s", $email);
				$pstm->execute();
				$result = $pstm->get_result();
				if ($result->num_rows == 0) {
					Log::error("User not found in database", [
						'email' => $email,
					]);
					return redirect()->back()->withErrors(provider: "Thông tin đăng nhập sai");
				}
				$row = $result->fetch_assoc();
				$hashed_password = $row['password'];
				$is_correct_password = password_verify($password, $hashed_password);
				if (!$is_correct_password) {
					return redirect()->back()->withErrors("Thông tin đăng nhập sai");
				}

				// fill user object
				$user = new User();
				$user->user_id = $row['user_id'];
				$user->full_name = $row['full_name'];
				$user->email = $row['email'];
				$user->password = $row['password'];
				Auth::login($user);

				if (Auth::check()) {
					Log::debug('Login successful for user: ', ['user_id' => $user->user_id]);
					$request->session()->regenerate();
					return redirect()->intended('/');
				} else {
					Log::debug('Login failed for user: ', ['user_id' => $user->user_id]);
					throw new Exception();
				}
			} catch (Exception $e) {
				Log::error("An error occurred in func. handleLogin()", [
					'error' => $e->getMessage(),
					'request_data' => $request->all(), // Optional: log request data
				]);
				return redirect()->back()->withErrors("Có lỗi xảy ra khi đăng nhập");
			} finally {
				$pstm->close();
			}
		}
	}

	// Show registration form
	public function showRegistrationForm()
	{
		return view('authentication.register');
	}

	// Handle registration
	public function handleRegister(Request $request): mixed
	{
		$request_data = $_POST;

		if (!is_array($request_data)) {
			$request_data = json_decode(json: $request_data);
		}

		$email = "";
		$password = "";
		$name = $request_data['name'];

		try {
			$email = $this->credentialsValidatorService->validateAndReturnEmail($request_data, true);
			$password = $this->credentialsValidatorService->validateAndReturnPassword($request_data);
		} catch (Exception $e) {
			Log::error("An error occurred in func. handleRegister()", [
				'error' => $e->getMessage(),
				'request_data' => $request->all(), // Optional: log request data
			]);
			return redirect()->back()->withErrors($e->getMessage());
		}

		// Create user
		$user = null;
		$emailPrefix = explode($email, string: '@')[0];
		$randomUsername = null;
		if (strlen($emailPrefix) > 50) {
			$randomUsername = AuthUtils::random_string(6);
		} else {
			$randomUsername = AuthUtils::random_username($emailPrefix);
		}
		try {
			DB::beginTransaction();
			$cart = Cart::create(attributes: [
				'items_count' => 0,
			]);
			$user = User::create(attributes: [
				'full_name' => $name,
				'user_name' => $randomUsername,
				'email' => $email,
				'password' => $password,
				'role_type' => 0,
				'cart_id' => $cart->cart_id,
			]);
			DB::commit();
		} catch (Exception $e) {
			DB::rollback();
			if (strpos($e->getMessage(), "1062 Duplicate") !== false) {
				return redirect()->back()->withErrors("Email đã được đăng ký");
			}
			Log::error("An error occurred in func. handleRegister()", [
				'error' => $e->getMessage(),
				'request_data' => $request->all(), // Optional: log request data
			]);
			return redirect()->back()->withErrors("Có lỗi xảy ra khi đăng ký");
		}
		Auth::login($user);

		// store new user in cache for 30 minutes
		// Cache::put(
		// 	$user->email,
		// 	$user,
		// 	config('auth.register.userCache.TTLSecs', 60 * 30),
		// );

		// return redirect('/auth/login')->with('registerSuccess', "Đăng ký tài khoản thành công, vui lòng đăng nhập");
		return redirect()->intended('/');
	}

	// Handle logout
	public function handleLogout(Request $request)
	{
		Auth::logout();

		$request->session()->invalidate();
		$request->session()->regenerateToken();

		return redirect('/');
	}

	public function showConsentScreen($social)
	{
		switch ($social) {
			case 'facebook':
			case 'google':
				return Socialite::driver($social)->redirect();
			default:
				return redirect()->back()->withErrors("Mạng xã hội này không được hỗ trợ");
		}

	}

	public function handleSocialCallback(Request $request, $social)
	{
		$conn = $this->dbConnService->getDbConn();
		$pstm = $conn->prepare("select user_id, full_name, email, password from users where email = ?");
		try {
			$googleUser = Socialite::driver($social)->user();

			$email = $googleUser->getEmail();
			$pstm->bind_param("s", $email);
			$pstm->execute();
			$result = $pstm->get_result();

			// if user already exists, sign them in directly
			if ($result->num_rows > 0) {
				$record = $result->fetch_assoc();
				$user = new User();
				$user->user_id = $record['user_id'];
				$user->email = $record['email'];
				$user->full_name = $record['full_name'];
				$user->password = $record['password'];
				Auth::login(user: $user);
				$request->session()->regenerate();

				// Otherwise, create a new user and sign them in
			} else {
				$name = $googleUser->getName();
				$password = AuthUtils::random_password();
				$emailPrefix = explode('@', $email)[0];
				$randomUsername = AuthUtils::random_username($emailPrefix);
				$cart = Cart::create(attributes: [
					'items_count' => 0,
				]);
				$user = User::create([
					'email' => $email,
					'full_name' => $name,
					'user_name' => $randomUsername,
					'password' => $password,
					'role_type' => 0,
					'cart_id' => $cart->cart_id,
				]);
				Auth::login(user: $user);
			}

			if (Auth::check()) {
				Log::debug('Login successful for user: ', ['user_id' => $user->user_id]);
				$request->session()->regenerate();
				return redirect()->intended('/');
			} else {
				Log::debug('Login failed for user: ', ['user_id' => $user->user_id]);
				throw new Exception();
			}

		} catch (\mysqli_sql_exception $me) {
			switch ($me->getCode()) {
				case 1062:
					Log::error("MySQL duplicate entry error in func. handleGoogleCallback()", [
						'error' => $me->getMessage(),
						'request_data' => $request->all(), // Optional: log request data
					]);
					return redirect('/auth/login')->withErrors("Email đã được đăng ký");
				default:
					// Handle other MySQL error
					Log::error('A MySQL error occurred in func. handleGoogleCallback()', [
						'error' => $me->getMessage(),
						'request_data' => $request->all(), // Optional: log request data
					]);
					return redirect('/auth/login')->withErrors("Có lỗi xảy ra khi đăng nhập");
			}
		} catch (Exception $e) {
			Log::error('An error occurred in func. handleGoogleCallback()', [
				'error' => $e->getMessage(),
				'request_data' => $request->all(), // Optional: log request data
			]);

			return redirect('/auth/login')->withErrors("Có lỗi xảy ra khi đăng nhập");
		} finally {
			$pstm->close();
		}
	}

	public function showAdminLoginForm()
	{
		if (Auth::check() && Auth::user()->role_type == 1) {
			return redirect()->intended('/admin/dashboard');
		}
		return view('admin.auth.login');
	}

	public function handleAdminLogin(Request $request)
	{
		$request_data = $_POST;
		$password = "";

		try {
			$username = $request_data["username"];
			$password = $this->credentialsValidatorService->validateAndReturnPassword($request_data);

			$conn = $this->dbConnService->getDBConn();
			$sql = "select user_id, full_name, email, password, role_type from users where user_name = ?";
			$pstm = $conn->prepare($sql);
			$pstm->bind_param("s", $username);
			$pstm->execute();
			$result = $pstm->get_result();

			if ($result->num_rows == 0) {
				Log::debug('User not found in database', [
					'email' => $username,
				]);
				throw new Exception("Invalid credentials");
			}

			$row = $result->fetch_assoc();
			Log::debug('User found in database', [
				'user' => $row,
			]);

			// Check if user is admin
			if ($row['role_type'] != 1) {
				throw new Exception("Unauthorized access");
			}

			// Verify password
			if (!password_verify($password, $row['password'])) {
				throw new Exception("Invalid credentials");
			}

			$user = new User();
			$user->user_id = $row['user_id'];
			$user->full_name = $row['full_name'];
			$user->email = $row['email'];
			$user->password = $row['password'];
			$user->role_type = $row['role_type'];

			Auth::login($user);
			$request->session()->regenerate();

			return redirect()->intended('/admin/dashboard');

		} catch (Exception $e) {
			Log::error("Admin login error", [
				'error' => $e->getMessage(),
				'email' => $username
			]);
			return redirect()->back()->withErrors($e->getMessage());
		}
	}
}
