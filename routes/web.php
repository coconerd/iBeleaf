<?php

use Illuminate\Support\Facades\Route;

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
Route::get('/', function() {
	return view('products/index');
});

Route::get('/products', function() {
	return view('products/index');
});

/**
 * @notice Authentication routes
 */
Route::get('auth/', function() {
	return view('authentication.login');
});

Route::get('auth/login', function() {
	return view('authentication.login');
});

Route::get('auth/register', function() {
	return view('authentication.register');
});