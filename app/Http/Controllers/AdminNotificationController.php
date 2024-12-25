<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Log;

class AdminNotificationController extends Controller
{
	public function getNotifications(Request $request)
	{
		$status = $request->input('status', 'all');
		try {
			$query = match ($status) {
				'unread' => Auth::user()->unreadNotifications(),
				'read' => Auth::user()->readNotifications(),
				default => Auth::user()->notifications()
			};
			$query->orderBy('created_at', 'desc');
			$notifications = $query->get();
			return response()->json(
				['success' => true, 'notifications' => $notifications],
				200
			);
		} catch (\Exception $e) {
			Log::error('AdminNotificationController@getUnreadNotifications: ' . $e->getMessage());
			return response()->json(
				['success' => false, 'message' => $e->getMessage()],
				500
			);
		}
	}

	public function markNotifcationAsRead($notification_id)
	{
		try {
			Auth::user()
				->unreadNotifications()
				->where('id', $notification_id)
				->first()
				->markAsRead();
			return response()->json(
				['success' => true],
				200
			);
		} catch (\Exception $e) {
			Log::error('AdminNotificationController@markNotifcationAsRead: ' . $e->getMessage());
			return response()->json(
				['success' => false, 'message' => $e->getMessage()],
				500
			);
		}
	}
}
