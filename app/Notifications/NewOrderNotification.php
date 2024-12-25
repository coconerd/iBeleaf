<?php

namespace App\Notifications;

use Illuminate\Broadcasting\Channel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class NewOrderNotification extends Notification
{
	use Queueable;
	

	protected $order;

	public function __construct($order)
	{
		$this->order = $order;
	}

	public function via($notifiable)
	{
		return ['database', 'broadcast'];
	}

	public function toDatabase($notifiable)
	{
		return [
			'title' => 'Đơn hàng mới #' . $this->order->order_id,
			'time' => $this->order->created_at->toDateTimeString(),
			'order_id' => $this->order->order_id
		];
	}

	public function toBroadcast($notifiable)
	{
		return new BroadcastMessage([
			'db_link' => [
				'id' => $this->id,
				'type' => 'App\\Notifications\\NewOrderNotification',
			],
			'title' => 'Đơn hàng mới #' . $this->order->order_id,
			'order_id' => $this->order->order_id,
			'time' => $this->order->created_at->toDateTimeString(),
		]);
	}

	public function broadcastOn()
	{
		return new Channel('admin');
	}

	public function broadcastAs()
	{
		return 'NewOrderNotification';
	}

	public function toArray($notifiable)
	{
		return [
			'order_id' => $this->order->id,
			'status' => $this->order->status,
		];
	}
}