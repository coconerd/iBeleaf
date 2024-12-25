<?php

use App\Http\Controllers\AdminDashboardController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\FunctionController;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckOutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\AdminClaimsController;
use App\Http\Controllers\AdminVoucherController;
use App\Http\Controllers\AdminNotificationController;
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
Route::get('/', [
	PagesController::class,
	"indexMain"
])->name('products');

Route::get('/get-products', [PagesController::class, 'increment']);

// search product cession
Route::get("/search-products", [FunctionController::class, 'searchProducts']);
// end search cession

Route::get('/{category}/page/{page}', [
	PagesController::class,
	"categoryMain"
])->where([
			'category' => '^(cay\-.*|chau\-.*|co\-canh|kieu\-.*|uncategorized|search)$',
			'page' => '[0-9]+'
		])->name('products.page');

Route::get('/{category}', [
	PagesController::class,
	"categoryMain"
])->where('category', '^(cay\-.*|chau\-.*|co\-canh|kieu\-.*|uncategorized|search)$')->name('products');

Route::get("/get-product", [PagesController::class, 'getCategories']);

/**
 * @notice Auth routes
 */
// Login
Route::get('/auth', [AuthController::class, 'showLoginForm'])->name('auth.index');
Route::get('/auth/login', [AuthController::class, 'showLoginForm'])->name('auth.showLoginForm');
Route::post('/auth/login', [AuthController::class, 'handleLogin'])->name('auth.login');

// Logout
Route::post('/auth/logout', [AuthController::class, 'handleLogout'])->name('auth.logout');

// Registe
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
	Route::get('/', function () {
		if (!Auth::check()) {
			return redirect()->route('admin.auth.showLoginForm');
		}
		return Auth::user()->role_type === 1
			? redirect()->route('admin.showDashboardPage')
			: redirect()->back()->with('error', 'Bạn không có quyền truy cập trang này');
	})->name('index');

	Route::prefix('auth')->name('auth.')->group(function () {
		Route::get('/login', [AuthController::class, 'showAdminLoginForm'])->name('showLoginForm');
		Route::post('/login', [AuthController::class, 'handleAdminLogin'])->name('handleLogin');
	});

	// Admin protected routes
	Route::middleware(['auth', 'role:1'])->group(function () {
		// Admin orders route
		Route::prefix('orders')->name('orders.')->group(function () {
			Route::get('/', [AdminOrderController::class, 'showOrdersPage'])->name('showOrdersPage');
			Route::get('/{order_id}/details', [AdminOrderController::class, 'getOrderDetails'])->name('getOrdersDetails');
			Route::get('/edit', [AdminOrderController::class, 'edit'])->name('edit');
			// Route::get('/orders', [AdminController::class, 'index'])->name('.management');
			Route::post('/update-field', [AdminOrderController::class, 'updateOrderField'])
				->name('updateField');
			Route::get('/statistics', [AdminOrderController::class, 'getStatistics'])->name('statistics');
		});

		// Admin Dashboard route
		Route::prefix('dashboard')->name('dashboard.')->group(function () {
			Route::get('/', [AdminDashboardController::class, 'showDashboardPage'])->name('showDashboardPage');
			Route::get('/sales-data', [AdminDashboardController::class, 'getSalesData'])->name('getSalesData');
			Route::get('/analyze/{metric}', [AdminDashboardController::class, 'analyzeMetric']);
			Route::get('/top-selling', [AdminDashboardController::class, 'topSellingProducts'])->name('topSelling');
		});

		// Admin claims routes
		Route::prefix('claims')->name('claims.')->group(function () {
			Route::get('/', [AdminClaimsController::class, 'showClaimsPage'])->name('index');
			Route::get('/{requestId}/details', [AdminClaimsController::class, 'showDetails'])->name('details');
			Route::post('/update-status', [AdminClaimsController::class, 'updateStatus'])->name('updateStatus');
			Route::get('/statistics', [AdminClaimsController::class, 'getStatistics'])->name('statistics');
		});

		// Admin products management routes
		Route::prefix('products')->name('products.')->group(function () {
			Route::get('/', [AdminProductController::class, 'showProductsPage'])->name('index');
			Route::get('/{product_id}/details', [AdminProductController::class, 'getDetails'])->name('details');
			Route::post('/{product_id}/update-field', [AdminProductController::class, 'updateField'])->name('updateField');
			Route::put('/{product_id}/update', [AdminProductController::class, 'update'])->name('update');
		});

		// Admin vouchers routes
		Route::prefix('vouchers')->name('vouchers.')->group(function () {
			Route::get('/', [AdminVoucherController::class, 'showVouchersPage'])->name('showVouchersPage');
			Route::post('/store', [AdminVoucherController::class, 'store'])->name('store');
			Route::get('/{voucher_id}/details', [AdminVoucherController::class, 'getDetails'])->name('details');
			Route::post('/{voucher_id}/update', [AdminVoucherController::class, 'update'])->name('update');
			Route::post('/{voucher_id}/delete', [AdminVoucherController::class, 'delete'])->name('delete');
		});

		// Admin notification routes
		Route::prefix('notifications')->name('notifications.')->group(function () {
			Route::get('/', [AdminNotificationController::class, 'getNotifications'])
				->name('getNotifications');
			Route::post('/{notification_id}/mark-as-read', [AdminNotificationController::class, 'markNotifcationAsRead'])
				->name('markAsRead');
		});
	});
});


