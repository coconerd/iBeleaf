document.addEventListener('DOMContentLoaded', function () {
	// Initialize charts
	updateCharts('week');

	// Initialize default filter period btn
	document.querySelector('[data-period="week"]').classList.add('active', 'btn-secondary');

	// Open claim details modal if delegated by other pages
	(function openIntendedModal() {
		const requestId = sessionStorage.getItem('modal.return_refund_id');
		console.log('Intended request ID:', requestId);
		if (!requestId) {
			return;
		}

		loadRequestDetails(requestId);
		sessionStorage.removeItem('modal.return_refund_id');
	})();

	// Handle period button clicks
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


	// Handle view details button click
	document.querySelectorAll('.view-details-btn').forEach(function (button) {
		button.addEventListener('click', function () {
			const requestId = this.dataset.requestId;
			loadRequestDetails(requestId);
		});
	});

	// Handle accept button click
	document.querySelector('.accept-btn').addEventListener('click', function () {
		const requestId = this.dataset.requestId;
		confirmAcceptRequest(requestId, () => {
			$('.accept-btn').prop('disabled', true);
			$('.reject-btn').prop('disabled', true);
			updateRequestStatus(requestId, 'accepted', () => {
				$('.accept-btn').prop('disabled', false);
				$('.reject-btn').prop('disabled', false);
			});
		});
	});

	// Handle reject button click
	document.querySelector('.reject-btn').addEventListener('click', function () {
		const requestId = this.dataset.requestId;
		rejectRequest(requestId);
	});

	// Handle view order details button click
	document.querySelectorAll('.order-id').forEach(function (button) {
		button.addEventListener('click', function (event) {
			console.log('clicked');
			event.stopPropagation();
			const orderId = this.dataset.orderId;
			loadOrderDetailsModal(orderId, event);
		})
	})

	// Handle processed row clicks
	document.querySelectorAll('.processed-row').forEach(function (row) {
		row.addEventListener('click', function () {
			const requestId = this.dataset.requestId;
			loadRequestDetails(requestId);
		});
	});

	// Handle sort icons click
	document.querySelectorAll('.sort-icon').forEach(icon => {
		// Set initial state based on URL parameters
		const urlParams = new URLSearchParams(window.location.search);
		const currentType = urlParams.get('type');
		const currentDirection = urlParams.get('direction');

		if (currentType === icon.dataset.type) {
			icon.classList.remove('mdi-arrow-up', 'mdi-arrow-down');
			icon.classList.add(currentDirection === 'asc' ? 'mdi-arrow-up' : 'mdi-arrow-down');
		}

		icon.addEventListener('click', function () {
			const type = this.dataset.type;
			const isAsc = this.classList.contains('mdi-arrow-up');
			const direction = isAsc ? 'desc' : 'asc';

			// Update icon
			this.classList.remove(isAsc ? 'mdi-arrow-up' : 'mdi-arrow-down');
			this.classList.add(isAsc ? 'mdi-arrow-down' : 'mdi-arrow-up');

			// Reload page with sort parameters
			window.location.href = `${window.location.pathname}?type=${type}&sort=created_at&direction=${direction}`;
		});
	});

	const quickAcceptBtn = document.querySelector('.quick-accept-btn');
	if (quickAcceptBtn) {
		quickAcceptBtn.addEventListener('click', function () {
			const requestId = this.dataset.requestId;
			confirmAcceptRequest(requestId, () => {
				updateRequestStatus(requestId, 'accepted');
			});
		});
	}

	// Status options for editable cells
	const statusOptionLevels = {
		1: {
			'pending': 'Đang chờ xử lý',
			'accepted': 'Đã chấp nhận',
			'rejected': 'Đã từ chối',
		},
		2: {
			'pending': 'Đang chờ xử lý',
			'accepted': 'Đã chấp nhận',
			'rejected': 'Đã từ chối',
			'received': 'Đã nhận hàng'
		},
		3: {
			'pending': 'Đang chờ xử lý',
			'accepted': 'Đã chấp nhận',
			'rejected': 'Đã từ chối',
			'received': 'Đã nhận hàng từ khách',
			'refunded': 'Đã hoàn tiền',
			'renewed': 'Đã trả hàng',
		}
	};

	// Handle editable cell clicks
	document.querySelectorAll('.editable-cell').forEach(cell => {
		cell.addEventListener('click', function (e) {
			e.stopPropagation();
			const requestId = this.dataset.requestId;
			const field = this.dataset.field;

			// Create and show the popup
			const popup = document.createElement('div');
			popup.className = 'edit-popup';

			let popupContent = '<div class="edit-popup-content">';
			const statusLevel = this.dataset.statusLevel;

			if (field == 'status') {
				Object.entries(statusOptionLevels[statusLevel])
					.forEach(([value, label]) => {
						if (value == this.dataset.status) {
							return;
						}
						popupContent += `<div class="edit-option" data-field=${field} data-value="${value}">${label}</div> `;
					});
				popupContent += '</div>';
				popup.innerHTML = popupContent;
			}

			// Position the popup
			const rect = this.getBoundingClientRect();
			popup.style.top = rect.bottom + window.scrollY + 5 + 'px';
			popup.style.left = rect.left + window.scrollX + 'px';

			// Add popup to document
			document.body.appendChild(popup);

			// Handle option selection
			popup.querySelectorAll('.edit-option').forEach(option => {
				option.addEventListener('click', function () {
					switch (this.dataset.field) {
						case 'status':
							const newValue = this.dataset.value;
							if (newValue == 'accepted') {
								confirmAcceptRequest(requestId, () => {
									updateRequestStatus(requestId, newValue);
								});
							} else if (newValue == 'rejected') {
								rejectRequest(requestId);
							}
							break;
						default:
							break;
					}
				});
			});

			// Close popup when clicking outside
			document.addEventListener('click', function closePopup(e) {
				if (!popup.contains(e.target) && !cell.contains(e.target)) {
					popup.remove();
					document.removeEventListener('click', closePopup);
				}
			});
		});
	});
});

