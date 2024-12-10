<div class="container-fluid" id="orders-container">
	<!-- Tabs Navigation -->
	<ul class="nav nav-tabs mb-4" id="orderTabs" role="tablist">
		<li class="nav-item" role="presentation">
			<button class="nav-link active" id="all-orders-tab" data-bs-toggle="tab" data-bs-target="#all-orders"
				type="button" role="tab" aria-controls="all-orders" aria-selected="true">Tất cả</button>
		</li>
		<li class="nav-item" role="presentation">
			<button class="nav-link" id="pending-payment-tab" data-bs-toggle="tab" data-bs-target="#pending-payment"
				type="button" role="tab" aria-controls="pending-payment" aria-selected="false">Chờ thanh
				toán</button>
		</li>
		<li class="nav-item" role="presentation">
			<button class="nav-link" id="shipping-tab" data-bs-toggle="tab" data-bs-target="#shipping" type="button"
				role="tab" aria-controls="shipping" aria-selected="false">Vận chuyển</button>
		</li>
		<li class="nav-item" role="presentation">
			<button class="nav-link" id="completed-tab" data-bs-toggle="tab" data-bs-target="#completed" type="button"
				role="tab" aria-controls="completed" aria-selected="false">Hoàn thành</button>
		</li>
		<li class="nav-item" role="presentation">
			<button class="nav-link" id="cancelled-tab" data-bs-toggle="tab" data-bs-target="#cancelled" type="button"
				role="tab" aria-controls="cancelled" aria-selected="false">Đã hủy</button>
		</li>
		<li class="nav-item" role="presentation">
			<button class="nav-link" id="returns-tab" data-bs-toggle="tab" data-bs-target="#returns" type="button"
				role="tab" aria-controls="returns" aria-selected="false">Trả hàng/Hoàn tiền</button>
		</li>
	</ul>

	<!-- Tabs Content -->
	<div class="tab-content" id="orderTabsContainer">
		<!-- All Orders -->
		<div class="tab-pane fade show active" id="allTab" role="tabpanel" aria-labelledby="all-orders-tab"
			data-order-status="">
		</div>

		<!-- Other Tabs (Repeat similar structure for other statuses) -->
		<div class="tab-pane fade" id="pendingTab" role="tabpanel" aria-labelledby="pending-payment-tab"
		data-order-status="pending">
			<!-- Pending Payment Orders -->
		</div>
		<div class="tab-pane fade" id="deliveringTab" role="tabpanel" aria-labelledby="shipping-tab"
		data-order-status="delivering">
			<!-- Shipping Orders -->
		</div>
		<div class="tab-pane fade" id="completedTab" role="tabpanel" aria-labelledby="completed-tab"
		data-order-status="completed">
			<!-- Completed Orders -->
		</div>
		<div class="tab-pane fade" id="cancelledTab" role="tabpanel" aria-labelledby="cancelled-tab"
		data-order-status="cancelled">
			<!-- Cancelled Orders -->
		</div>
		<!-- <div class="tab-pane fade" id="returnedTab" role="tabpanel" aria-labelledby="returns-tab"> -->
			<!-- Returns/Refund Orders -->
		<!-- </div> -->
	</div>
</div>