/**
 * End admin routes
 */
// Profile: middleware auth để bắt buộc phải đăng nhập mới xem được các trang có route này
Route::middleware(['auth', 'role:0'])->group(function () {
	Route::get('/profile', [ProfileController::class, 'showProfilePage'])->name('profile.homePage');
	Route::post('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
	Route::post('/profile/validate/{field}', [ProfileController::class, 'validateField'])->name('profile.validate');

	Route::get('/profile/current-password', [ProfileController::class, 'showCurrentPasswordForm'])->name('profile.currentPassword');
	Route::post('/profile/currentPassword-verify', [ProfileController::class, 'handleCurrentPasswordVerification'])->name('profile.verifyCurrentPassword');
	Route::post('/profile/verify-newpassword', [ProfileController::class, 'handleVerifyNewPassword'])->name('profile.verifyNewPassword');

	// OAuth2 social login
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
	Route::get('/orders/{order_id}/claims', [OrderController::class, 'getClaims'])->name('orders.claims');
});

// Product routes
Route::get('/product/all-categories', [ProductController::class, 'getAllCategories'])
	->name('product.allCategories');
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
	Route::get('/cart/checkout', [CheckOutController::class, 'getCartItems'])
		->name('cart.checkout');
	Route::post('/cart/insert', [CartController::class, 'insertItemToCart'])
		->name('cart.insert');
	Route::post('/cart/items-update', [CartController::class, 'updateCartItems'])
		->name('cart.items.update');
	Route::post('/cart/update-price', [CartController::class, 'updatePrice'])
		->name('cart.updatePrice');
	Route::post('/cart/store-voucher', [CartController::class, 'storeVoucher'])->name('cart.store-voucher');
});

// Voucher routes
Route::middleware(['auth', 'role:0'])->group(function (): void {
	Route::post('voucher/validate', [VoucherController::class, 'validateVoucher'])
		->name('voucher.validate');
});

//Checkout route
Route::middleware(['auth', 'role:0'])->group(function () {
	Route::post('/checkout/calculate-shipping', [CheckOutController::class, 'calculatingShippingFee'])
		->name('checkout.calculateShippingFee');
	Route::get('/checkout/user-info', [CheckOutController::class, 'getUserInfo'])
		->name('checkout.userInfo');
	Route::get('/checkout/initial-shipping-fee', [CheckOutController::class, 'getInitialShippingFee'])
		->name('checkout.initialShippingFee');
	Route::get('/checkout/items', [CheckOutController::class, 'getCartItems'])
		->name('checkout.items');
	Route::post('/checkout/update-default-address', [CheckOutController::class, 'updateDefaultAddress'])
		->name('checkout.updateDefaultAddress');
	Route::post('/checkout/submit-order', [CheckOutController::class, 'submitOrder'])
		->name('checkout.submitOrder');
	Route::get('/checkout/success', [CheckOutController::class, 'showSuccessPage'])
		->name('checkout.success');
});

// catch 404 not found
Route::fallback(function () {
	return redirect('/404');
});

Route::get('/404', function () {
	return view('errors.404');
});
