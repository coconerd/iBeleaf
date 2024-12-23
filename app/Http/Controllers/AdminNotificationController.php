<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Log;

class AdminNotificationController extends Controller
{
	public function getUnreadNotifications()
	{
		try {
			$query = Auth::user()->unreadNotifications();
			$query->orderBy('created_at', 'desc');
			$notifications = $query->get();
			return response()->json(['success' => true, 'notifications' => $notifications], 200);
		} catch (\Exception $e) {
			Log::error('AdminNotificationController@getUnreadNotifications: ' . $e->getMessage());
			return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
		}
	}
}
