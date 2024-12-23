@extends('layouts.layout')
@section('title', 'Wishlist Page')


@section('head-script')
<!-- Font import -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,700;1,700&display=swap" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@endsection


@section('style')
<style>
	body {
		background-color: #F7F4F0;
	}

	.montserrat-700-bold {
		font-family: "Montserrat", sans-serif;
		font-optical-sizing: auto;
		font-weight: 700;
		font-style: normal;
	}

	.montserrat-400-normal {
		font-family: "Montserrat", sans-serif;
		font-optical-sizing: auto;
		font-weight: 400;
		font-style: normal;
	}

	.table thead th {
		background-color: transparent;
		color: #1E362D;
		font-family: montserrat-700-bold;
	}

	.table tbody td {
		background-color: transparent;
	}

	#removeBtn:hover {
		color: black;
		border-color: black;
		opacity: 0.8;
	}

	#removeBtn {
		opacity: 0.5;
		border-color: #949A90;
		border-width: 2px;
		color: #949A90;
	}

	#addCartBtn:hover {
		border-color: #1E362D;
		border-radius: 7px;
	}

	#addCartBtn {}

	.alert-hidden {
		display: none;
	}

	.alert {
		position: absolute;
		top: 7vw;
		left: 50%;
		transform: translateX(-50%);
		z-index: 1000;
		width: 80%;
		max-width: 600px;
		transition: all 0.5s ease-in-out;
	}
</style>
@endsection

@section ('content')
<div class="container my-4">
	<div class="alert alert-success alert-hidden" role="alert">
		<i class="bi bi-check-circle"></i> Xoá sản phẩm thành công.
	</div>
	<div class="alert alert-danger alert-hidden" role="alert">
		<i class="bi bi-x-circle"></i> Xóa sản phẩm thất bại.
	</div>

	<h3 class="mb-4 fw-normal text-uppercase" style="font-size: 1.75rem; color: gray;">Sản phẩm yêu thích của tôi</h3>

	<table class="table table-borderless">
		<thead>
			<tr>
				<th scope="col" class="text-uppercase text-left"></th>
				<th scope="col" class="text-uppercase text-left">Tên sản phẩm</th>
				<th scope="col" class="text-uppercase text-left">Giá bán</th>
				<th scope="col" class="text-uppercase text-left">Tình trạng</th>
				<th scope="col" class="text-uppercase text-left"></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td colspan="5" class="border-bottom p-0 m-0" style="border-width: 2px;"></td>
			</tr>
			@foreach($wishlistProducts as $wishProduct)
				<tr>
					<td class>
						<div class="d-flex align-items-center">
							<button id="removeBtn" type="button" class="btn btn-outline btn-sm me-3 rounded-circle"
								onclick="removeFromWishlist(event, '{{$wishProduct->product_id}}' )"
								style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;">
								<i class="bi bi-x color fw-bold"></i>
							</button>
							<img src="{{ $wishProduct->product_images[0]->product_image_url }}" alt="Ảnh của cây" class="img-fluid rounded"
								style="width: 60px; height: 60px; border: none;">
						</div>
					</td>
					<td class="text-left">
						<div>
							<p class="mb-0">{{ $wishProduct->name }}</p>
							<small class="text-muted">{{ $wishProduct->code }}</small>
						</div>
					</td>
					<td class="text-left">
						<!-- <p class="fw-bold text-success mb-0">{{ number_format($wishProduct->price, 0, '.', ',') }}đ</p> -->
						<p class="fw-bold text-success mb-0">{{ number_format($wishProduct->price, 0, '.', ',') }}đ</p>
						@if ($wishProduct->discounted_price)
							<small
								class="text-decoration-line-through text-muted">{{ number_format($wishProduct->original_price, 0, '.', ',') }}₫</small>
						@endif
					</td>
					<td class="text-left">
						@if ($wishProduct->stock_quantity > 0)
							<small style="color: #949A90">Còn hàng</small>
						@else
							<small style="color: #C78B5F;">Hết hàng</small>
						@endif
					</td>
					<td class="text-left">
						<button id="addCartBtn" class="btn btn-sm">Thêm vào giỏ hàng</button>
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>

	<div class="mt-4">
		<p>Chia sẻ sản phẩm:</p>
		<div class="d-flex gap-2">
			<a href="#" class="btn btn-outline rounded-circle p-2" style="width: 40px; height: 40px;">
				<i class="fab fa-facebook-f"></i>
			</a>
			<a href="#" class="btn btn-outline rounded-circle p-2" style="width: 40px; height: 40px;">
				<i class="fab fa-twitter"></i>
			</a>
			<a href="#" class="btn btn-outline rounded-circle p-2" style="width: 40px; height: 40px;">
				<i class="fab fa-pinterest"></i>
			</a>
			<a href="#" class="btn btn-outline rounded-circle p-2" style="width: 40px; height: 40px;">
				<i class="fab fa-linkedin-in"></i>
			</a>
		</div>

	</div>
</div>

@endsection
<script>
	function removeFromWishlist(event, productId) {
		function showAlert(type, milisecs = 3000) {
			const alertClass = `.alert-${type}`;
			$(alertClass)
				.removeClass('alert-hidden');

			setTimeout(() => {
				$(alertClass).addClass('alert-hidden');
			}, milisecs);
		}

		$.ajax({
			url: "{{ route('wishlist.remove') }}",
			method: "POST",
			data: {
				_token: $('meta[name="csrf-token"]').attr('content'),
				product_id: productId,
			},
			success: function (data) {
				console.log('Xóa sản phẩm với mã sản phẩm ' + productId + ' thành công')
				const clickedBtn = $(event.target);
				clickedBtn.closest('tr').remove();
				showAlert('success');
			},
			error: function () {
				console.log('Xóa sản phẩm với mã sản phẩm ' + productId + ' thất bại')
				alert('Đã có lỗi xảy ra')
				showAlert('danger');
			}
		});
	}
</script>
@section('body-script')
@endsection