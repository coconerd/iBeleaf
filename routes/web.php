<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckOutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\VoucherController;
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
 * @notice Auth routes
 */
// Login
Route::get('/auth', [AuthController::class, 'showLoginForm'])->name('auth.index');
Route::get('/auth/login', [AuthController::class, 'showLoginForm'])->name('auth.showLoginForm');
Route::post('/auth/login', [AuthController::class, 'handleLogin'])->name('auth.login');

// Logout
Route::post('/auth/logout', [AuthController::class, 'handleLogout'])->name('auth.logout');

// Register
Route::get('/auth/register', [AuthController::class, 'showRegistrationForm'])->name('auth.showRegisterForm');
Route::post('/auth/register', [AuthController::class, 'handleRegister'])->name('auth.register');

// OAuth2 social login
Route::post('/auth/login/{social}', [AuthController::class, 'showConsentScreen']);
Route::get('/auth/login/{social}/callback', [AuthController::class, 'handleSocialCallback']);
/** End auth routes */

/**
 * @notice Admin routes
 */
Route::prefix('admin')->name('admin.')->group(function () {
	// Public admin routes
	Route::get('/', function() {
		return Auth::check() && Auth::user()->role_type === 1 
			? redirect()->route('admin.dashboard')
			: redirect()->route('admin.showLoginForm');
	})->name('index');
	Route::get('/login', [AuthController::class, 'showAdminLoginForm'])->name('showLoginForm');
	Route::post('/login', [AuthController::class, 'handleAdminLogin'])->name('handleLogin');
	
	// Protected admin routes
	Route::middleware(['auth', 'role:1'])->group(function () {
		Route::get('/dashboard', [AuthController::class, 'showAdminDashboard'])->name('dashboard');
		Route::post('/logout', [AuthController::class, 'handleAdminLogout'])->name('logout');
	});
});

/**
 * End admin routes
 */

// Profile: middleware auth để bắt buộc phải đăng nhập mới xem được các trang có route này
Route::middleware(['auth'])->group(function () {
	Route::get('/profile', [ProfileController::class, 'showProfilePage'])->name('profile.homePage');
	Route::post('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
	Route::post('/profile/validate/{field}', [ProfileController::class, 'validateField'])->name('profile.validate');

	Route::get('/profile/current-password', [ProfileController::class, 'showCurrentPasswordForm'])->name('profile.currentPassword');
	Route::post('/profile/currentPassword-verify', [ProfileController::class, 'handleCurrentPasswordVerification'])->name('profile.verifyCurrentPassword');
	Route::post('/profile/verify-newpassword', [ProfileController::class, 'handleVerifyNewPassword'])->name('profile.verifyNewPassword');

	Route::middleware(['role:0'])->get('/profile/orders', [ProfileController::class, 'showOrdersForm'])->name('profile.showOrdersForm');
	Route::middleware(['role:0'])->get('/profile/returns', [ProfileController::class, 'showReturnsForm'])->name('profile.returns');
});

// Order routes
Route::middleware(['auth', 'role:0'])->group(function () {
	Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
	Route::get('/orders/{order_id}/detail', [OrderController::class, 'showDetail'])->name('orders.detail');
	Route::post('/orders/store', [OrderController::class, 'store'])->name('orders.store');
	Route::post('/orders/update', [OrderController::class, 'update'])->name('orders.update');
	Route::post('/orders/delete', [OrderController::class, 'delete'])->name('orders.delete');
	Route::post('/orders/submit-feedback', [OrderController::class, 'submitFeedback'])->name('orders.submitFeedback');
	Route::post('/orders/cancel/{order_id}', [OrderController::class, 'cancel'])->name('orders.cancel');
	Route::post('/orders/submit-refund-return', [OrderController::class, 'submitRefundReturn'])->name('orders.submitRefundReturn');
});

// Product routes
Route::get('/product/{product_id}', [ProductController::class, 'show'])
	->name('product.show');
Route::middleware(['auth', 'role:0'])
	->post('/product/submit-feedback', [ProductController::class, 'submitFeedback'])
	->name('product.submitFeedback');

// Wishlist routes
Route::middleware(['auth', 'role:0'])->group(function (): void {
	Route::get('/wishlist', [WishlistController::class, 'index'])->name(name: 'wishlist.index');
	Route::post('/wishlist/add', [WishlistController::class, 'add'])->name('wishlist.add');
	Route::post('/wishlist/remove', [WishlistController::class, 'remove'])->name('wishlist.remove');
});

// Feedback routes
Route::get('/feedback/{product_id}', [FeedbackController::class, 'index'])->name('feedback.index');

// Cart routes
Route::middleware(['auth', 'role:0'])->group(function () {
	Route::get('/cart/items', [CartController::class, 'showCartItems'])
		->name('cart.items');
	Route::post('/cart/update', [CartController::class, 'updateItemsCount'])
		->name('cart.update');
	Route::delete('/cart/{cartId}/{productId}', [CartController::class, 'removeCartItem'])
		->name('cart.remove');
	Route::get('/cart/checkout', [CheckOutController::class, 'showShippingForm'])
		->name('cart.checkout');
	Route::post('/cart/insert', [CartController::class, 'insertItemToCart'])
		->name('cart.insert');
});

// Voucher routes
Route::middleware(['auth'])->group(function (): void {
	Route::post('voucher/validate', [VoucherController::class, 'validateVoucher'])
		->name('voucher.validate');
});