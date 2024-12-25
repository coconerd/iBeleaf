$(document).ready(function () {
	// Add snow effect
	const snowflakes = 50;
	const snowContainer = $('.snow-overlay');

	for (let i = 0; i < snowflakes; i++) {
		const snow = $('<div class="snowflake">❆</div>');
		snow.css({
			'left': `${Math.random() * 100}%`,
			'animation-delay': `${Math.random() * 3}s`,
			'animation-duration': `${Math.random() * 3 + 2}s`
		});
		snowContainer.append(snow);
	}

	// Show success alert on page load if any
	if (sessionStorage.getItem('success')) {
		showAlert('success', sessionStorage.getItem('success'));
		sessionStorage.removeItem('success');
	} else if (sessionStorage.getItem('error')) {
		showAlert('error', sessionStorage.getItem('error'));
		sessionStorage.removeItem('error');
	}

	// Handle filters
	$('#applyFilters').click(function () {
		const category = $('#categoryFilter').val();
		const stock = $('#stockFilter').val();

		const url = new URL(window.location.href);
		if (category != null) url.searchParams.set('category', category);
		if (stock != null) url.searchParams.set('stock', stock);

		window.location.href = url.toString();
	});

	// Handle sorting
	$('.sort-icon').click(function () {
		const sort = $(this).data('sort');
		const currentSort = new URLSearchParams(window.location.search).get('sort');
		const currentDirection = new URLSearchParams(window.location.search).get('direction');

		let newDirection = 'desc';
		if (currentSort === sort) {
			newDirection = currentDirection === 'desc' ? 'asc' : 'desc';
		}

		const url = new URL(window.location.href);
		url.searchParams.set('sort', sort);
		url.searchParams.set('direction', newDirection);

		window.location.href = url.toString();
	});

	// Change sort icon directions based on request
	(function updateSortIcons() {
		const currentSort = new URLSearchParams(window.location.search).get('sort');
		const currentDirection = new URLSearchParams(window.location.search).get('direction');

		$('.sort-icon').each(function () {
			const sort = $(this).data('sort');
			if (sort === currentSort) {
				$(this).removeClass('mdi-arrow-up-down').addClass(currentDirection === 'desc' ? 'mdi-arrow-down' : 'mdi-arrow-up');
			}
		});
	})();

	// Update initial stock filter's value
	(function updateStockFiltersValue() {
		const currentStock = new URLSearchParams(window.location.search).get('stock');
		$(`#stockFilter > option[value="${currentStock}"]`).prop('selected', true);
	})();

	// Handle description editing
	$('.editable-cell').click(function () {
		const productId = $(this).data('productId');
		const currentText = $(this).text().trim();

		Swal.fire({
			title: 'Cập nhật mô tả',
			input: 'textarea',
			inputValue: currentText,
			showCancelButton: true,
			confirmButtonText: 'Cập nhật',
			cancelButtonText: 'Hủy',
			confirmButtonColor: '#435E53',
			customClass: {
				popup: 'edit-description-popup'
			}
		}).then((result) => {
			if (result.isConfirmed) {
				updateProductField(productId, 'detailed_description', result.value);
			}
		});
	});

	// Handle edit product
	$('.edit-product').click(function () {
		const productId = $(this).data('productId');
		console.log('Edit product clicked, id: ', productId);
		showModal('editProductModal');
		loadProductDetails(productId);
	});

	// Handle add product
	$('.add-product').click(function () {
		showModal('editProductModal');
		getAllCategories()
			.then(allCategories => {
				console.log('All categories retrieved: ', allCategories);
				loadCategories([], allCategories);
				$('#editProductForm')
				$('#editCode').val('');
				$('#editName').val('');
				$('#editShortDescription').val('');
				$('#editDescription').val('');
				$('#editPrice').val('');
				$('#editStock').val('');
				$('#editDiscount').val('');
				$('#editStock').empty();
				$('selected-categories').empty();
				$('#preview-image-container').children(':not(:last-child)').remove();
			})
			.catch(error => {
				console.error('Error loading categories:', error);
				showAlert('error', 'Không thể tải danh mục sản phẩm');
			});
	});

	// Handle save product changes after edit in modal
	$('#saveProductChanges').click(function (e) {
		e.preventDefault();
		const form = $('#editProductForm');
		Swal.fire({
			title: 'Xác nhận cập nhật sản phẩm?',
			text: "Sản phẩm sẽ được cập nhật với thông tin mới.",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#435E53',
			cancelButtonColor: '#6c757d',
			confirmButtonText: 'Xác nhận',
			cancelButtonText: 'Hủy',
			customClass: {
				popup: 'status-confirm-popup'
			}
		}).then((result) => {
			if (result.isConfirmed) {
				form.submit();
			}
		});
	});

	// Handle search
	$('#searchButton').click(function () {
		performSearch();
	});

	// Handle enter key in search input
	$('#searchInput').keypress(function (e) {
		if (e.which == 13) { // Enter key
			performSearch();
		}
	});

	// Initialize search input with URL params
	(function initializeSearch() {
		const searchValue = new URLSearchParams(window.location.search).get('search');
		const searchType = new URLSearchParams(window.location.search).get('type');
		if (searchValue) {
			$('#searchInput').val(searchValue);
		}
		if (searchType) {
			$('#searchType').val(searchType);
		}
	})();
});

