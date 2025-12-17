<?php

namespace App\Notifications;

use App\Models\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

/**
 * إشعار تغيير حالة دين
 */
class ClientStatusChangedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Client $client,
        public string $oldStatus,
        public string $newStatus
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $statusText = match($this->newStatus) {
            'new' => 'جديد',
            'contacted' => 'تم التواصل',
            'promise_to_pay' => 'وعد بالدفع',
            'paid' => 'مدفوع',
            'overdue' => 'متأخر',
            'failed' => 'فشل',
            default => 'غير محدد',
        };

        return [
            'type' => 'client_status_changed',
            'title' => 'تم تغيير حالة الدين',
            'message' => "تم تغيير حالة دين {$this->client->name} إلى: {$statusText}",
            'client_id' => $this->client->id,
            'client_name' => $this->client->name,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'status_text' => $statusText,
            'url' => route('owner.clients.index', ['status' => $this->newStatus]),
            'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
        ];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }
}
