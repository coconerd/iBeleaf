/* Xử lý sự kiện khi nhấn vào các tab trong mục 'đơn hàng' (các tab bao gồm 'Tất cả', 'Đang giao', ...) */
$(document).on('ordersTabLoaded', function (e) {
	async function loadOrdersTab(tabClass) {
		const tabPane = $(`.tab-pane.${tabClass}`);
		if (!tabPane) {
			return;
		}
		const token = $('meta[name="csrf-token"]').attr('content');
		let status = tabPane.attr('data-order-status');
		if (status) {
			status = encodeURIComponent(status);
		}

		console.log('status = ', status);

		let url = '/orders' + '?token=' + token;
		if (status) {
			url += '&status=' + status;
		}
		console.log(`Loading orders tab for class '${tabClass}':`, tabClass);
		console.log('URL:', url);

		$.ajax({
			url: url,
			method: 'GET',
			success: async function (response) {
				console.log(response);
				if (response.html == null) {
					alert('Không thể tải đơn mua của bạn.');
					return;
				}
				tabPane.html(response.html);
				// Activate the tab pane
				tabPane.addClass('active show').siblings().removeClass('active show');
			},
			error: function () {
				alert('Không thể tải đơn mua của bạn.');
			}
		});
	}

	loadOrdersTab('allTab');

	// Add listeners for all tab clicksThe problem is that
	$('button.nav-link').each(function () {
		$(this).on('click', function () {
			tabClass = $(this).attr('aria-controls') + 'Tab';
			console.debug('Tab class: ' + tabClass);
			loadOrdersTab(tabClass);
		})
	})

	// Handle order cancel button click
	$(document).on('click', '#cancelBtn', function(e) {
		e.preventDefault();
		
		if (confirm('Bạn có chắc chắn muốn hủy đơn hàng này không?')) {
			const orderId = $(this).closest('.card').data('orderId');
			const token = $('meta[name="csrf-token"]').attr('content');
			
			$.ajax({
				url: `/orders/cancel/${orderId}`,
				method: 'POST',
				headers: {
					'X-CSRF-TOKEN': token
				},
				success: function(response) {
					if (response.success) {
						// Reload current tab to show updated order status
						const currentTab = $('.nav-link.active').attr('aria-controls') + 'Tab';
						loadOrdersTab(currentTab);
					} else {
						alert(response.message);
					}
				},
				error: function(xhr) {
					alert(xhr.responseJSON?.message || 'Có lỗi xảy ra khi hủy đơn hàng');
				}
			});
		}
	});
});


/*-----------------------------------------------------------------------------------------------------------------------------------------*/