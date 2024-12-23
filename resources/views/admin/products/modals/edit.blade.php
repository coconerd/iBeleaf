<div class="modal fade vintage-modal" id="editProductModal" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title vintage-title">
					<i class="mdi mdi-package-variant"></i>
					Chỉnh sửa sản phẩm
				</h5>
				<button type="button" class="btn-close vintage-close" data-bs-dismiss="modal"></button>
			</div>
			<div class="modal-body">
				<form id="editProductForm" method="POST" enctype="multipart/form-data">
					@csrf
					@method('PUT')
					<div class="row">
						<div class="col-md-6 mb-3">
							<div class="form-group">
								<label class="form-label vintage-label">Mã sản phẩm</label>
								<input type="text" class="form-control vintage-input inactive-muted-text" id="editCode"
									readonly>
							</div>
						</div>
						<div class="col-md-6 mb-4">
							<div class="form-group">
								<label class="form-label vintage-label">Tên sản phẩm</label>
								<input type="text" class="form-control vintage-input inactive-muted-text" id="editName"
									name="name">
							</div>
						</div>
					</div>

					<div class="mb-4">
						<div class="form-group">
							<label class="form-label vintage-label">Mô tả ngắn</label>
							<textarea class="form-control vintage-textarea inactive-muted-text"
								id="editShortDescription" name="short_description" rows="2"></textarea>
						</div>
					</div>

					<div class="mb-4">
						<div class="form-group">
							<label class="form-label vintage-label">Mô tả chi tiết</label>
							<textarea class="form-control vintage-textarea inactive-muted-text" id="editDescription"
								name="detailed_description" rows="3"></textarea>
						</div>
					</div>

					<div class="row">
						<div class="col-md-4 mb-3">
							<div class="form-group">
								<label class="form-label vintage-label">Giá gốc</label>
								<div class="input-group">
									<input type="number" name="price"
										class="form-control vintage-input inactive-muted-text" id="editPrice">
									<span class="input-group-text vintage-addon ">đ</span>
								</div>
							</div>
						</div>
						<div class="col-md-4 mb-3">
							<div class="form-group">
								<label class="form-label vintage-label">Giảm giá</label>
								<div class="input-group">
									<input type="number" name="discount_percentage"
										class="form-control vintage-input inactive-muted-text" id="editDiscount" min="0"
										max="100">
									<span class="input-group-text vintage-addon">%</span>
								</div>
							</div>
						</div>
						<div class="col-md-4 mb-3">
							<div class="form-group">
								<label class="form-label vintage-label">Số lượng tồn kho</label>
								<input type="number" name="stock_quantity"
									class="form-control vintage-input inactive-muted-text" id="editStock">
							</div>
						</div>
					</div>

					<!-- Add new image management section -->
					<div class="mb-4">
						<div class="form-group">
							<label class="form-label vintage-label" id="imgCounter">Hình ảnh sản phẩm (tối đa 5)</label>
							<input type="file" name="images[]" accept="image/*" multiple
								class="form-control visually-hidden">
							<div class="d-flex flex-wrap border-dashed" id="preview-image-container">
								<!-- Images will be populated here -->
								<!-- Add new image button -->
								<div class="p-2" style="background-color: transparent;">
									<div class="d-flex preview-image justify-content-center align-items-center border border-1 border-dark"
										style="cursor: pointer; max-width: 40px !important;">
										<span class="fs-3">+</span>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="form-group mt-5">
						<label class="form-label vintage-label" style="font-size: 1rem;">Chọn danh mục cho sản phẩm</label>
						<div class="row mt-3">
							<div class="col-md-6">
								<label class="form-label vintage-label">Danh mục sản phẩm có sẵn</label>
								<div id="available-categories" class="border p-2"
									style="max-height:200px; overflow:auto;">
									<!-- Dynamically populated with JS -->
								</div>
							</div>
							<div class="col-md-6">
								<label class="form-label vintage-label">Danh mục sản phẩm đã chọn</label>
								<div id="selected-categories" class="border p-2"
									style="max-height:200px; overflow:auto;">
									<!-- Dynamically populated with JS -->
								</div>
								<input type="hidden" name="categories" id="editSelectedCategoriesInput" />
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-secondary vintage-btn" data-bs-dismiss="modal">
					<i class="mdi mdi-close"></i> Đóng
				</button>
				<button type="submit" class="btn btn-primary vintage-btn-primary" id="saveProductChanges">
					<i class="mdi mdi-content-save"></i> Lưu thay đổi
				</button>
			</div>
		</div>
	</div>
</div>

<link rel="stylesheet" href="{{ asset('css/admin/products/modals/edit.css') }}">
<script src="{{ asset('js/admin/products/modals/edit.js') }}"></script>