$(document).ready(function () {
	// Global array to track uploaded files
	let uploadedImgs = [];

	// Handle modal load event
	$('#editProductModal').on('editProductDetailModalLoaded', function () {
		uploadedImgs = [];
		uploadedImgs = $('#editProductModal input[name="images[]"]')[0].files;
		uploadedImgs = Array.from(uploadedImgs);
		updateSelectedCategoriesInput();
		console.log('Modal loaded, syncing uploaded images:', uploadedImgs);
	});

	function copyFilesToInput(inputElement) {
		const dataTransfer = new DataTransfer();
		uploadedImgs.forEach(file => {
			dataTransfer.items.add(file);
		});
		inputElement.files = dataTransfer.files;
	}

	// Handle image upload
	$('#editProductModal input[name="images[]"]').on('change', function () {
		console.log('input changed');
		const input = $(this)[0];
		const previewContainer = $('#preview-image-container');
		const imgCounter = $('#imgCounter');

		if (input.files && input.files.length > 0) {
			const newFiles = Array.from(input.files);
			if (uploadedImgs.length + newFiles.length > 5) {
				showAlert('error', 'Bạn chỉ có thể tải lên tối đa 5 ảnh.');
				return;
			}

			uploadedImgs = [...uploadedImgs, ...newFiles];
			copyFilesToInput(input);

			// Preview new files
			newFiles.forEach(file => {
				let reader = new FileReader();
				reader.onload = function (e) {
					const imgHtml = `
						<div class="position-relative p-2">
							<img src="${e.target.result}" class="preview-image">
							<button type="button" class="btn-close position-absolute top-0 end-0"
									data-index="${uploadedImgs.length - 1}" aria-label="Close"></button>
						</div>
					`;
					previewContainer.children().last().before(imgHtml);
				};
				reader.readAsDataURL(file);
			});

			imgCounter.text(`Hình ảnh sản phẩm (${uploadedImgs.length}/5)`);
			console.log('Uploaded images: ', uploadedImgs);
		}
	});

	// Handle image removal
	$('#preview-image-container').on('click', '.btn-close', function () {
		const index = $(this).data('index');
		// if (!Array.isArray(uploadedImgs)) {
		// 	console.error('uploadedImgs is not an array:', uploadedImgs);
		// 	uploadedImgs = [];
		// }
		console.debug('uploadedImgs before removal:', uploadedImgs);
		uploadedImgs.splice(index, 1);

		$(this).parent().remove();

		const input = $('#editProductModal input[name="images[]"]')[0];
		copyFilesToInput(input);

		$('#imgCounter').text(`Hình ảnh sản phẩm (${uploadedImgs.length}/5)`);

		// Update indices of remaining close buttons
		$('.btn-close').each(function (i) {
			$(this).data('index', i);
		});
	});

	// Handle upload button click
	$('#preview-image-container > div:last-child').on('click', function () {
		$('#editProductModal input[name="images[]"]').click();
	});

	// Reset uploaded images when modal is closed
	$('#editProductModal').on('hidden.bs.modal', function () {
		uploadedImgs = [];
		$('#preview-image-container').children().not(':last').remove();
		$('#imgCounter').text('Hình ảnh sản phẩm (0/5)');
	});

	// Handle preview image click
	$(document).on('click', '.preview-image', function () {
		const imageUrl = $(this).attr('src');
		Swal.fire({
			imageUrl: imageUrl,
			imageAlt: 'Product image preview',
			showConfirmButton: false,
			width: 'auto',
		});
	});

	// Click to move category from available -> selected
	$(document).on('click', '#available-categories .category-item', function () {
		$('#selected-categories').append($(this));
		updateSelectedCategoriesInput();
	});

	// Click to move category from selected -> available
	$(document).on('click', '#selected-categories .category-item', function () {
		$('#available-categories').append($(this));
		updateSelectedCategoriesInput();
	});
});

function updateSelectedCategoriesInput() {
	const selectedIds = [];
	$('#selected-categories .category-item').each(function () {
		selectedIds.push($(this).data('id'));
	});
	$('#editSelectedCategoriesInput').val(selectedIds);
}