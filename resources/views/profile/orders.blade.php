<div class="container-fluid" id="ordersContainer">
	<!-- Tabs Navigation -->
	<ul class="nav nav-tabs mb-4" id="tabHeaders" role="tablist">
		<li class="nav-item" role="presentation">
			<button class="nav-link active allTab" data-bs-toggle="tab" data-order-status="all"
				type="button" role="tab" aria-controls="all" aria-selected="true">Tất cả</button>
		</li>
		<li class="nav-item" role="presentation">
			<button class="nav-link pendingTab" data-bs-toggle="tab" data-order-status="pending" type="button"
				role="tab" aria-controls="pending" aria-selected="false">Chờ lấy hàng</button>
		</li>
		<li class="nav-item" role="presentation">
			<button class="nav-link deliveringTab" data-bs-toggle="tab" data-order-status="delivering" type="button"
				role="tab" aria-controls="delivering" aria-selected="false">Chờ giao hàng</button>
		</li>
		<li class="nav-item" role="presentation">
			<button class="nav-link deliveredTab" data-bs-toggle="tab" data-order-status="delivered" type="button"
				role="tab" aria-controls="delivered" aria-selected="false">Đã giao</button>
		</li>
		<li class="nav-item" role="presentation">
			<button class="nav-link cancelledTab" data-bs-toggle="tab" data-order-status="cancelled" type="button"
				role="tab" aria-controls="cancelled" aria-selected="false">Đã hủy</button>
		</li>
		<li class="nav-item" role="presentation">
			<button class="nav-link returnedrefundedTab" data-bs-toggle="tab" data-order-status="returned,refunded" type="button"
				role="tab" aria-controls="returnedRefunded" aria-selected="false">Đổi / trả hàng</button>
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
			<!-- Pending Orders -->
		</div>
		<div class="tab-pane fade deliveringTab" role="tabpanel" aria-labelledby="shipping-tab"
		data-order-status="delivering">
			<!-- Shipping Orders -->
		</div>
		<div class="tab-pane fade deliveredTab" role="tabpanel" aria-labelledby="shipping-tab"
		data-order-status="delivering">
			<!-- Shipping Orders -->
		</div>
		<div class="tab-pane fade cancelledTab" role="tabpanel" aria-labelledby="cancelled-tab"
		data-order-status="cancelled">
			<!-- Cancelled Orders -->
		</div>
		<div class="tab-pane fade returnedRefundedTab" role="tabpanel" aria-labelledby="returns-tab"
		data-order-status="returned,refunded">
			<!-- Returns/Refund Orders -->
		</div>
	</div>
</div>