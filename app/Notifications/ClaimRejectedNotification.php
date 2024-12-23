<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ClaimRejectedNotification extends Notification
{
    use Queueable;
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->view('emails.claims-rejected', [
                'request' => $this->request
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'request_id' => $this->request->return_refund_id,
            'type' => $this->request->type,
            'status' => 'rejected',
            'reason' => $this->request->reject_reason
        ];
    }
}