function loadRequestDetails(requestId) {
	const modal = new bootstrap.Modal(document.getElementById('requestDetailsModal'));
	const modalContent = document.getElementById('request-details-content');

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

	// Fetch request details via AJAX
	fetch(`/admin/claims/${requestId}/details`, {
		headers: {
			'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
		}
	})
		.then(response => response.json())
		.then(data => {
			if (data.success) {
				// Render request details
				const request = data.request;
				const reasonMap = {
					'wrong_item': 'Giao sai sản phẩm',
					'damaged': 'Sản phẩm bị hư hỏng',
					'not_as_described': 'Sản phẩm không như mô tả',
					'quality_issue': 'Vấn đề chất lượng',
					'change_mind': 'Đổi ý',
					'other': 'Lý do khác'
				};

				let detailsHtml = `
                    <div class="request-details">
                        <div class="request-header mb-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-0">Mã yêu cầu #${request.return_refund_id}</h5>
                                    <small class="text-muted">Mã đơn: <span class="order-id text-decoration-underline" role="button" data-order-id="${request.order_item.order_id}">#${request.order_item.order_id}</span></small>
                                </div>
                                <span class="badge badge-${request.status}">
                                    ${request.status === 'pending' ? 'Đang xử lý' :
						request.status === 'accepted' ? 'Đã chấp nhận' :
							request.status === 'rejected' ? 'Đã từ chối' : 'Đã nhận hàng'}
                                </span>
                            </div>
                            <small class="text-muted">${new Date(request.created_at).toLocaleString()}</small>
                        </div>

                        <div class="customer-info mb-4">
                            <h6 class="section-title">Thông tin khách hàng</h6>
                            <div class="info-card">
                                <p><i class="mdi mdi-account me-2"></i>${request.user.full_name}</p>
                                <p><i class="mdi mdi-email me-2"></i>${request.user.email}</p>
                                <p class="text-decoration-underline"><i class="mdi mdi-phone me-2"></i>${request.user.phone_number}</p>
                                <p><i class="mdi mdi-city me-2"></i>${request.user.province_city}</p>
                            </div>
                        </div>

                        <div class="product-info mb-4">
                            <h6 class="section-title">Thông tin sản phẩm</h6>
                            <div class="info-card product-card d-flex align-items-center">
                                <img src="${request.order_item.product.product_images[0]?.product_image_url || '/images/placeholder-plant.jpg'}" 
                                     class="product-thumbnail me-3" alt="Product image">
                                <div>
                                    <a href="/product/${request.order_item.product.product_id}" class="text-decoration-none text-dark">
                                        <h6 class="mb-1">${request.order_item.product.short_description}</h6>
                                    </a>
                                    <p class="mb-0 text-muted">Số lượng yêu cầu đổi/trả: ${request.quantity}</p>
                                    <p class="mb-0 text-muted">${request.quantity} x ${request.order_item.total_price / request.quantity} = ${request.order_item.total_price}₫</p>
                                </div>
                            </div>
                        </div>

                        <div class="reason-info mb-4">
                            <h6 class="section-title">Lý do yêu cầu</h6>
                            <div class="info-card">
                                <p class="reason-tag mb-2">
                                    <span class="badge bg-secondary">${reasonMap[request.reason_tag] || request.reason_tag}</span>
                                </p>
                                <p class="reason-description">${request.reason_description}</p>
                            </div>
                        </div>

                        <div class="images-info">
                            <h6 class="section-title">Hình ảnh đính kèm</h6>
                            <div class="info-card">
                                <div class="image-gallery">
                `;

				request.refund_return_images.forEach(image => {
					detailsHtml += `
                        <div class="image-item">
							<img src="data:image/jpeg;base64,${image.refund_return_image}" alt="Ảnh đính kèm" class="refund-image" alt="Refund Image">
                        </div>
                    `;
				});

				detailsHtml += `
                                </div>
                            </div>
                        </div>
                    </div>
                `;

				// Set modal content
				modalContent.innerHTML = detailsHtml;

				// Update accept and reject buttons' data-request-id
				document.querySelector('.accept-btn').dataset.requestId = request.return_refund_id;
				document.querySelector('.reject-btn').dataset.requestId = request.return_refund_id;

				// Add image preview functionality
				document.querySelectorAll('.refund-image').forEach(img => {
					img.addEventListener('click', function () {
						Swal.fire({
							imageUrl: this.src,
							imageAlt: 'Request Image',
							showConfirmButton: false,
							width: 'auto'
						});
					});
					// Add click handler for the order ID in the request details modal
					modalContent.addEventListener('click', async function (e) {
						if (e.target.classList.contains('order-id')) {
							const orderId = e.target.dataset.orderId;
							const requestDetailsModal = bootstrap.Modal.getInstance(document.getElementById('requestDetailsModal'));

							// Hide modal and wait for animation
							requestDetailsModal.hide();
							await new Promise(resolve => setTimeout(resolve, 200));

							// Remove modal backdrop manually if it exists
							const backdrop = document.querySelector('.modal-backdrop');
							if (backdrop) {
								backdrop.remove();
							}

							// Reset body classes
							document.body.classList.remove('modal-open');
							document.body.style.overflow = '';
							document.body.style.paddingRight = '';

							// Load order details modal
							loadOrderDetailsModal(orderId, e);
						}
					});
				});

			} else {
				modalContent.innerHTML = `<p class="text-danger">Lỗi: ${data.message}</p>`;
			}
		})
		.catch(error => {
			console.error('Error:', error);
			modalContent.innerHTML = `<p class="text-danger">Đã xảy ra lỗi khi tải chi tiết yêu cầu.</p>`;
		});
}