function updateProductField(productId, field, value) {
	$.ajax({
		url: `/admin/products/${productId}/update-field`,
		method: 'POST',
		data: {
			field: field,
			value: value,
			_token: $('meta[name="csrf-token"]').attr('content')
		},
		success: function (response) {
			if (response.success) {
				showAlert('success', 'Cập nhật thành công');
				setTimeout(() => window.location.reload(), 1500);
			} else {
				showAlert('error', 'Có lỗi xảy ra');
			}
		},
		error: function () {
			showAlert('error', 'Có lỗi xảy ra');
		}
	});
}

function showModal(modalHtmlId) {
	const modal = new bootstrap.Modal(document.getElementById(`${modalHtmlId}`));
	modal.show();
}

async function getAllCategories() {
	return new Promise((resolve, reject) => {
		$.ajax({
			url: '/product/all-categories',
			method: 'GET',
			success: function(response) {
				console.log('All categories: ', response);
				resolve(response);
			},
			error: function(error) {
				console.error('Error fetching categories:', error);
				reject(error);
			}
		});
	});
}

function loadProductDetails(productId) {
	$.ajax({
		url: `/admin/products/${productId}/details`,
		method: 'GET',
		success: function (response) {
			if (response.success) {
				console.log('Product details: ', response.product);
				// Initialize modal using Bootstrap's Modal constructor
				const jModal = $('#editProductModal');
				const product = response.product;
				if (!product) {
					console.error('Product is empty');
					return;
				}

				// Populate modal with basic product details
				jModal.find('#editDescription').val(product.detailed_description);
				jModal.find('#editCode').val(product.code);
				jModal.find('#editName').val(product.name);
				jModal.find('#editShortDescription').val(product.short_description);
				jModal.find('#editPrice').val(product.price);
				jModal.find('#editStock').val(product.stock_quantity);
				jModal.find('#editDiscount').val(product.discount_percentage);

				// Populate product categories
				const productCategoryIds = product.categories.map(cat => cat.category_id);
				// const allCategories = response.allCategories;
				getAllCategories()
					.then(allCategories => {
						loadCategories(productCategoryIds, allCategories);
					})
					.catch(error => {
						console.error('Error loading categories:', error);
						showAlert('error', 'Không thể tải danh mục sản phẩm');
					});

				// Populate modal's image containers
				console.log('Populating images: ', product.product_images);
				const previewContainer = jModal.find('#preview-image-container');
				const inputElement = jModal.find('input[name="images[]"]')[0];
				inputElement.value = '';
				console.log('cleared input value');

				// Display existing images
				for (let i = 0; i < product.product_images.length; i++) {
					const imageUrl = product.product_images[i].product_image_url;
					console.log('imageUrl is: ' + imageUrl);
					const imgHtml = `
						<div class="position-relative p-2">
							<img src="${imageUrl}" class="preview-image">
							<button type="button" class="btn-close position-absolute top-0 end-0" data-index="${i}" aria-label="Close"></button>
						</div>
					`;
					previewContainer.children().last().before(imgHtml);
				}

				// Read existing images into <input>
				const dataTransfer = new DataTransfer();
				let loadedImages = 0;
				const totalImages = previewContainer.find('img').length;

				previewContainer.find('img').each(function () {
					const imgUrl = $(this).attr('src');
					fetch(imgUrl)
						.then(res => res.blob())
						.then(blob => {
							const count = dataTransfer.items.length + 1;
							const file = new File([blob], `image_${count}.jpg`, { type: blob.type });
							dataTransfer.items.add(file);
							console.log('Data transfer files length ', dataTransfer.files.length);

							loadedImages++;
							if (loadedImages === totalImages) {
								inputElement.files = dataTransfer.files;
								console.log('Input files length: ', inputElement.files.length);
								$('#imgCounter').text(`Hình ảnh sản phẩm (${count}/5)`);
								jModal.trigger('editProductDetailModalLoaded');
							}
						});
				});

				console.log('Product images length: ', product.product_images.length);

				// Set the form action URL dynamically
				const form = $('#editProductForm');
				form.attr('action', `/admin/products/${productId}/update`);
			} else {
				showAlert('error', 'Có lỗi xảy ra');
			}
		},
		error: function () {
			showAlert('error', 'Có lỗi xảy ra');
		}
	});
}

function loadCategories(productCategoryIds, allCategories) {
	// Populate selected categories (right column)
	const selectedContainer = $('#selected-categories');
	const availableContainer = $('#available-categories');

	selectedContainer.empty();
	availableContainer.empty();

	allCategories.sort((cat1, cat2) => cat1 < cat2);
	productCategoryIds.sort();
	allCategories.forEach(cat => {
		if (!productCategoryIds.includes(cat.category_id)) {
			availableContainer.append(`<div class="category-item p-1" data-id="${cat.category_id}">${cat.name}</div>`);
		}
	});

	productCategoryIds.forEach(selId => {
		const cat = allCategories.find(c => c.category_id === selId);
		if (cat) {
			selectedContainer.append(`<div class="category-item p-1" data-id="${cat.category_id}">${cat.name}</div>`);
		}
	});
}

function performSearch() {
	const searchValue = $('#searchInput').val();
	const searchType = $('#searchType').val();

	const url = new URL(window.location.href);

	if (searchValue) {
		url.searchParams.set('search', searchValue);
		url.searchParams.set('type', searchType);
	} else {
		url.searchParams.delete('search');
		url.searchParams.delete('type');
	}

	window.location.href = url.toString();
}
