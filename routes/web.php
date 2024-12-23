<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\FunctionController;

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


// ------------------ learn ------------------
// Route::get("/test1", function() {
// 	return ["name1", "name2", "name3"];
// });

// Route::get("/test", function() {
// 	return response()->json([
// 		"name" => "Tran vu bao",
// 		"email" => "tranvubao@gmail.com"
// 	]); // response()
// });

// // redirect
// Route::get("redirect", function() {	
// 	return redirect("/test1");
// });

