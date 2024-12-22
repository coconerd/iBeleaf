<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use App\Models\VoucherRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Log;
use Carbon\Carbon;

class AdminVoucherController extends Controller
{
	public function showVouchersPage(Request $request)
	{
		$query = Voucher::with('voucherRules');

		// Handle search
		if ($request->has('search') && $request->has('type')) {
			$searchTerm = $request->search;
			$searchType = $request->type;
			
			$query->where($searchType, 'LIKE', "%{$searchTerm}%");
		}

		// Handle status filter
		if ($request->has('status')) {
			$now = Carbon::now();
			switch ($request->status) {
				case 'not_yet':
					$query->where('voucher_start_date', '>', $now);
					break;
				case 'active':
					$query->where('voucher_start_date', '<=', $now)
						  ->where('voucher_end_date', '>=', $now);
					break;
				case 'expired':
					$query->where('voucher_end_date', '<', $now);
					break;
			}
		}

		// Handle sorting
		if ($request->has('sort')) {
			$direction = $request->get('direction', 'desc');
			$query->orderBy($request->sort, $direction);
		} else {
			$query->orderBy('voucher_start_date', 'desc'); // Default sort
		}

		$vouchers = $query->paginate(10)->withQueryString(); // Add pagination with URL query preservation
		return view('admin.vouchers.index', compact('vouchers'));
	}

	public function store(Request $request)
	{
		try {
			DB::beginTransaction();

			// Parse dates from Flatpickr format (d-m-Y H:i)
			$startDate = Carbon::createFromFormat('d-m-Y H:i', $request->start_date);
			$endDate = Carbon::createFromFormat('d-m-Y H:i', $request->end_date);

			$voucher = Voucher::create([
				'voucher_name' => $request->voucher_name,
				'voucher_type' => $request->voucher_type,
				'description' => $request->description,
				'voucher_start_date' => $startDate,
				'voucher_end_date' => $endDate,
				'value' => $request->value ?? 0,
			]);

			// Handle voucher rules
			if ($request->has('rules')) {
				foreach ($request->rules as $rule) {
					VoucherRule::create([
						'voucher_id' => $voucher->voucher_id,
						'rule_type' => $rule['type'],
						'rule_value' => $rule['value'] ?? 0,
					]);
				}
			}
			Log::debug('here');

			DB::commit();
			// return response()->json(['success' => true, 'message' => 'Tạo mã giảm giá thành công']);
			return redirect()->back()->with('success', 'Tạo mã giảm giá thành công');
		} catch (\Exception $e) {
			DB::rollBack();
			Log::error('AdminVouherController@store : ' . $e->getMessage());
			return redirect()->back()->with('error', 'Có lỗi xảy ra khi tạo mã giảm giá');
			// return response()->json(['success' => false, 'error' => $e->getMessage(), 'message' => 'Có lỗi khi tạo mã giảm giá'], 500);
		}
	}

	public function getDetails($voucher_id)
	{
		$voucher = Voucher::with('voucherRules')->findOrFail($voucher_id);
		return response()->json($voucher);
	}

	public function update(Request $request, $voucher_id)
	{
		Log::debug('here');
		Log::debug($request->all());
		try {
			DB::beginTransaction();

			$voucher = Voucher::findOrFail($voucher_id);

			// Parse dates from Flatpickr's default format
			$data = $request->except('rules');
			if ($request->has('start_date')) {
				$data['voucher_start_date'] = Carbon::createFromFormat('d-m-Y H:i', $request->start_date);
			}
			if ($request->has('end_date')) {
				$data['voucher_end_date'] = Carbon::createFromFormat('d-m-Y H:i', $request->end_date);
			}

			$data['value'] ??= 0;
			$voucher->update($data);

			// Update rules
			if ($request->has('rules')) {
				$voucher->voucherRules()->delete();
				foreach ($request->rules as $rule) {
					VoucherRule::create([
						'voucher_id' => $voucher->voucher_id,
						'rule_type' => $rule['type'],
						'rule_value' => $rule['value'] ?? 0,
					]);
				}
			}

			DB::commit();
			// return response()->json(['message' => 'Voucher updated successfully']);
			return redirect()->back()->with('success', 'Cập nhật mã giảm giá thành công');
		} catch (\Exception $e) {
			DB::rollBack();
			Log::error('AdminVoucherController@update : ' . $e->getMessage());
			// return response()->json(['error' => $e->getMessage()], 500);
			return redirect()->back()->with('error', 'Có lỗi xảy ra khi cập nhật mã giảm giá');
		}
	}

	public function delete($voucher_id)
	{
		try {
			DB::beginTransaction();
			$voucher = Voucher::findOrFail($voucher_id);
			VoucherRule::where('voucher_id', '=', $voucher_id)->delete();
			$voucher->delete();
			DB::commit();
			return response()->json(['success' => true, 'message' => 'Xóa mã giảm giá thành công']);
		} catch (\Exception $e) {
			DB::rollback();
			Log::error('AdminVoucherController@delete : ' . $e->getMessage());
			return response()->json(['success' => false, 'error' => $e->getMessage(), 'message' => 'Cõ lỗi xảy ra khi xóa mã giảm giá'], 500);
		}
	}
}
