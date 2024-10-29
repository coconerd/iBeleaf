<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
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
Route::get('/auth', [AuthController::class, 'showLoginForm']);
Route::get('/auth/login', [AuthController::class, 'showLoginForm']);
Route::post('/auth/login', [AuthController::class, 'handleLogin']);

Route::get('/auth/register', [AuthController::class, 'showRegistrationForm']);
Route::post('/auth/register', [AuthController::class, 'handleRegister']);