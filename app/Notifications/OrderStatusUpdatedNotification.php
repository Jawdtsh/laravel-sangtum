<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrderStatusUpdatedNotification extends Notification {
    use Queueable;

    private $order;

    public function __construct($order) {
        $this->order = $order;
    }

    public function via($notifiable) {
        return ['database'];
    }

    public function toDatabase($notifiable) {
        return [
            'order_id'   => $this->order->id,
            'new_status' => $this->order->status,
            'updated_at' => $this->order->updated_at,
        ];
    }
}
