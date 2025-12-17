<?php

namespace App\Notifications;

use App\Models\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

/**
 * إشعار إضافة مديون جديد
 */
class ClientAddedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Client $client
    ) {}

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'client_added',
            'title' => 'تم إضافة مديون جديد',
            'message' => "تم إضافة المديون: {$this->client->name}",
            'client_id' => $this->client->id,
            'client_name' => $this->client->name,
            'debt_amount' => $this->client->debt_amount,
            'url' => route('owner.clients.index'),
            'icon' => 'M12 4v16m8-8H4',
        ];
    }

    /**
     * Get the broadcastable representation of the notification.
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }
}
