<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Broadcasting\Channel;
use Illuminate\Notifications\Messages\BroadcastMessage;
use App\Models\RefundReturnImage;
use \App\Models\ReturnRefundItem;

class NewClaimNotification extends Notification
{
	use Queueable;

	protected ReturnRefundItem $request;

	/**
	 * Create a new notification instance.
	 */
	public function __construct(ReturnRefundItem $request)
	{
		$this->request = $request;
	}

	public function via(object $notifiable): array
	{
		return ['broadcast', 'database'];
	}

	public function toDatabase($notifiable)
	{
		return [
			'title' => 'Yêu cầu đổi/trả hàng mới #' . $this->request->return_refund_id,
			'type' => $this->request->type, // 'return' or 'refund'
			'time' => $this->request->created_at->toDateTimeString(),
			'return_refund_id' => $this->request->return_refund_id,
			'order_item_id' => $this->request->order_items_id,
		];
	}

	public function toBroadcast()
	{
		return new BroadcastMessage([
			'db_link' => [
				'id' => $this->id,
				'type' => 'App\\Notifications\\NewClaimNotification',
			],
			'title' => 'Yêu cầu đổi/trả hàng mới #' . $this->request->return_refund_id,
			'return_refund_id' => $this->request->return_refund_id,
			'time' => $this->request->created_at->toDateTimeString(),
			'order_item_id' => $this->request->order_items_id,
		]);
	}

	public function broadcastOn()
	{
		return new Channel('admin');
	}

	public function broadcastAs()
	{
		return 'NewClaimNotification';
	}

	public function toArray(object $notifiable): array
	{
		return [
		];
	}
}
