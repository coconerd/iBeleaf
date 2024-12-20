$(document).ready(function () {
	// Handle refund/return button click
	$('.refundReturnBtn').on('click', function () {
		console.log('refundReturnBtn clicked');
		let orderId = $(this).closest('.card').data('order-id');
		console.log('order id is: ' + orderId);
		// const modal = $(this).closest('.refundReturnModal');
		const modal = $(`#refundReturnModal`);
		console.log('found modal: ', modal);
		modal.find('.refundReturnOrderId').val(orderId);
		$('.modalOrderId').val(orderId);

		let claims = [];
		// Get orders's claims status
		$.ajax({
			url: `/orders/${orderId}/claims`,
			method: 'GET',
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			success: function (response) {
				claims = response.claims;
				console.log('claims: ', claims);
				modal.find('.refundReturnItemsSelect').find('option').each(function () {
					const orderItemsId = $(this).val();
					const claim = claims.find(claim => (
						claim.order_items_id === Number(orderItemsId)
					));
					if (!!claim && claim.isClaimable === false) {
						// $(this).prop('disabled', true);
						$(this).text($(this).text() + ' - ' + claim.reason);
						$(this).css('color', 'orange');
						$(this).css('font-style', 'italic');
						$(this).css('opacity', '0.7');
					}
				});
			},
			error: function (err) {
				console.log('Error fetching claims: ', err);
				console.error('error: ', err);
			}
		});

		// Populate the items select box
		let itemsOptions = '';
		$(this).closest('.card').find('.product-item').each(function () {
			const orderItemsId = $(this).data('order-items-id');
			const productName = $(this).find('.product-short-description').text().trim();
			const productImgUrl = $(this).find('.product-image-thumbnail').attr('src');
			const purchaseQuantity = $(this).find('.purchaseQuantity').text().trim().replace('x ', '');

			itemsOptions += `
				<option data-img-url="${productImgUrl} " 
					data-purchase-quantity="${purchaseQuantity}"
					value="${orderItemsId}">
						${productName} (số lượng: ${purchaseQuantity})
				</option>`;
		});
		modal.find('.refundReturnItemsSelect').html(itemsOptions);

		// Clear previous selected items list
		modal.find('#refundReturnItemsList').html('');

		modal.modal('show').appendTo('body');
	});

	// Handle selection of items in refund/return modal
	$('.refundReturnItemsSelect').on('change', function () {
		let selectedOptions = $(this).find('option:selected');
		let itemList = '';
		selectedOptions.each(function () {
			let orderItemsId = $(this).val();
			let productName = $(this).text();
			let quantity = 1; // Default quantity
			let productImgUrl = $(this).data('img-url');
			let purchaseQuantity = $(this).data('purchase-quantity');
			itemList += `
				<div class="refund-item" data-order-items-id="${orderItemsId}">
					<div class="d-flex align-items-center mb-2">
						<input type="hidden" name="items[${orderItemsId}][order_items_id]" value="${orderItemsId}">
						<img src="${productImgUrl}" class="me-3" style="width: 60px; height: auto; border-radius: 4px;">
						<div>
							<p class="product-name mb-0">${productName}</p>
							<div class="input-group quantity-input-group borderless" style="width: 150px;">
								<button type="button" class="me-1 btn btn-sm decrease-quantity rounded">-</button>
								<input type="number" name="items[${orderItemsId}][quantity]" class="form-control form-control-sm rounded quantity-input" value="${quantity}" min="1" max="${purchaseQuantity}" 
									style="-webkit-appearance: none; -moz-appearance: textfield; width: 1%; border: none; background-color: #f2f3f4">
								<button type="button" class="ms-1 btn btn-sm increase-quantity rounded">+</button>
							</div>
						</div>
						<button type="button" class="btn btn-md ms-auto remove-item">x</button>
					</div>
				</div>
			`;
		});

		const modal = $(this).closest('.modal');
		console.log('modal is: ', modal);
		modal.find('.refundReturnItemsList').html(itemList);

		// Add listeners for input quantity change
		modal.find('.refundReturnItemsList').find('.quantity-input').each(function () {
			console.log('adding listener for quantity input');
			$(this).on('change', function () {
				const modal = $(this).closest('.modal');
				let selectedItemsQuantity = 0;
				modal.find('.refund-item').each(function () {
					selectedItemsQuantity += parseInt($(this).find('.quantity-input').val());
				})
				const imgInput = modal.find('input[name="images[]"]');
				const inputElem = imgInput.get(0);
				const imgCounter = modal.find('#refundReturnImgCounter');
				console.log('The fuck')
				if (inputElem.files.length > 5 * selectedItemsQuantity) {
					alert('Bạn chỉ có thể chọn tối đa 5 ảnh cho mỗi sản phẩm.');
					imgInput.val('');
					imgCounter.text('Tải ảnh lên (tối đa 5 ảnh/mỗi sản phẩm đã mua)')
					modal.find('#refundReturnPreviewContainer').empty();
				} else {
					console.log('length of inputElem files: ' + inputElem.files.length);
					imgCounter.text(`Tải ảnh lên (${inputElem.files.length}/${5 * selectedItemsQuantity})`);
				}
			})
		});

		// Update the refundReturnImgCounter text
		let selectedItemsQuantity = 0;
		modal.find('.refund-item').each(function () {
			selectedItemsQuantity += parseInt($(this).find('.quantity-input').val());
		});
		const imgCounter = modal.find('#refundReturnImgCounter');
		const imgInput = modal.find('input[name="images[]"]');
		const inputElem = imgInput.get(0);
		imgCounter.text(`Tải ảnh lên (${inputElem.files ? inputElem.files.length : 0}/${5 * selectedItemsQuantity})`);
	});

	// Increase quantity of refund/return items
	$(document).off('click', '.increase-quantity').on('click', '.increase-quantity', function () {
		let input = $(this).siblings('.quantity-input');
		const currVal = parseInt(input.val());
		const maxVal = parseInt(input.attr('max'));
		console.log('current value is: ', currVal);
		if (currVal < maxVal) {
			input.val(currVal + 1);
			input.trigger('change');
		}
	});

	// Decrease quantity
	$(document).on('click', '.decrease-quantity', function () {
		let input = $(this).siblings('.quantity-input');
		const currVal = parseInt(input.val());
		console.log('current value is: ', currVal);
		if (parseInt(input.val()) > 1) {
			input.val(currVal - 1);
			input.trigger('change');
		}
	});

	$(document).on('change', '.quantity-input', function () {
		console.log('Quantity input changed');
		const modal = $(this).closest('.modal');
		let selectedItemsQuantity = 0;
		modal.find('.refund-item').each(function () {
			selectedItemsQuantity += parseInt($(this).find('.quantity-input').val());
		});
		const imgInput = modal.find('input[name="images[]"]');
		const inputElem = imgInput.get(0);
		const imgCounter = modal.find('.refundReturnImgCounter');
		if (inputElem.files.length > 5 * selectedItemsQuantity) {
			alert('Bạn chỉ có thể chọn tối đa 5 ảnh cho mỗi sản phẩm.');
			imgInput.val('');
			imgCounter.text('Tải ảnh lên (tối đa 5 ảnh/mỗi sản phẩm đã mua)');
		} else {
			imgCounter.text(`Tải ảnh lên (${inputElem.files.length}/${5 * selectedItemsQuantity})`);
		}
	});



	// Remove item from list
	$(document).on('click', '.remove-item', function () {
		let orderItemsId = $(this).closest('.refund-item').data('order-items-id');
		$(this).closest('.refund-item').remove();
		$('.refundReturnItemsSelect option[value="' + orderItemsId + '"]').prop('selected', false);
	});
})