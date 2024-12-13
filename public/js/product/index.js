let favorited = false;
document.addEventListener('DOMContentLoaded', function () {
	const thumbnails = document.querySelectorAll('.img-thumbnail');
	const carousel = new bootstrap.Carousel(document.querySelector('#productCarousel'));

	// Listeners for thumbnail functionality
	thumbnails.forEach((thumb, index) => {
		thumb.addEventListener('click', function () {
			carousel.to(index);
			updateThumbnails(index);
			// Change main image when thumbnail is selected
			setTimeout(() => {
				document.querySelector('.main-image img').src = this.src;
				document.querySelectorAll('.img-thumbnail').forEach(t => t.classList.remove('js-img-thumbnail-active'));
				this.classList.add('js-img-thumbnail-active');
			}, 480);
		});
	});

	// Counter functionality
	const decrementButton = document.getElementsByClassName("decrementBtn");
	const incrementButton = document.getElementsByClassName("incrementBtn");
	const counterInput = document.getElementsByClassName("counter");
	const totalPrice = document.getElementsByClassName("total-price");

	// Retrieve the unit price
	const unitPrice = parseFloat(totalPrice[0].dataset.unitPrice);

	// Event listener for counter decrement
	for (const button of decrementButton) {
		button.addEventListener("click", function () {
			let currentValue = parseInt(counterInput[0].value, 10);
			if (currentValue > 1) {
				for (const input of counterInput) {
					input.value = currentValue - 1;
				};
				// Update total price
				const newTotalPrice = unitPrice * (currentValue - 1);
				totalPrice[0].textContent = newTotalPrice.toLocaleString('en-US') + '₫';
			}
		});
	}

	// Event listener for counter increment
	for (const button of incrementButton) {
		button.addEventListener("click", function () {
			let currentValue = parseInt(counterInput[0].value, 10);
			for (const input of counterInput) {
				input.value = currentValue + 1;
				// Update total price
				const newTotalPrice = unitPrice * (currentValue + 1);
				totalPrice[0].textContent = newTotalPrice.toLocaleString('en-US') + '₫';
			};
		});
	}

	// Update initial heart button state
	const heartButton = document.querySelector('.heart-button');
	favorited = heartButton.classList.contains('heart-button-active');


	// Only show sticky div when addCartBtn element is out of view
	window.addEventListener("scroll", function () {
		const stickyDiv = $('.sticky-bottom')[0];
		const triggerElement = $('#addCartBtn');
		const triggerPosition = triggerElement.offset().top - $(window).scrollTop();

		// If the trigger element is out of view (below the viewport)
		if (triggerPosition < 0) {
			$(stickyDiv).slideDown(100); // Show sticky div with animation
		} else {
			$(stickyDiv).slideUp(100); // Hide sticky div with animation
		}
	});
});

let currentImageIndex = 0;
const carousel = document.querySelector('#productCarousel');

function navigateImage(direction) {
	const bsCarousel = bootstrap.Carousel.getInstance(carousel);

	// Calculate new index
	const items = carousel.querySelectorAll('.carousel-item');
	const activeItem = carousel.querySelector('.carousel-item.active');
	currentImageIndex = Array.from(items).indexOf(activeItem);
	let newIndex;

	if (direction > 0) {
		bsCarousel.next();
		newIndex = (((currentImageIndex + 2) % items.length) - 1);
	} else {
		bsCarousel.prev();
		newIndex = (((currentImageIndex + items.length) % items.length) - 1);
	}

	updateThumbnails(newIndex);
}

function updateThumbnails(index) {
	document.querySelectorAll('.img-thumbnail').forEach((thumb, i) => {
		thumb.classList.toggle('js-img-thumbnail-active', i === index);
	});
}

// Listen for carousel events
carousel.addEventListener('slid.bs.carousel', function () {
	const activeItem = carousel.querySelector('.carousel-item.active');
	const items = carousel.querySelectorAll('.carousel-item');
	currentImageIndex = Array.from(items).indexOf(activeItem);
	updateThumbnails(currentImageIndex);
});

// Add fullscreen functionality
const modal = document.getElementById('fullscreenModal');
const modalImg = document.getElementById('fullscreenImage');
const closeBtn = document.querySelector('.modal-close');

// Make main carousel images clickable
document.querySelectorAll('#productCarousel .carousel-item img').forEach(img => {
	img.style.cursor = 'pointer';
	img.onclick = function () {
		modal.style.display = 'block';
		modalImg.src = this.src;
	}
});

// Close modal when clicking close button or outside the image
closeBtn.onclick = function () {
	modal.style.display = 'none';
}

modal.onclick = function (e) {
	if (e.target === modal) {
		modal.style.display = 'none';
	}
}

// Close modal with escape key
document.addEventListener('keydown', function (e) {
	if (e.key === 'Escape' && modal.style.display === 'block') {
		modal.style.display = 'none';
	}
});

