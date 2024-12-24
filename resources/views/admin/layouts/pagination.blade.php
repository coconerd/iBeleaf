<div class="d-flex justify-content-between align-items-center px-4 py-3">
	<div class="showing-text text-muted">
		@if (isset($compact) && $compact == true)
			{{ $paginator->firstItem() ?? 0 }}-{{ $paginator->lastItem() ?? 0 }}
		@else
			Hiển thị {{ $paginator->firstItem() ?? 0 }}-{{ $paginator->lastItem() ?? 0 }}
			trong tổng số {{ $paginator->total() ?? 0 }} {{ $itemName ?? 'mục' }}
		@endif
	</div>
	<div class="vintage-pagination">
		{{ $paginator->onEachSide(1)->links('pagination::bootstrap-4') }}
	</div>
	<style>
		.vintage-pagination .pagination {
			margin: 0;
			display: flex;
			gap: 0.5rem;
		}

		.vintage-pagination .page-item {
			margin: 0;
		}

		.vintage-pagination .page-link {
			border: none;
			padding: 0.5rem 1rem;
			color: #435E53;
			border-radius: 8px;
			font-weight: 500;
			transition: all 0.2s ease;
			background: transparent;
		}

		.vintage-pagination .page-link:hover {
			background: rgba(67, 94, 83, 0.1);
			color: #435E53;
			transform: translateY(-1px);
		}

		.vintage-pagination .page-item.active .page-link {
			background: #435E53;
			color: white;
			box-shadow: 0 2px 6px rgba(67, 94, 83, 0.2);
		}

		.vintage-pagination .page-item.disabled .page-link {
			background: transparent;
			color: #6c757d;
			opacity: 0.5;
			cursor: not-allowed;
		}

		.showing-text {
			font-size: 0.9rem;
			color: #6c757d;
		}
	</style>
</div>