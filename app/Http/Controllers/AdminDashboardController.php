<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
	public function showDashboardPage()
	{
		return view('admin.dashboard.index');
	}
}
