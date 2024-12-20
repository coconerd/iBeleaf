<div class="container-fluid" id="ordersContainer">
	<!-- Tabs Navigation -->
	<ul class="nav nav-tabs mb-4" id="tabHeaders" role="tablist">
		<li class="nav-item" role="presentation">
			<button class="nav-link active allTab" data-bs-toggle="tab" data-order-status="all"
				type="button" role="tab" aria-controls="all" aria-selected="true">Tất cả</button>
		</li>
		<li class="nav-item" role="presentation">
			<button class="nav-link pendingTab" data-bs-toggle="tab" data-order-status="pending" type="button"
				role="tab" aria-controls="pending" aria-selected="false">Đang xử lý</button>
		</li>
		<li class="nav-item" role="presentation">
			<button class="nav-link deliveringTab" data-bs-toggle="tab" data-order-status="delivering" type="button"
				role="tab" aria-controls="delivering" aria-selected="false">Đang giao hàng</button>
		</li>
		<li class="nav-item" role="presentation">
			<button class="nav-link deliveredTab" data-bs-toggle="tab" data-order-status="delivered" type="button"
				role="tab" aria-controls="delivered" aria-selected="false">Đã giao</button>
		</li>
		<li class="nav-item" role="presentation">
			<button class="nav-link cancelledTab" data-bs-toggle="tab" data-order-status="cancelled" type="button"
				role="tab" aria-controls="cancelled" aria-selected="false">Đã hủy</button>
		</li>
	</ul>

	<!-- Tabs Content -->
	<div class="tab-content" id="ordersTabContainer">
		<!-- All Orders -->
		<div class="tab-pane fade show active allTab" role="tabpanel" aria-labelledby="all-orders-tab"
			data-order-status="">
		</div>
		<!-- Other Tabs (Repeat similar structure for other statuses) -->
		<div class="tab-pane fade pendingTab" role="tabpanel" aria-labelledby="pending-tab"
		data-order-status="pending">
			<!-- Pending Orders -->
		</div>
		<div class="tab-pane fade deliveringTab" role="tabpanel" aria-labelledby="delivering-tab"
		data-order-status="delivering">
			<!-- Shipping Orders -->
		</div>
		<div class="tab-pane fade deliveredTab" role="tabpanel" aria-labelledby="delivered-tab"
		data-order-status="delivered">
			<!-- delivered Orders -->
		</div>
		<div class="tab-pane fade cancelledTab" role="tabpanel" aria-labelledby="cancelled-tab"
		data-order-status="cancelled">
			<!-- Cancelled Orders -->
		</div>
	</div>
</div>