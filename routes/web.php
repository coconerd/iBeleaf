<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/**
 * @notice Landing page
 */
Route::get('/', function () {
	return view('products/index');
});

Route::get('/products', function () {
	return view('products/index');
});

/**
 * @notice Authentication routes
 */
// Login
Route::get('/auth', [AuthController::class, 'showLoginForm']);
Route::get('/auth/login', [AuthController::class, 'showLoginForm']);
Route::post('/auth/login', [AuthController::class, 'handleLogin']);

// Register
Route::get('/auth/register', [AuthController::class, 'showRegistrationForm']);
Route::post('/auth/register', [AuthController::class, 'handleRegister']);

// OAuth2 social login
Route::get('/auth/login/{social}', action: [AuthController::class, 'showConsentScreen']);
Route::get('/auth/login/{social}/callback', [AuthController::class, 'handleSocialCallback']);

// Profile: middleware auth để bắt buộc phải đăng nhập mới xem được các trang có route này
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'showProfilePage'])->name('profile.homePage');
    Route::post('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/validate/{field}', [ProfileController::class, 'validateField'])->name('profile.validate');

	Route::get('/profile/current-password', [ProfileController::class, 'showCurrentPasswordForm'])->name('profile.currentPassword');
	Route::post('/profile/currentPassword-verify', [ProfileController::class, 'handleCurrentPasswordVerification'])->name('profile.verifyCurrentPassword');
	// Route::get('/profile/form-newpassword', [ProfileController::class, 'showVerifyNewPasswordForm'])->name('profile.showVerifyNewPasswordForm');
	// Route::get('/get-showVerifyNewPasswordForm-url', function() {
	// 	return response()->json([
	// 		'url' => route('profile.showVerifyNewPasswordForm')
	// 	]);
	// });

	Route::post('/profile/verify-newpassword', [ProfileController::class, 'handleVerifyNewPassword'])->name('profile.verifyNewPassword');

	Route::get('/profile/orders', [ProfileController::class, 'showOrders'])->name('profile.orders');
	Route::get('/profile/returns', [ProfileController::class, 'showReturns'])->name('profile.returns');
});
