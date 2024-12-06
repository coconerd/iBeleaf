<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductConroller;
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
	return view('product/index');
});


/**
 * @notice Authentication routes
 */
// Login
Route::get('/auth', [AuthController::class, 'showLoginForm']);
Route::get('/auth/login', [AuthController::class, 'showLoginForm']);
Route::post('/auth/login', [AuthController::class, 'handleLogin']);

// Register
Route::get('/auth/register', action: [AuthController::class, 'showRegistrationForm']);
Route::post('/auth/register', [AuthController::class, 'handleRegister']);

// OAuth2 social login
Route::post('/auth/login/{social}', action: [AuthController::class, 'showConsentScreen']);
Route::get('/auth/login/{social}/callback', [AuthController::class, 'handleSocialCallback']);

// Profile: middleware auth để bắt buộc phải đăng nhập mới xem được các trang có route này
Route::middleware(['auth'])->group(function (): void {
	Route::get('/profile', [ProfileController::class, 'showProfilePage'])->name('profile.homepage');
	Route::post('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
	Route::post('/profile/validate/{field}', [ProfileController::class, 'validateField'])->name('profile.validate');
	Route::get('/profile/change-password', [ProfileController::class, 'showChangePassword'])->name('profile.changePassword');
	Route::get('/profile/orders', [ProfileController::class, 'showOrders'])->name('profile.orders');
	Route::get('/profile/returns', [ProfileController::class, 'showReturns'])->name('profile.returns');
});


Route::get(
	'/product/{product_id}',
	[ProductController::class, 'show']
)->name('product.show');

Route::get('/cart/add/{id}', function ($id) {
    return "Product $id added to cart.";
})->name('cart.add');

Route::post('/wishlist/add', [App\Http\Controllers\WishlistController::class, 'add'])->name('wishlist.add');

Route::post('/reviews/store}', function ($id) {
    return "Review for product $id has been saved.";
})->name('reviews.store');