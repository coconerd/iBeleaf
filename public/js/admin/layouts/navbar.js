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
		const url = new URL('/admin/notifications', window.location.origin);
		url.searchParams.append('status', 'unread');
		$.ajax({
			url: url.toString(),
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
					<a class="dropdown-item preview-item"
						data-type="${noti.type}"
						data-subject-id=${getNotifcationSubjectID(noti)}
						data-notification-id=${getNotificationID(noti)}
					>
						<div class="preview-thumbnail">
							<div class="preview-icon bg-success">
								${getNotificationIcon(noti.type)}
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

	// Listen for new notification events on the 'orders' channel
	echo.channel('admin')
		.listen('.NewOrderNotification', addNotiToDropdown)
		.listen('.NewClaimNotification', addNotiToDropdown);

	// Handle notifications dropdown item click
	$(document).on('click', '#notifications > .dropdown-item', async function (e) {
		console.log($(this));
		const type = $(this).data('type');
		const notificationClass = type.split('\\').pop();
		const subjectId = $(this).data('subject-id');

		// Mark notification as read
		const notificationID = $(this).data('notification-id');
		await new Promise((resolve) => {
			$.ajax({
				url: `/admin/notifications/${notificationID}/mark-as-read`,
				method: 'POST',
				headers: {
					"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
				},
				success: function (response) {
					console.log('Notification marked as read:', response.message);
					const unreadCount = parseInt($('.notifications-count').text());
					$('.notifications-count').text((unreadCount - 1).toString());
					$('.notifications-count').css('display', unreadCount > 0 ? 'block' : 'none');
					resolve();
				},
				error: function (response) {
					console.error('Error marking notification as read:', response.message);
					resolve();
				}
			});
		});

		// Redirect to appropriate page
		switch (notificationClass) {
			case 'NewOrderNotification':
				// Additional steps after redirecting to /admin/orders
				sessionStorage.setItem('modal.orderId', subjectId);
				window.location.href = '/admin/orders';
				break;
			case 'NewClaimNotification':
				window.location.href = '/admin/claims';
				sessionStorage.setItem('modal.return_refund_id', subjectId);
				break;
			default:
				break;
		}
	});
});

function addNotiToDropdown(notification) {
	console.log('Received order notification:', notification);

	// Increment counter
	const unreadCount = parseInt($('.notifications-count').text());
	$('.notifications-count').text(unreadCount + 1);
	$('.notifications-count').css('display', 'block');

	// Create notification HTML
	const notiHtml = `
					<a class="dropdown-item preview-item" drop-down-item 
						data-type="${notification.db_link.type}
						data-subject-id="${getNotifcationSubjectID(notification)}"
						data-notification-id="${getNotificationID(notification)}"
					>
						<div class="preview-thumbnail">
							<div class="preview-icon bg-success">
								${getNotificationIcon(notification.db_link.type)}
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
}

function getNotificationIcon(notificationType) {
	switch (notificationType) {
		case 'App\\Notifications\\NewOrderNotification':
			return '<i class="mdi mdi-cart-plus"></i>';
		case 'App\\Notifications\\NewClaimNotification':
			return '<i class="mdi mdi-alert-circle"></i>';
		default:
			return '<i class="mdi mdi-cart-plus"></i>';
	}
}

function getNotifcationSubjectID(notification) {
	const notiType = !!notification.db_link ? 'realtime' : 'static';
	if (notiType === 'realtime') {
		switch (notification.db_link.type) {
			case 'App\\Notifications\\NewOrderNotification':
				return notification.db_link.order_id;
			case 'App\\Notifications\\NewClaimNotification':
				return notification.db_link.return_refund_id;
			default:
				return null;
		}
	}
	else {
		switch (notification.type) {
			case 'App\\Notifications\\NewOrderNotification':
				return notification.data.order_id;
			case 'App\\Notifications\\NewClaimNotification':
				return notification.data.return_refund_id;
			default:
				return null;
		}
	}
}

function getNotificationID(notification) {
	const notiType = !!notification.db_link ? 'realtime' : 'static';
	if (notiType === 'realtime') {
		return notification.db_link.id;
	}
	else {
		return notification.id;
	}
}
