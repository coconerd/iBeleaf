$(document).ready(function () {
	// Initialize flatpickr with better timezone handling
	flatpickr(".flatpickr", {
		enableTime: true,
		dateFormat: "d-m-Y H:i",
		time_24hr: true,
		locale: "vn",
		// minDate: "yesterday",
		allowInput: true,
	});

	// Handle add voucher button click
	$('#addVoucherBtn').click(function () {
		$('.modal-title').text('Thêm Mã Giảm Giá');
		const jForm = $('#voucherForm');
		jForm.prop('method', 'POST');
		jForm.prop('action', '/admin/vouchers/store');
	});

	// Handle edit button click
	$('.edit-voucher').click(function () {
		const voucherId = $(this).data('id');
		const jForm = $('#voucherForm');
		$.get(`/admin/vouchers/${voucherId}/details`, function (voucher) {
			$('.modal-title').text('Cập Nhật Mã Giảm Giá');
			jForm.prop('method', 'POST');
			jForm.prop('action', `/admin/vouchers/${voucher.voucher_id}/update`);
			fillVoucherForm(voucher);
		});
	});

	// Handle delete button click
	$('.delete-voucher').click(function () {
		const voucherId = $(this).data('id');
		$.ajax({
			url: `/admin/vouchers/${voucherId}/delete`,
			method: 'POST',
			success: function (response) {
				showAlert('success', response.message);
				setTimeout(() => location.reload(), 1500);
			},
			error: function (xhr) {
				showAlert('error', xhr.message);
			}
		});
	});

	// Handle voucher type change
	$('#voucher_type').on('change', function () {
		const type = $(this).val();
		const valueType = $('#valueType');
		const valueGroup = $('#valueGroup');

		switch (type) {
			case 'percentage':
				valueType.text('%');
				valueGroup.show();
				break;
			case 'cash':
				valueType.text('VNĐ');
				valueGroup.show();
				break;
			case 'free_shipping':
			case 'BOGO':
				valueGroup.hide();
				break;
		}

		$('#value').val('');
	});

	// Handle value input change
	$('#value').on('input', function () {
		const voucherType = $('#voucher_type > option:selected').val();
		switch (voucherType) {
			case 'percentage':
				$(this).attr('max', 100);
				if ($(this).val() > 100) {
					$(this).val(100);
				}
				break;
			case 'cash':
				$(this).removeAttr('max');
				if ($(this).val() < 0) {
					$(this).val(0);
				}
				break;
		}
	});

	// Handle add rule button
	let ruleCount = 1;
	$('#addRule').on('click', function () {
		const newRule = `
            <div class="rule-item mb-2">
                <div class="input-group">
                    <select class="form-select vintage-input" name="rules[${ruleCount}][type]">
                        <option value="min_order">Giá trị đơn hàng tối thiểu</option>
                        <option value="max_uses">Số lần sử dụng tối đa</option>
                        <option value="max_discount">Giảm giá tối đa</option>
                    </select>
                    <input type="number" class="form-control vintage-input" 
                           name="rules[${ruleCount}][value]" placeholder="Giá trị">
                    <button type="button" class="btn vintage-btn-danger remove-rule">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        `;
		$('#rulesContainer').append(newRule);
		ruleCount++;
	});

	// Reset form when modal is hidden
	$('#voucherModal').on('hidden.bs.modal', function () {
		$('#voucherForm')[0].reset();
		$('#rulesContainer').html($('#rulesContainer .rule-item').first());
		ruleCount = 1;
	});

	// Handle form submission
	$('#submitBtn').on('click', function (e) {
		// e.preventDefault(); // Prevent default form submission
		console.log('Submit button clicked');

		Swal.fire({
			title: 'Xác nhận thêm voucher?',
			text: "Voucher sẽ được thêm vào hệ thống.",
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
				console.log('submitting form');
				$('#voucherForm').submit();
			}
		});

	});

	// Handle remove rule button
	$(document).on('click', '.remove-rule', function () {
		$(this).closest('.rule-item').remove();
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

	// Update sort icons on page load
	(function updateSortIcons() {
		const currentSort = new URLSearchParams(window.location.search).get('sort');
		const currentDirection = new URLSearchParams(window.location.search).get('direction');

		$('.sort-icon').each(function () {
			const sort = $(this).data('sort');
			if (sort === currentSort) {
				$(this).removeClass('mdi-arrow-up-down')
					.addClass(currentDirection === 'desc' ? 'mdi-arrow-down' : 'mdi-arrow-up');
			}
		});
	})();

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

	// Handle status filter
	$('#statusFilter').on('change', function() {
		performSearch(); // Reuse existing search function
	});

	// Update initial status filter value
	(function initializeFilters() {
		const status = new URLSearchParams(window.location.search).get('status');
		if (status) {
			$('#statusFilter').val(status);
		}
	})();
});

function fillVoucherForm(voucher) {
	console.log('Filling voucher form:', voucher);
	$('#voucherForm').data('action', `/admin/vouchers/${voucher.voucher_id}`);
	$('#voucherForm').data('method', 'PUT');
	$('#voucher_name').val(voucher.voucher_name);
	$('#voucher_type').val(voucher.voucher_type);
	$('#description').val(voucher.description);
	$('#value').val(voucher.value);

	// Populate voucher rules
	voucher.voucher_rules.forEach((rule, index) => {
		if (index === 0) {
			$('.rule-item').find('select').val(rule.rule_type);
			$('.rule-item').find('input').val(rule.rule_value);
		} else {
			const newRule = `
				<div class="rule-item mb-2">
					<div class="input-group">
						<select class="form-select vintage-input" name="rules[${index}][type]">
							<option value="min_order">Giá trị đơn hàng tối thiểu</option>
							<option value="max_uses">Số lần sử dụng tối đa</option>
							<option value="max_discount">Giảm giá tối đa</option>
						</select>
						<input type="number" class="form-control vintage-input" 
							   name="rules[${index}][value]" placeholder="Giá trị">
						<button type="button" class="btn vintage-btn-danger remove-rule">
							<i class="fas fa-times"></i>
						</button>
					</div>
				</div>
			`;
			$('#rulesContainer').append(newRule);
			$('.rule-item').last().find('select').val(rule.rule_type);
			$('.rule-item').last().find('input').val(rule.rule_value);
		}
	});

	// Update flatpickr instances
	document.querySelector('#start_date')._flatpickr.setDate(voucher.voucher_start_date);
	document.querySelector('#end_date')._flatpickr.setDate(voucher.voucher_end_date);
}

function performSearch() {
	const searchValue = $('#searchInput').val();
	const searchType = $('#searchType').val();
	const status = $('#statusFilter').val();

	const url = new URL(window.location.href);

	if (searchValue) {
		url.searchParams.set('search', searchValue);
		url.searchParams.set('type', searchType);
	} else {
		url.searchParams.delete('search');
		url.searchParams.delete('type');
	}

	if (status) {
		url.searchParams.set('status', status);
	} else {
		url.searchParams.delete('status');
	}

	// Preserve the page parameter if it exists
	const currentPage = new URLSearchParams(window.location.search).get('page');
	if (currentPage) {
		url.searchParams.set('page', 1); // Reset to first page on new search
	}

	window.location.href = url.toString();
}
