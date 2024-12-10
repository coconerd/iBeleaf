/* Xử lý sự kiện khi nhấn vào các tab trong mục 'đơn hàng' (các tab bao gồm 'Tất cả', 'Đang giao', ...) */
$(document).on('ordersTabLoaded', function (e) {
	async function loadOrdersTab(tabId) {
		const div = $(`#${tabId}`);
		if (!div) {
			return;
		}

		const data = {
			_token: $('meta[name="csrf-token"]').attr('content')
		}
		const status = $(`#${tabId}`).attr('data-order-status');
		if (status) {
			data.status = status;
		}
		console.log('Loading orders tab:', tabId, data);

		$.ajax({
			url: '/orders',
			method: 'GET',
			data: data,
			success: async function (response) {
				console.log(response);
				if (!response.html) {
					alert('Không thể tải đơn mua của bạn.');
					return;
				}
				$(`#${tabId}`).html(response.html);
			},
			error: function () {
				alert('Không thể tải đơn mua của bạn.');
			}
		});
	}

	loadOrdersTab('allTab');

	// Add listeners for all tab clicks
	$('#orderTabsContainer > div.tab-pane').each(function () {
		$(this).on('click', function () {
			console.debug('Tab clicked:', $(this));
			const tabId = $(this).attr('id');
			loadOrdersTab(tabId);
		});
	});
});


/*-----------------------------------------------------------------------------------------------------------------------------------------*/