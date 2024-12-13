<div class="container-fluid" id="ordersContainer">
	<!-- Tabs Navigation -->
	<ul class="nav nav-tabs mb-4" id="tabHeaders" role="tablist">
		<li class="nav-item" role="presentation">
			<button class="nav-link active allTab" data-bs-toggle="tab" data-order-status="all"
				type="button" role="tab" aria-controls="all-orders" aria-selected="true">Tất cả</button>
		</li>
		<li class="nav-item" role="presentation">
			<button class="nav-link pendingTab" data-bs-toggle="tab" data-order-status="pending" type="button"
				role="tab" aria-controls="shipping" aria-selected="false">Chờ lấy hàng</button>
		</li>
		<li class="nav-item" role="presentation">
			<button class="nav-link deliveringTab" data-bs-toggle="tab" data-order-status="delivering" type="button"
				role="tab" aria-controls="shipping" aria-selected="false">Chờ giao hàng</button>
		</li>
		<li class="nav-item" role="presentation">
			<button class="nav-link completedTab" data-bs-toggle="tab" data-order-status="completed" type="button"
				role="tab" aria-controls="completed" aria-selected="false">Hoàn thành</button>
		</li>
		<li class="nav-item" role="presentation">
			<button class="nav-link cancelledTab" data-bs-toggle="tab" data-order-status="cancelled" type="button"
				role="tab" aria-controls="cancelled" aria-selected="false">Đã hủy</button>
		</li>
		<li class="nav-item" role="presentation">
			<button class="nav-link returnedTab" data-bs-toggle="tab" data-order-status="returned" type="button"
				role="tab" aria-controls="returns" aria-selected="false">Trả hàng/Hoàn tiền</button>
		</li>
	</ul>

	<!-- Tabs Content -->
	<div class="tab-content" id="ordersTabContainer">
		<!-- All Orders -->
		<div class="tab-pane fade show active allTab" role="tabpanel" aria-labelledby="all-orders-tab"
			data-order-status="">
		</div>

		<!-- Other Tabs (Repeat similar structure for other statuses) -->
		<div class="tab-pane fade pendingTab" role="tabpanel" aria-labelledby="pending-payment-tab"
		data-order-status="pending">
			<!-- Pending Payment Orders -->
		</div>
		<div class="tab-pane fade deliveringTab" role="tabpanel" aria-labelledby="shipping-tab"
		data-order-status="delivering">
			<!-- Shipping Orders -->
		</div>
		<div class="tab-pane fade completedTab" role="tabpanel" aria-labelledby="completed-tab"
		data-order-status="completed">
			<!-- Completed Orders -->
		</div>
		<div class="tab-pane fade cancelledTab" role="tabpanel" aria-labelledby="cancelled-tab"
		data-order-status="cancelled">
			<!-- Cancelled Orders -->
		</div>
		<div class="tab-pane fade returnedTab" role="tabpanel" aria-labelledby="returns-tab">
			<!-- Returns/Refund Orders -->
		</div>
	</div>
</div>