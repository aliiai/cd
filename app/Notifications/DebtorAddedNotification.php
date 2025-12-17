<?php

namespace App\Notifications;

use App\Models\Debtor;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

/**
 * إشعار إضافة مديون جديد
 */
class DebtorAddedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Debtor $debtor
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
            'type' => 'debtor_added',
            'title' => 'تم إضافة مديون جديد',
            'message' => "تم إضافة المديون: {$this->debtor->name}",
            'debtor_id' => $this->debtor->id,
            'debtor_name' => $this->debtor->name,
            'debt_amount' => $this->debtor->debt_amount,
            'url' => route('owner.debtors.index'),
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

