<div class="modal vintage-modal fade" id="voucherModal" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title vintage-title">Thêm Mã Giảm Giá Mới</h5>
				<button type="button" class="btn-close vintage-close" data-bs-dismiss="modal"
					aria-label="Close"></button>
			</div>
			<form id="voucherForm" data-action="{{ route('admin.vouchers.store') }}" data-method="POST" novalidate>
				@csrf
				<div class="modal-body">
					<div class="row g-4">
						<div class="col-md-6">
							<div class="form-group">
								<label class="vintage-label">Mã voucher <span class="text-danger">*</span></label>
								<input type="text" class="form-control vintage-input inactive-muted-text"
									id="voucher_name" name="voucher_name" required placeholder="VD: SUMMER2024">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="vintage-label">Loại voucher <span class="text-danger">*</span></label>
								<select class="form-select vintage-select" id="voucher_type" name="voucher_type"
									required>
									<option value="">Chọn loại voucher</option>
									<option value="percentage">Giảm theo phần trăm (%)</option>
									<option value="cash">Giảm theo số tiền (VNĐ)</option>
									<option value="free_shipping">Miễn phí vận chuyển</option>
									<option value="BOGO">Mua 1 tặng 1</option>
								</select>
							</div>
						</div>

						<div class="col-12">
							<div class="form-group">
								<label class="vintage-label">Mô tả</label>
								<textarea class="form-control vintage-textarea inactive-muted-text" id="description"
									name="description" rows="3" placeholder="Mô tả chi tiết về voucher"></textarea>
							</div>
						</div>

						<div class="col-md-4" id="valueGroup">
							<div class="form-group">
								<label class="vintage-label">Giá trị <span class="text-danger">*</span></label>
								<div class="input-group">
									<input type="number" class="form-control vintage-input inactive-muted-text"
										id="value" name="value" min="0" required>
									<span class="input-group-text vintage-addon" id="valueType">%</span>
								</div>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label class="vintage-label">Ngày bắt đầu <span class="text-danger">*</span></label>
									<input type="text" 
										class="form-control vintage-input inactive-muted-text flatpickr" 
										id="start_date" 
										name="start_date" 
										placeholder="Chọn ngày và giờ bắt đầu"
										required>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label class="vintage-label">Ngày kết thúc <span class="text-danger">*</span></label>
									<input type="text"
										class="form-control vintage-input inactive-muted-text flatpickr" 
										id="end_date" 
										name="end_date"
										placeholder="Chọn ngày và giờ kết thúc"
										required>
							</div>
						</div>

						<div class="col-12">
							<div class="form-group">
								<div class="d-flex justify-content-between align-items-center mb-2">
									<label class="vintage-label mb-0">Điều kiện áp dụng</label>
									<button type="button" class="btn btn-sm vintage-btn-outline" id="addRule">
										<i class="fas fa-plus"></i> Thêm điều kiện
									</button>
								</div>
								<div id="rulesContainer">
									<div class="rule-item">
										<div class="input-group">
											<select class="form-select vintage-select" name="rules[0][type]">
												<option value="min_order">Giá trị đơn hàng tối thiểu</option>
												<option value="max_uses">Số lần sử dụng tối đa</option>
												<option value="max_discount">Giảm giá tối đa</option>
											</select>
											<input type="number" class="form-control vintage-input required"
												name="rules[0][value]" min="0" placeholder="Giá trị">
											<button type="button" class="btn vintage-btn-danger remove-rule">
												<i class="fas fa-times"></i>
											</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn vintage-btn-outline" data-bs-dismiss="modal">Hủy</button>
					<button type="button" id="submitBtn" class="btn vintage-btn-primary">Lưu voucher</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script src="{{ asset('js/admin/vouchers/modals/create.js') }}"></script>