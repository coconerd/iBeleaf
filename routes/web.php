<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\FeedbackController;

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
	return view('tmp');
});


/**
 * @notice Authentication routes
 */
// Login
Route::get('/auth', [AuthController::class, 'showLoginForm'])->name('auth.index');
Route::get('/auth/login', [AuthController::class, 'showLoginForm'])->name('auth.showLoginForm');
Route::post('/auth/login', [AuthController::class, 'handleLogin'])->name('auth.login');

// Register
Route::get('/auth/register', [AuthController::class, 'showRegistrationForm'])->name('auth.showRegisterForm');
Route::post('/auth/register', [AuthController::class, 'handleRegister'])->name('auth.register');

// OAuth2 social login
Route::post('/auth/login/{social}', [AuthController::class, 'showConsentScreen']);
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

	Route::get('/profile/orders', [ProfileController::class, 'showOrdersForm'])->name('profile.showOrdersForm');
	Route::get('/profile/returns', [ProfileController::class, 'showReturns'])->name('profile.returns');
});

// Order routes
Route::middleware(['auth'])->group(function () {
	Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
	Route::get('/orders/{order_id}', [OrderController::class, 'show'])->name('orders.show');
	Route::post('/orders/store', [OrderController::class, 'store'])->name('orders.store');
	Route::post('/orders/update', [OrderController::class, 'update'])->name('orders.update');
	Route::post('/orders/delete', [OrderController::class, 'delete'])->name('orders.delete');
	Route::post('/orders/submit-feedback', [OrderController::class, 'submitFeedback'])->name('orders.submitFeedback');
});

// Product routes
Route::get(
	'/product/{product_id}',
	[ProductController::class, 'show']
)->name('product.show');

// Wishlist routes
Route::middleware(['auth'])->group(function (): void {
	Route::get('/wishlist', [WishlistController::class, 'index'])->name(name: 'wishlist.index');
	Route::post('/wishlist/add', [WishlistController::class, 'add'])->name('wishlist.add');
	Route::post('/wishlist/remove', [WishlistController::class, 'remove'])->name('wishlist.remove');
});

// Feedback routes
Route::get('/feedback/{product_id}', [FeedbackController::class, 'index'])->name('feedback.index');
Route::middleware('auth')->post('/feedback/store', [FeedbackController::class, 'store'])->name('feedback.store');

//Cart routes
Route::get('/cart', [CartController::class, 'showCartItems'])->name('cart.view')
	->middleware('auth');
Route::post('/cart/update-count', [CartController::class, 'updateItemsCount'])
	->name('cart.update-count')
	->middleware('auth');