$('.heart-button').click(function () {
	const button = $(this);
	if (favorited) {
		$.ajax({
			url: "{{ route('wishlist.remove') }}",
			method: 'POST',
			data: {
				product_id: '{{ $productId }}',
				_token: '{{ csrf_token() }}'
			},
			success: function (response) {
				favorited = false;
				button.removeClass('heart-button-active');
				button.addClass('heart-button-inactive');
			},
			error: function (xhr) {
				alert('what the fuck');
				if (xhr.status === 401) {
					window.location.href = "{{ route('auth.showLoginForm') }}";
				} else {
					alert('Có lỗi khi xóa sản phẩm khỏi danh sách yêu thích.');
				}
			}
		});
	}
	else {
		$.ajax({
			url: "{{ route('wishlist.add') }}",
			method: 'POST',
			data: {
				product_id: '{{ $productId }}',
				_token: '{{ csrf_token() }}'
			},
			success: function (response) {
				favorited = true;
				button.removeClass('heart-button-inactive');
				button.addClass('heart-button-active');
			},
			error: function (xhr) {
				if (xhr.status === 401) {
					window.location.href = "{{ route('auth.showLoginForm') }}";
				} else {
					alert('Có lỗi xảy ra khi thêm sản phẩm vào danh sách yêu thích.');
				}
			}
		});
	}
});

// Load and display reviews
function loadReviews(productId) {
	console.log('loadReviews() is running!');
	$.get(`/feedback/${productId}`, function (data) {
		$('#reviewTitle').text(`Reviews (${data.length})`);
		if (data.length === 0) {
			$('#reviewsList').html('<p>Chưa có đánh giá nào. Hãy trở thành người đầu tiên đánh giá sản phẩm!</p>');
			return;
		}

		const reviewsHtml = data.map(review => `
				<div class="review-item">
					<div class="d-flex justify-content-between align-items-center mb-2">
						<div>
							<strong>${review.user.full_name}</strong>
							<div class="review-stars">
								${'★'.repeat(review.num_star)}${'☆'.repeat(5 - review.num_star)}
							</div>
						</div>
						<span class="review-date">${new Date(review.created_at).toLocaleDateString()}</span>
					</div>
					<p class="mb-0">${review.feedback_content}</p>
					${review.feedback_images.map(image => `<img src="data:image/jpeg;base64,${image.feedback_image}" alt="Feedback Image" class="feedback-image" style="width: 100px; height: 100px; object-fit: cover; margin-right: 10px;">`).join('')}
				</div>
			`).join('');

		$('#reviewsList').html(reviewsHtml);

		$('.feedback-image').click(function () {
			const modal = $('#fullscreenModal');
			const modalImg = $('#fullscreenImage');
			modalImg.attr('src', $(this).attr('src'));
			modal.show();
		});
	});
}

// Handle review submission
$('#reviewForm').on('submit', function (e) {
	e.preventDefault();

	const feedbackContent = $('textarea[name="review"]').val();
	console.log(feedbackContent);
	if (feedbackContent.length < 10) {
		alert('Vui lòng nhập tối thiểu 10 ký tự cho đánh giá của bạn');
		return;
	}

	$.ajax({
		url: "/feedback/store",
		method: 'POST',
		data: $(this).serialize(),
		success: function (response) {
			alert('Cảm ơn đã đánh giá sản phẩm!');
			$('#reviewForm')[0].reset();
			loadReviews(); // Reload reviews after submission
		},
		error: function (xhr) {
			if (xhr.status === 401) {
				window.location.href = "{{ route('auth.showLoginForm') }}";
			} else {
				alert('Có lỗi xảy ra khi gửi đánh giá.');
			}
		}
	});
});

// // Load reviews on page load
// loadReviews();

// Add wishlist functionality for related products
$('.hover-heart').click(function (e) {
	e.preventDefault();
	const button = $(this);
	const productId = button.data('product-id');

	if (button.hasClass('heart-button-active')) {
		$.ajax({
			url: "{{ route('wishlist.remove') }}",
			method: 'POST',
			data: {
				product_id: productId,
				_token: '{{ csrf_token() }}'
			},
			success: function (response) {
				button.removeClass('heart-button-active');
				button.addClass('heart-button-inactive');
				// button.find('i').css('color', 'white');
			},
			error: function (xhr) {
				if (xhr.status === 401) {
					window.location.href = "{{ route('auth.showLoginForm') }}";
				} else {
					alert('Error removing product from wishlist');
				}
			}
		});
	} else {
		$.ajax({
			url: "{{ route('wishlist.add') }}",
			method: 'POST',
			data: {
				product_id: productId,
				_token: '{{ csrf_token() }}'
			},
			success: function (response) {
				button.removeClass('heart-button-inactive');
				button.addClass('heart-button-active');
				// button.find('i').css('color', 'red');
			},
			error: function (xhr) {
				if (xhr.status === 401) {
					window.location.href = "{{ route('auth.showLoginForm') }}";
				} else {
					alert('Error adding product to wishlist');
				}
			}
		});
	}
});