function loadOrderDetailsModal(orderId, event) {
	event.stopPropagation();
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


// Replace simple alerts with SweetAlert2
function showAlert(type, message) {
	const Toast = Swal.mixin({
		toast: true,
		position: 'top-end',
		showConfirmButton: false,
		timer: 3000,
		timerProgressBar: true
	});

	Toast.fire({
		icon: type,
		title: message
	});
}

function updateRequestStatus(requestId, status, callbackFn) {
	// Send AJAX request to update status
	console.log('Updating status to:', status);
	fetch('/admin/claims/update-status', {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json',
			'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
		},
		body: JSON.stringify({
			request_id: requestId,
			status: status
		})
	})
		.then(response => response.json())
		.then(data => {
			if (data.success) {
				showAlert('success', 'Cập nhật trạng thái thành công.');
				setTimeout(() => window.location.reload(), 3000);
			} else {
				showAlert('error', 'Có lỗi xảy ra khi cập nhật trạng thái.');
			}
		})
		.catch(error => {
			console.error('Error:', error);
			showAlert('error', 'Có lỗi xảy ra khi cập nhật trạng thái.');
		}).then(callbackFn);
}

// Create snowflakes
(function createSnowflakes() {
	const snowflakesCount = 30;
	const symbols = ['❄', '❅', '❆'];
	const container = document.querySelector('.content-wrapper');

	for (let i = 0; i < snowflakesCount; i++) {
		const snowflake = document.createElement('div');
		snowflake.className = 'snowflake';
		snowflake.style.left = `${Math.random() * 100}%`;
		snowflake.style.animationDuration = `${Math.random() * 3 + 2}s`;
		snowflake.style.opacity = Math.random();
		snowflake.innerHTML = symbols[Math.floor(Math.random() * symbols.length)];
		container.appendChild(snowflake);
	}
})();

