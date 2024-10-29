<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Exception;
use Auth;
use App\Providers\DBConnService;

class CredentialsValidator
{
	protected DBConnService $dbConnService;

	public function __construct(DBConnService $dBConnService)
	{
		$this->dbConnService = $dBConnService;
	}

	public function validateAndReturnEmail(array $request_data, bool $is_login = false): mixed
	{
		// check empty email
		if (empty($request_data["email"])) {
			throw new Exception("email is required");
		}

		$email = $request_data["email"];

		// email length check
		if (strlen($email) > 255) {
			throw new Exception("email is too long");
		}

		// email format check
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			throw new Exception("email format is invalid");
		}

		// check email availability
		if (!$is_login) {
			$conn = $this->dbConnService->getDBConn();
			$sql = "select email from users where email = ?";
			$pstm = $conn->prepare($sql);
			$pstm->bind_param("s", $email);
			$pstm->execute();
			$result = $pstm->get_result();
			if ($result->num_rows > 0) {
				throw new Exception(message: "email has been taken");
			}
			$conn->close();
		}

		// not throwing any exception, all good
		return $email;
	}

	public function validateAndReturnName(array $request_data)
	{
		// check empty name
		if (empty($request_data["name"])) {
			throw new Exception("user's name is required");
		}

		$name = $request_data["name"];

		if (strlen($name) > 255) {
			throw new Exception("name is too long");
		}

		// not throwing any exception, all good
		return $name;
	}

	public function valdiateAndReturnPassword(array $request_data)
	{
		// check empty password
		if (empty($request_data["password"])) {
			throw new Exception("password is required");
		}

		$password = $request_data["password"];

		// password length check
		if (strlen($password) < 8) {
			throw new Exception(message: "password must be");
		}

		// not throwing any exception, all good
		return $password;
	}
}

class AuthController extends Controller
{
	protected CredentialsValidator $credentialsValidator;
	protected DBConnService $dbConnService;

	public function __construct(DBConnService $dbConnService)
	{
		$this->dbConnService = $dbConnService;
		$this->credentialsValidator = new CredentialsValidator($dbConnService);
	}

	public function showLoginForm()
	{
		return view('authentication.login');
	}

	// Handle login
	public function handleLogin(Request $request)
	{
		$request_data = $_POST;
		$email = "";
		$password = "";

		try {
			$email = $this->credentialsValidator->validateAndReturnEmail($request_data, true);
			$password = $this->credentialsValidator->valdiateAndReturnPassword($request_data);
		} catch (Exception $e) {
			back()->withErrors($e->getMessage());
		}

		// check if user exists
		// check in cache first
		$user = Cache::get($email);
		if ($user) {
			// check password
			$is_correct_password = password_verify($password, $user->password);
			if (!$is_correct_password) {
				// $this->sendErrorJSON("invalid credentials", 400);
				back()->withErrors("invalid credentials");
				return;
			}
		} else {
			// retrieve from db
			$conn = $this->dbConnService->getDBConn();
			$sql = "select * from users where email = ?";
			$pstm = $conn->prepare($sql);
			$pstm->bind_param("s", $email);
			$pstm->execute();
			$result = $pstm->get_result();
			if ($result->num_rows == 0) {
				return redirect()->back()->withErrors("account not found");
			}
			$row = $result->fetch_assoc();
			$hashed_password = $row['password'];
			$is_correct_password = password_verify($password, $hashed_password);
			if (!$is_correct_password) {
				return back()->withErrors("invalid credentials");
			}

			// fill user object
			$user = new User();
			$user->id = $row['id'];
			$user->name = $row['name'];
			$user->email = $row['email'];
			$user->password = $row['password'];
		}

		Auth::login($user);
		$request->session()->regenerate();
		return redirect()->intended('/');
	}

	// Show registration form
	public function showRegistrationForm()
	{
		return view('authentication.register');
	}

	// Handle registration
	public function handleRegister(Request $request)
	{
		$request_data = $_POST;

		if (!is_array($request_data)) {
			$request_data = json_decode($request_data);
		}

		$email = "";
		$password = "";
		$name = $request_data['name'];

		try {
			$email = $this->credentialsValidator->validateAndReturnEmail($request_data, true);
			$password = $this->credentialsValidator->valdiateAndReturnPassword($request_data);
		} catch (Exception $e) {
			back()->withErrors(provider: $e->getMessage());
		}
		// Create user
		$user = null;
		try {
			$user = User::create([
				'name' => $name,
				'email' => $email,
				'password' => $password,
			]);
		} catch (Exception $e) {
			return back()->withError("failed to create account");
		}

		// Auth::login($user);

		// store new user in cache for 30 minutes
		Cache::put(
			$user->email,
			$user,
			config('auth.register.userCache.TTLSecs', 60 * 30),
		);
		return redirect('/auth/login')->with('registerSuccess', "Đăng ký tài khoản thành công, vui lòng đăng nhập");
	}

	// Handle logout
	public function handleLogout(Request $request)
	{
		Auth::logout();

		$request->session()->invalidate();
		$request->session()->regenerateToken();

		return redirect('/');
	}
}
