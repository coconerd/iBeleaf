/* Xử lý sự kiện khi nhấn vào các tab trong mục 'đơn hàng' (các tab bao gồm 'Tất cả', 'Đang giao', ...) */
$(document).on('ordersTabLoaded', function (e) {
	async function loadOrdersTab(tabClass) {
		const tabPane = $(`.tab-pane.${tabClass}`);
		if (!tabPane) return;

		// Show loading state immediately
		tabPane.addClass('active show').siblings().removeClass('active show');
		tabPane.html('<div class="text-center py-4"><div class="spinner-border text-secondary" role="status"></div></div>');

		const token = $('meta[name="csrf-token"]').attr('content');
		let status = tabPane.attr('data-order-status');
		let url = '/orders' + '?token=' + token + (status ? '&status=' + encodeURIComponent(status) : '');

		try {
			const response = await $.ajax({
				url: url,
				method: 'GET'
			});

			if (!response.html) {
				throw new Error('No HTML content received');
			}

			// Immediately replace content without animation delay
			tabPane.html(response.html);

			// Add a subtle fade-in effect only to the new content
			tabPane.find('.order-card').css({
				opacity: 0,
				transform: 'translateY(10px)'
			}).each(function (index) {
				$(this).delay(index * 30).animate({
					opacity: 1,
					transform: 'translateY(0)'
				}, 200);
			});

		} catch (error) {
			tabPane.html('<div class="alert alert-danger">Không thể tải đơn mua của bạn.</div>');
		}
	}

	// Initial load
	loadOrdersTab('allTab');

	// Tab click handlers
	$('button.nav-link').on('click', function () {
		const tabClass = $(this).attr('aria-controls') + 'Tab';
		loadOrdersTab(tabClass);
	});

	// Handle order cancel button click
	$(document).on('click', '#cancelBtn', function (e) {
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
				success: function (response) {
					if (response.success) {
						// Reload current tab to show updated order status
						const currentTab = $('.nav-link.active').attr('aria-controls') + 'Tab';
						loadOrdersTab(currentTab);
					} else {
						alert(response.message);
					}
				},
				error: function (xhr) {
					alert(xhr.responseJSON?.message || 'Có lỗi xảy ra khi hủy đơn hàng');
				}
			});
		}
	});
});


/*-----------------------------------------------------------------------------------------------------------------------------------------*/