function confirmAcceptRequest(requestId, onConfirm, onAbort) {
	Swal.fire({
		title: 'Xác nhận phê duyệt yêu cầu đổi trả hàng?',
		text: "Phiếu đổi trả sẽ được chuyển sang trạng thái đã đồng ý và khách hàng sẽ được thông báo qua email.",
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
			onConfirm(requestId);
		} else {
			onAbort(requestId);
		}
	});
}

function rejectRequest(requestId, callbackFn) {
	// Close the details modal first
	const detailsModal = bootstrap.Modal.getInstance(document.getElementById('requestDetailsModal'));
	detailsModal.hide();

	// Wait for modal to finish closing animation
	setTimeout(() => {
		Swal.fire({
			title: 'Từ chối yêu cầu đổi/trả hàng',
			input: 'textarea',
			inputLabel: 'Thông báo kèm lý do từ chối yêu cầu sẽ được gửi đến khách hàng qua email',
			inputPlaceholder: 'Nhập lý do từ chối...',
			showCancelButton: true,
			confirmButtonColor: '#435E53',
			cancelButtonColor: '#6c757d',
			confirmButtonText: 'Xác nhận',
			cancelButtonText: 'Hủy',
			allowOutsideClick: false,
			allowEscapeKey: true,
			inputAttributes: {
				autocomplete: 'off'
			},
			didOpen: () => {
				const textarea = Swal.getInput();
				textarea.focus();
			},
			inputValidator: (value) => {
				if (!value) {
					return 'Vui lòng nhập lý do từ chối!';
				}
			}
		}).then((result) => {
			if (result.isConfirmed) {
				// Send AJAX request to update status and save reject reason
				fetch('/admin/claims/update-status', {
					method: 'POST',
					headers: {
						'Content-Type': 'application/json',
						'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
					},
					body: JSON.stringify({
						request_id: requestId,
						status: 'rejected',
						reject_reason: result.value
					})
				})
					.then(response => response.json())
					.then(data => {
						if (data.success) {
							showAlert('success', 'Đã từ chối yêu cầu.');
							setTimeout(() => window.location.reload(), 1500);
						} else {
							showAlert('error', 'Có lỗi xảy ra khi từ chối yêu cầu.');
						}
					})
					.catch(error => {
						console.error('Error:', error);
						showAlert('error', 'Có lỗi xảy ra khi từ chối yêu cầu.');
					}).then(callbackFn);
			}
		});
	}, 200); // Wait for modal close animation
}

function updateCharts(period) {
	console.log('updated charts called');
	fetch(`/admin/claims/statistics?period=${period}`)
		.then(response => response.json())
		.then(data => {
			console.log('Statistics data:', data);
			updateStatusChart(data.statusStats);
			updateProductsChart(data.productStats);
		})
		.catch(error => console.error('Error fetching statistics:', error));
}

function updateStatusChart(data) {
	const ctx = document.getElementById('statusChart').getContext('2d');
	if (window.statusChart && typeof window.statusChart.destroy === 'function')
		window.statusChart.destroy();

	window.statusChart = new Chart(ctx, {
		type: 'line',
		data: {
			labels: data.labels,
			datasets: [
				{
					label: 'Đã chấp nhận',
					data: data.accepted,
					borderColor: '#18A04A',
					tension: 0.4
				},
				{
					label: 'Đã từ chối',
					data: data.rejected,
					borderColor: '#D42426',
					tension: 0.4
				},
				{
					label: 'Đã hoàn thành',
					data: data.completed,
					borderColor: '#435E53',
					tension: 0.4
				}
			]
		},
		options: {
			responsive: true,
			scales: {
				y: {
					beginAtZero: true,
					ticks: {
						stepSize: 1
					}
				}
			}
		}
	});
}

function updateProductsChart(data) {
	const ctx = document.getElementById('productsChart').getContext('2d');
	if (window.productsChart && typeof window.productsChart.destroy === 'function')
		window.productsChart.destroy();

	window.productsChart = new Chart(ctx, {
		type: 'bar',
		data: {
			labels: data.labels,
			datasets: [{
				label: 'Số lượt yêu cầu',
				data: data.values,
				backgroundColor: '#435E53',
			}]
		},
		options: {
			responsive: true,
			scales: {
				y: {
					beginAtZero: true,
					ticks: {
						stepSize: 1
					}
				}
			}
		}
	});
}