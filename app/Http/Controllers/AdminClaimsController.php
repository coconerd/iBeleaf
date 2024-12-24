<?php

namespace App\Http\Controllers;

use App\Notifications\ClaimRejectedNotification;
use Illuminate\Http\Request;
use App\Models\ReturnRefundItem;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use App\Notifications\ClaimAcceptedNotification;

class AdminClaimsController extends Controller
{
	// Display the list of refund and return requests
	public function showClaimsPage(Request $request)
	{
		$direction = $request->query('direction', 'desc');
		$type = $request->query('type');

		// Pending requests (status = 'pending')
		$refundRequests = ReturnRefundItem::with(['order_item.product.product_images', 'user'])
			->where('type', 'refund')
			->where('status', 'pending')
			->orderBy('created_at', 'desc')
			->paginate(10, ['*'], 'pending_refund_page');

		$returnRequests = ReturnRefundItem::with(['order_item.product.product_images', 'user'])
			->where('type', 'return')
			->where('status', 'pending')
			->orderBy('created_at', 'desc')
			->paginate(10, ['*'], 'pending_return_page');

		// Processed requests with dynamic sorting
		$processedRefundRequests = ReturnRefundItem::with(['order_item.product.product_images', 'user'])
			->where('type', 'refund')
			->whereIn('status', ['rejected', 'received'])
			->when($type === 'refund', function ($query) use ($direction) {
				return $query->orderBy('updated_at', $direction);
			}, function ($query) {
				return $query->orderBy('updated_at', 'desc');
			})
			->paginate(5, ['*'], 'processed_refund_page');

		$processedReturnRequests = ReturnRefundItem::with(['order_item.product.product_images', 'user'])
			->where('type', 'return')
			->whereIn('status', ['rejected', 'received'])
			->when($type === 'return', function ($query) use ($direction) {
				return $query->orderBy('updatd_at', $direction);
			}, function ($query) {
				return $query->orderBy('updated_at', 'desc');
			})
			->paginate(5, ['*'], 'processed_return_page');

		// Add queries for completed requests
		$completedRefundRequests = ReturnRefundItem::with(['order_item.product.product_images', 'user'])
			->where('type', 'refund')
			->where(function ($query) {
				$query->where('status', ReturnRefundItem::STATUS_REFUNDED)
					->orWhere(function ($q) {
						$q->where('status', ReturnRefundItem::STATUS_RECEIVED)
							->where('type', 'refund');
					});
			})
			->when($type === 'refund', function ($query) use ($direction) {
				return $query->orderBy('updated_at', $direction);
			}, function ($query) {
				return $query->orderBy('updated_at', 'desc');
			})
			->paginate(5, ['*'], 'completed_refund_page');

		$completedReturnRequests = ReturnRefundItem::with(['order_item.product.product_images', 'user'])
			->where('type', 'return')
			->where(function ($query) {
				$query->where('status', ReturnRefundItem::STATUS_RENEWED)
					->orWhere(function ($q) {
						$q->where('status', ReturnRefundItem::STATUS_RECEIVED)
							->where('type', 'return');
					});
			})
			->when($type === 'return', function ($query) use ($direction) {
				return $query->orderBy('updated_at', $direction);
			}, function ($query) {
				return $query->orderBy('updated_at', 'desc');
			})
			->paginate(5, ['*'], 'completed_return_page');

		return view('admin.claims.index', compact(
			'refundRequests',
			'returnRequests',
			'processedRefundRequests',
			'processedReturnRequests',
			'completedRefundRequests',
			'completedReturnRequests'
		));
	}

	// Show details of a specific request
	public function showDetails($requestId): JsonResponse
	{
		$requestItem = ReturnRefundItem::with([
			'order_item.product.product_images',
			'user',
			'refund_return_images'
		])->find($requestId);

		// Log::debug('Retrieved request item: ', $requestItem->toArray());

		// pre-processing the images (base64-encode) before sending to client
		foreach ($requestItem->refund_return_images as $image) {
			$image->refund_return_image = base64_encode($image->refund_return_image);
		}

		if (!$requestItem) {
			return response()->json(['success' => false, 'message' => 'Yêu cầu không tồn tại.'], 404);
		}

		return response()->json(['success' => true, 'request' => $requestItem]);
	}

	// Update the status of a request
	public function updateStatus(Request $request): JsonResponse
	{
		try {
			$validatedData = $request->validate([
				'request_id' => 'required|integer|exists:return_refund_items,return_refund_id',
				'status' => 'required|string|in:pending,accepted,rejected,received,refunded,renewed',
				'reject_reason' => 'nullable|string'
			]);

			$returnRefundItem = ReturnRefundItem::with(['user', 'order_item.product'])
				->find($validatedData['request_id']);

			if (!$returnRefundItem) {
				return response()->json(['success' => false, 'message' => 'Yêu cầu không tồn tại.'], 404);
			}

			$notifySuccess = true;

			// Update status based on request type and new status
			$newStatus = $validatedData['status'];
			switch ($newStatus) {
				case 'rejected':
					$returnRefundItem->status = ReturnRefundItem::STATUS_REJECTED;
					$returnRefundItem->reject_reason = $validatedData['reject_reason'];
					try {
						$returnRefundItem->user->notify(new ClaimRejectedNotification($returnRefundItem));
					} catch (\Exception $e) {
						Log::error('Error sending notification: ' . $e->getMessage());
						$notifySuccess = false;
					}
					break;
				case 'accepted':
					$returnRefundItem->status = ReturnRefundItem::STATUS_ACCEPTED;
					try {
						$returnRefundItem->user->notify(new ClaimAcceptedNotification($returnRefundItem));
					} catch (\Exception $e) {
						Log::error('Error sending notification: ' . $e->getMessage());
						$notifySuccess = false;
					}
					break;
				default:
					$returnRefundItem->status = $validatedData['status'];
					break;
			}

			$returnRefundItem->save();

			return response()->json([
				'success' => true,
				'message' => $notifySuccess
					? 'Cập nhật trạng thái thành công.'
					: 'Cập nhật trạng thái thành công, nhưng không thể gửi thông báo cho người dùng.'
			]);
		} catch (\Exception $e) {
			Log::error('Error updating status: ' . $e->getMessage());
			return response()->json(['success' => false, 'message' => 'Có lỗi xảy ra khi cập nhật yêu cầu'], 500);
		}
	}
}
