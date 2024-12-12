/* Xử lý sự kiện khi nhấn vào các tab trong mục 'đơn hàng' (các tab bao gồm 'Tất cả', 'Đang giao', ...) */
$(document).on('ordersTabLoaded', function (e) {
	async function loadOrdersTab(tabClass) {
		const tabPane = $(`.tab-pane.${tabClass}`);
		if (!tabPane) {
			return;
		}
		const token = $('meta[name="csrf-token"]').attr('content');
		const status = tabPane.attr('data-order-status');
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
			tabClass = $(this).attr('data-order-status') + 'Tab';
			console.debug('Tab class: ' + tabClass);
			loadOrdersTab(tabClass);
		})
	})
});


/*-----------------------------------------------------------------------------------------------------------------------------------------*/