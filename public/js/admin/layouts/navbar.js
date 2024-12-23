document.addEventListener('DOMContentLoaded', function () {
	// Initialize Laravel Echo
	const echo = new Echo({
		broadcaster: 'pusher',
		key: 'local', // changed here
		cluster: 'mt1', // Replace with your Pusher cluster
		forceTLS: false, // Use true if using HTTPS, false for HTTP
		wsHost: window.location.hostname, // Replace with your Pusher or local server host
		wsPort: 8080, // Port for the WebSocket connection
		// wssPort: 443, // Port for the Secure WebSocket connection
		disableStats: true, // Disable sending connection stats to Pusher
		enabledTransports: ['ws'], // Enable WebSocket transport
	});

	// Add connection debugging
	echo.connector.pusher.connection.bind('connected', () => {
		console.log('Connected to Reverb WebSocket');
	});

	echo.connector.pusher.connection.bind('error', (err) => {
		console.error('Reverb connection error:', err);
	});

	echo.connector.pusher.connection.bind('disconnected', () => {
		console.log('Disconnected from Reverb');
	});

	// Initialize notification counter
	let unreadCount = 0;

	// fetch unread notificaitons from db
	(function fetchUnreadNotifcations() {
		$.ajax({
			url: '/admin/unread-notifications',
			method: 'GET',
			headers: {
				"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
			},
			success: function (response) {
				if (!response.success) {
					console.error('Error fetching unread notifications:', response.message);
					return;
				}
				console.log('Unread notifications:', response.notifications);
				unreadCount = response.notifications.length;
				$('.notifications-count').text(unreadCount.toString());
				$('.notifications-count').css('display', unreadCount > 0 ? 'block' : 'none');

				// Insert static notifications
				const notifications = response.notifications;
				const notiContainer = $('#notifications');
				const limit = Math.min(notifications.length, 4);
				for (let i = 0; i < limit; i++) {
					const noti = notifications[i];
					const notiHtml = `
					<a class="dropdown-item preview-item" data-type="${noti.type}">
						<div class="preview-thumbnail">
							<div class="preview-icon bg-success">
								<i class="mdi mdi-cart-plus"></i>
							</div>
						</div>
						<div class="preview-item-content d-flex align-items-start flex-column justify-content-center">
							<h6 class="preview-subject fw-normal mb-0">${noti.data.title}</h6>
							<p class="text-gray ellipsis mb-0">Just now</p>
						</div>
					</a>
					<div class="dropdown-divider"></div>`;
					notiContainer.append(notiHtml);
				}
			},

			error: function (error) {
				console.error('Error:', error);
			}
		});
	})();

	// Listen for new notification events
	echo.channel('orders')
		.listen('.NewOrderNotification', (notification) => { // Note the leading dot
			console.log('Received order notification:', notification);

			// Increment counter
			unreadCount++;
			$('.notifications-count').text(unreadCount.toString());
			$('.notifications-count').css('display', 'block');

			// Create notification HTML
			const notiHtml = `
					<a class="dropdown-item preview-item" data-type="${notification.db_link.type}">
						<div class="preview-thumbnail">
							<div class="preview-icon bg-success">
								<i class="mdi mdi-cart-plus"></i>
							</div>
						</div>
						<div class="preview-item-content d-flex align-items-start flex-column justify-content-center">
							<h6 class="preview-subject fw-normal mb-0">${notification.title}</h6>
							<p class="text-gray ellipsis mb-0">Just now</p>
						</div>
					</a>
					<div class="dropdown-divider"></div>
				`;

			// Insert at top of notifications list
			if ($('#notifications > .dropdown-item').length >= 6) {
				$('#notifications > .dropdown-item').last().remove();
			}
			$('#notifications').prepend(notiHtml);

			// Optional: Show toast notification
			Swal.fire({
				toast: true,
				position: 'top-end',
				icon: 'success',
				title: notification.title,
				showConfirmButton: false,
				timer: 3000
			});
		});

	// Handle notifications dropdown item click
	$(document).on('click', '#notifications > .dropdown-item', function (e) {
		console.log($(this));
		const type = $(this).data('type');
		const notificationClass = type.split('\\').pop();
		switch (notificationClass) {
			case 'NewOrderNotification':
				window.location.href = '/admin/orders';
				break;
			default:
				break;
		}
	});
});