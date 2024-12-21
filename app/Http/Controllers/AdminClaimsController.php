<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReturnRefundItem;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;

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
				return $query->orderBy('created_at', $direction);
			}, function ($query) {
				return $query->orderBy('created_at', 'desc');
			})
			->paginate(10, ['*'], 'processed_refund_page');

		$processedReturnRequests = ReturnRefundItem::with(['order_item.product.product_images', 'user'])
			->where('type', 'return')
			->whereIn('status', ['rejected', 'received'])
			->when($type === 'return', function ($query) use ($direction) {
				return $query->orderBy('created_at', $direction);
			}, function ($query) {
				return $query->orderBy('created_at', 'desc');
			})
			->paginate(10, ['*'], 'processed_return_page');

		return view('admin.claims.index', compact(
			'refundRequests',
			'returnRequests',
			'processedRefundRequests',
			'processedReturnRequests'
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
				'status' => 'required|string|in:accepted,rejected',
				'reject_reason' => 'nullable|string'
			]);

			$returnRefundItem = ReturnRefundItem::find($validatedData['request_id']);

			if (!$returnRefundItem) {
				return response()->json(['success' => false, 'message' => 'Yêu cầu không tồn tại.'], 404);
			}

			$returnRefundItem->status = $validatedData['status'];

			if ($validatedData['status'] === 'rejected') {
				$returnRefundItem->reject_reason = $validatedData['reject_reason'];
			}

			$returnRefundItem->save();
			return response()->json(['success' => true, 'message' => 'Cập nhật trạng thái thành công.']);
		} catch (\Exception $e) {
			Log::error('Error updating status of refund/return request: ', ['error' => $e->getMessage()]);
			return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
		}
	}
}
