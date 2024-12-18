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
		const triggerElement = $('.addCartBtn');
		const triggerPosition = triggerElement.offset().top - $(window).scrollTop();

		// If the trigger element is out of view (below the viewport)
		if (triggerPosition < 0) {
			$(stickyDiv).slideDown(100); // Show sticky div with animation
		} else {
			$(stickyDiv).slideUp(100); // Hide sticky div with animation
		}
	});

	// Global array to track uploaded files
	let uploadedImgs = [];

	function copyFilesToInput(inputElement) {
		// Create a DataTransfer object
		const dataTransfer = new DataTransfer();

		// Add all files from uploadedFiles array to DataTransfer
		uploadedImgs.forEach(file => {
			dataTransfer.items.add(file);
		});

		// Set the input's files to the DataTransfer's files
		inputElement.files = dataTransfer.files;
	}

	// Handle image selection (image input change)
	$('#reviewForm input[name="images[]"]').on('change', function () {
		const input = $(this)[0];
		const previewContainer = $('#preview-image-container');
		const imgCounter = $('#imgCounter');

		console.log('input is: ', input);

		if (input.files && input.files.length > 0) {
			// Add new files to uploadedFiles array
			const newFiles = Array.from(input.files);
			if (uploadedImgs.length + newFiles.length > 5) {
				alert('Bạn chỉ có thể tải lên tối đa 5 ảnh.');
				return;
			}

			uploadedImgs = [...uploadedImgs, ...newFiles];
			// Update input.files to match uploadedFiles
			copyFilesToInput(input);
			console.log('uploadedFiles: ', uploadedImgs);
			console.log('dataTransfer.files: ', input.files);
			console.log('input.files after update: ', input.files);

			// Preview new files
			newFiles.forEach(file => {
				let reader = new FileReader();
				reader.onload = function (e) {
					const imgHtml = `
                    <div class="position-relative p-2">
                        <img src="${e.target.result}" class="preview-image" style="width: 100px; height: 100px;">
                        <button type="button" class="btn-close position-absolute top-0 end-0" data-index="${uploadedImgs.length - 1}" aria-label="Close"></button>
                    </div>
                `;
					previewContainer.children().last().before(imgHtml);
				};
				reader.readAsDataURL(file);
			});

			console.log('input images: ', input.files);
			imgCounter.text(`Tải ảnh lên (${uploadedImgs.length}/5)`);
			// input.value = ''; // Reset input to allow selecting same file
		}
	});

	// Handle image removal
	$('#preview-image-container').on('click', '.btn-close', function () {
		const index = $(this).data('index');
		uploadedImgs.splice(index, 1);
		$(this).parent().remove();

		const input = $('#reviewForm input[name="images[]"]')[0];
		copyFilesToInput(input);

		$('#imgCounter').text(`Tải ảnh lên (${uploadedImgs.length}/5)`);

		// Update indices of remaining close buttons
		$('.btn-close').each(function (i) {
			$(this).data('index', i);
		});
	});

	// Handle image upload button click
	uploadBtn = $('#preview-image-container > div:last-child');
	uploadBtn.on('click', function () {
		let imgInput = $('#reviewForm input[name="images[]"]');
		imgInput.click()
	})
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
	const productId = button.data('product-id');
	if (favorited) {
		$.ajax({
			url: "/wishlist/remove",
			method: 'POST',
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			data: {
				product_id: productId,
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
			url: "/wishlist/add",
			method: 'POST',
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			data: {
				product_id: productId,
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
$('#reviewForm').on('halal', function (e) {
	e.preventDefault();

	const feedbackContent = $('textarea[name="review"]').val();
	console.log(feedbackContent);
	if (feedbackContent.length < 10) {
		alert('Vui lòng nhập tối thiểu 10 ký tự cho đánh giá của bạn');
		return;
	}

	$.ajax({
		url: "/product/submit-feedback",
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

	// Reset reviewForm's state data after form submission
	uploadedImgs = [];
	$('#preview-image-container').children().not(':last').remove();
	$('#imgCounter').text('Tải ảnh lên (0/5)');
	$('.star-rating input[type="radio"]').each(function () {
		$(this).prop('checked', false);
	});
});


// Add wishlist functionality for related products
$('.hover-heart').click(function (e) {
	e.preventDefault();
	const button = $(this);
	const productId = button.data('product-id');

	if (button.hasClass('heart-button-active')) {
		$.ajax({
			url: "/wishlist/remove",
			method: 'POST',
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			data: {
				product_id: productId,
			},
			success: function (response) {
				button.removeClass('heart-button-active');
				button.addClass('heart-button-inactive');
			},
			error: function (xhr) {
				alert('Lỗi khi thêm sản phẩm vào danh sách yêu thích');
			}
		});
	} else {
		$.ajax({
			url: "/wishlist/add",
			method: 'POST',
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			data: {
				product_id: productId,
			},
			success: function (response) {
				button.removeClass('heart-button-inactive');
				button.addClass('heart-button-active');
			},
			error: function (xhr) {
				alert('Lỗi khi thêm sản phẩm vào danh sách yêu thích');
			}
		});
	}
});

// Handle adding product to cart
$('.addCartBtn').on('click', function () {
	console.debug('triggered');
	const quantity = $('.counter').first().val();
	const productId = $(this).data('product-id');

	$.ajax({
		url: "/cart/insert",
		method: 'POST',
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		},
		data: {
			product_id: productId,
			quantity: quantity ?? 0,
			unit_price: 100000,
		},
		success: function () {
			$('.counter').val(1);
			$('.alert-success').html('');
			$('.alert-success').html('<strong>Sản phẩm đã được thêm vào giỏ hàng</strong>');
			$('.alert-success').removeClass('visually-hidden');
			$('.alert-success').fadeIn().delay(2000).fadeOut();
		},
		error: function () {
			$('.alert-danger').html('');
			$('.alert-danger').html('<strong>Không thể thêm sản phẩm vào giỏ hàng</strong>');
			$('.alert-danger').removeClass('visually-hidden');
			$('.alert-danger').fadeIn().delay(2000).fadeOut();
		}
	});
});
