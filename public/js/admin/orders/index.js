document.addEventListener('DOMContentLoaded', function () {
	// Existing flatpickr initialization
	flatpickr("#single_date", {
		dateFormat: "Y-m-d"
	});
	flatpickr("#date_start", {
		dateFormat: "Y-m-d"
	});
	flatpickr("#date_end", {
		dateFormat: "Y-m-d"
	});

	// Initialize default filter period btn
	document.querySelector('[data-period="week"]').classList.add('active', 'btn-secondary');

	// Charts: initialize with default period
	updateCharts('week');

	// Open order modal if delegated by other page
	(function openIntendedModal() {
		const orderId = sessionStorage.getItem('modal.orderId');
		if (!orderId) {
			return;
		}
		loadOrderDetailsPopup(orderId, new Event('click'));
		sessionStorage.removeItem('modal.orderId');
	})();

	// Charts: handle periods filter buttons clicks
	document.querySelectorAll('[data-period]').forEach(button => {
		button.addEventListener('click', function () {
			const period = this.dataset.period;
			updateCharts(period);

			// Update active button state
			document.querySelectorAll('[data-period]').forEach(btn => {
				btn.classList.remove('active', 'btn-secondary');
				btn.classList.add('btn-outline-secondary');
			});
			this.classList.remove('btn-outline-secondary');
			this.classList.add('active', 'btn-secondary');
		});
	});

	// Existing date filter type handling
	document.querySelectorAll('input[name="dateFilterType"]').forEach(function (el) {
		el.addEventListener('change', function () {
			if (this.value === 'single') {
				document.getElementById('singleDatePicker').style.display = 'block';
				document.getElementById('rangeDatePicker').style.display = 'none';
			} else {
				document.getElementById('singleDatePicker').style.display = 'none';
				document.getElementById('rangeDatePicker').style.display = 'block';
			}
		});
	});

	// Add sorting functionality
	document.querySelectorAll('.sortable').forEach(function (header) {
		header.addEventListener('click', function () {
			const sort = this.dataset.sort;
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
	});

	document.querySelectorAll('.view-details-btn').forEach(function (element) {
		element.addEventListener('click', function (event) {
			event.stopPropagation();
			const orderId = this.dataset.orderId;
			loadOrderDetailsPopup(orderId, event);
		});
	});

	// Handle editable cells
	document.querySelectorAll('.editable-cell').forEach(function (cell) {
		cell.addEventListener('click', function (event) {
			event.stopPropagation();
			const field = this.dataset.field;
			const orderId = this.dataset.orderId;
			let options = {};

			if (field === 'status') {
				options = statusOptions;
			} else if (field === 'is_paid') {
				options = isPaidOptions;
			}

			if (Object.keys(options).length === 0) return;

			// Create popup
			const popup = document.createElement('div');
			popup.classList.add('popup-menu');

			const list = document.createElement('ul');

			for (const [value, label] of Object.entries(options)) {
				const item = document.createElement('li');
				item.textContent = label;
				item.dataset.value = value;
				item.addEventListener('click', function () {
					updateOrderField(orderId, field, value);
					document.body.removeChild(popup);
				});
				list.appendChild(item);
			}

			popup.appendChild(list);
			document.body.appendChild(popup);

			// Position the popup
			const rect = this.getBoundingClientRect();
			popup.style.top = `${rect.bottom + window.scrollY}px`;
			popup.style.left = `${rect.left + window.scrollX}px`;

			// Close popup when clicking outside
			document.addEventListener('click', function handler(event) {
				if (!popup.contains(event.target)) {
					document.body.removeChild(popup);
					document.removeEventListener('click', handler);
				}
			}, { once: true });
		});
	});

	// Handle expand/collapse of newest orders
	document.querySelectorAll('.newest-order-item .order-header').forEach(function (header) {
		header.addEventListener('click', function (event) {
			// Check if the click originated from the '.order-id.clickable' element
			if (event.target.closest('.order-id.clickable')) {
				// Do nothing if the click is on the order-id
				return;
			}

			const orderId = this.dataset.orderId;
			const details = document.getElementById(`order-details-${orderId}`);
			const toggleIcon = this.querySelector('.toggle-icon');

			if (details.style.display === 'none') {
				details.style.display = 'block';
				toggleIcon.classList.remove('mdi-chevron-down');
				toggleIcon.classList.add('mdi-chevron-up');
			} else {
				details.style.display = 'none';
				toggleIcon.classList.remove('mdi-chevron-up');
				toggleIcon.classList.add('mdi-chevron-down');
			}
		});
	});

	// Ensure the click handler on '.order-id.clickable' stops propagation
	function loadOrderDetailsPopup(orderId, event) {
		if (event) event.stopPropagation();
		const modal = new bootstrap.Modal(document.getElementById('orderDetailsModal'));
		const modalContent = document.getElementById('order-details-content');

		// Show loading indicator
		modalContent.innerHTML = `
				<div class="text-center">
					<div class="spinner-border text-primary" role="status">
						<span class="visually-hidden">Đang tải...</span>
					</div>
				</div>
			`;

		// Show the modal
		modal.show();

		// Fetch order details via AJAX
		fetch(`/admin/orders/${orderId}/details`, {
			headers: {
				'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
			}
		})
			.then(response => response.json())
			.then(data => {
				if (data.success) {
					// Render order details
					const order = data.order;

					let orderDetailsHtml = `
						<p><strong>Mã đơn hàng:</strong> #${order.order_id}</p>
						<p><strong>Khách hàng:</strong> ${order.user.full_name}</p>
						<p><strong>Ngày đặt hàng:</strong> ${new Date(order.created_at).toLocaleString()}</p>
						<p><strong>Tổng tiền:</strong> ${Number(order.total_price).toLocaleString()} ₫</p>
						<p><strong>Phí vận chuyển:</strong> ${Number(order.deliver_cost).toLocaleString()} ₫</p>
					`;

					// Check for voucher
					if (order.voucher) {
						orderDetailsHtml += `
							<p><strong>Mã giảm giá:</strong> ${order.voucher.voucher_name || 'Không có'}&nbsp; - &nbsp;${order.voucher.description || ''}</p>
						`;
					} else {
						orderDetailsHtml += `<p><strong>Mã giảm giá:</strong> Không có</p>`;
					}

					orderDetailsHtml += `
						<p><strong>Ghi chú:</strong> ${order.additional_note || 'Không có'}</p>
						<hr style="opacity: 0.1; color: grey;">
						<h5>Sản phẩm:</h5>
						<div class="order-items">
					`;

					order.order_items.forEach(item => {
						const product = item.product;
						const imageUrl = product.product_images.length > 0 ? product.product_images[0].product_image_url : '/images/placeholder-plant.jpg';
						const originalPrice = Number(product.price).toLocaleString();
						const discountedAmount = Number(item.discounted_amount || 0);
						let itemPriceHtml = '';

						if (discountedAmount > 0) {
							const discountedPrice = (product.price - discountedAmount).toLocaleString();
							itemPriceHtml = `
								<p class="product-quantity-price mb-0">
									Số lượng: ${item.quantity} x 
									<span class="text-decoration-line-through text-muted">${originalPrice} ₫</span> 
									<span class="text-danger">${discountedPrice} ₫</span>
								</p>
							`;
						} else {
							itemPriceHtml = `
								<p class="product-quantity-price mb-0">
									Số lượng: ${item.quantity} x ${originalPrice} ₫
								</p>
							`;
						}

						orderDetailsHtml += `
							<div class="order-item d-flex align-items-center mb-3">
								<img src="${imageUrl}" alt="${product.name}" class="product-image me-3">
								<div>
									<p class="product-name mb-1">${product.short_description}</p>
									${itemPriceHtml}
								</div>
							</div>
						`;
					});

					orderDetailsHtml += `</div>`;

					modalContent.innerHTML = orderDetailsHtml;
				} else {
					modalContent.innerHTML = `<p class="text-danger">Lỗi: ${data.message}</p>`;
				}
			})
			.catch(error => {
				console.error('Error:', error);
				modalContent.innerHTML = `<p class="text-danger">Đã xảy ra lỗi khi tải chi tiết đơn hàng.</p>`;
			});
	}

	document.querySelectorAll('.order-id.clickable').forEach(function (element) {
		element.addEventListener('click', function (event) {
			loadOrderDetailsPopup(this.dataset.orderId, event);
		});
	});
});

function updateCharts(period) {
	fetch(`/admin/orders/statistics?period=${period}`,
		{
			headers: {
				'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
			}
		}
	)
		.then(response => response.json())
		.then(data => {
			console.log('sales data: ', data.salesData);
			updateSalesChart(data.salesData);
			updateOrderStatusChart(data.statusData);
		})
		.catch(error => console.error('Error fetching statistics:', error));
}

function updateSalesChart(data) {
	const ctx = document.getElementById('salesChart').getContext('2d');
	if (window.salesChart && typeof window.salesChart.destroy === 'function')
		window.salesChart.destroy();

	window.salesChart = new Chart(ctx, {
		type: 'line',
		data: {
			labels: data.labels,
			datasets: [{
				label: 'Doanh thu',
				data: data.values,
				borderColor: '#435E53',
				backgroundColor: 'rgba(67, 94, 83, 0.1)',
				fill: true,
				tension: 0.4
			}]
		},
		options: {
			responsive: true,
			plugins: {
				legend: {
					position: 'top',
				},
				title: {
					display: false
				},
				tooltip: {
					callbacks: {
						label: function (context) {
							let label = context.dataset.label || '';
							if (label) {
								label += ': ';
							}
							if (context.parsed.y !== null) {
								label += context.parsed.y.toLocaleString('vi-VN') + '₫';
							}
							return label;
						}
					}
				}
			},
			scales: {
				y: {
					beginAtZero: true,
					ticks: {
						callback: function (value) {
							return value.toLocaleString('vi-VN') + '₫';
						}
					}
				}
			}
		}
	});
}

function updateOrderStatusChart(data) {
	const ctx = document.getElementById('orderStatusChart').getContext('2d');
	if (window.orderStatusChart && typeof window.orderStatusChart.destroy === 'function')
		window.orderStatusChart.destroy();

	window.orderStatusChart = new Chart(ctx, {
		type: 'pie',
		data: {
			labels: ['Đang xử lý', 'Đang giao', 'Đã giao', 'Đã hủy'],
			datasets: [{
				data: data,
				backgroundColor: [
					'#ffc107', // pending - yellow
					'#007bff', // delivering - blue
					'#28a745', // delivered - green
					'#dc3545'  // cancelled - red
				],
				borderColor: '#ffffff',
				borderWidth: 2
			}]
		},
		options: {
			responsive: true,
			plugins: {
				legend: {
					position: 'bottom'
				}
			},
			onClick: (event, elements) => {
				if (elements.length > 0) {
					const index = elements[0].index;
					const status = ['pending', 'delivering', 'delivered', 'cancelled'][index];
					const value = data[index];

					console.log(`Clicked ${status} segment with value ${value}`);

					// Example: Navigate to filtered orders page
					window.location.href = `/admin/orders?status=${status}`;
				}
			}

		}
	});
}

// Handle change status button click
document.querySelectorAll('.change-status-btn').forEach(function (button) {
	button.addEventListener('click', function (event) {
		event.stopPropagation();
		const orderId = this.dataset.orderId;

		Swal.fire({
			title: 'Xác nhận chuyển trạng thái?',
			text: "Đơn hàng sẽ được chuyển sang trạng thái đang giao hàng và khách hàng sẽ nhận được thông báo qua email.",
			icon: 'question',
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
				this.classList.add('btn-secondary');
				updateOrderField(orderId, 'status', 'delivering');
			}
		});
	});
});

function updateOrderField(orderId, field, value) {
	fetch("/admin/orders/update-field", {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json',
			'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
		},
		body: JSON.stringify({
			order_id: orderId,
			field: field,
			value: value
		})
	})
		.then(response => response.json())
		.then(async (data) => {
			if (data.success) {
				// Reload the page or update the cell content
				showAlert('success', data.message);
				await new Promise(r => setTimeout(r, 2000));
				window.location.reload();
			} else {
				console.error('Error:', data.message);
				showAlert('error', data.message);
			}
		})
		.catch(error => {
			console.error('Error:', error);
		});
}
