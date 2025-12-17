<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * إشعار تغيير حالة الشكوى
 */
class TicketStatusChangedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Ticket $ticket,
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
            'open' => 'مفتوحة',
            'in_progress' => 'قيد المعالجة',
            'waiting_user' => 'في انتظار المستخدم',
            'closed' => 'مغلقة',
            default => 'غير محدد',
        };

        return [
            'type' => 'ticket_status_changed',
            'title' => 'تغيير حالة الشكوى',
            'message' => "تم تغيير حالة الشكوى {$this->ticket->ticket_number} إلى: {$statusText}",
            'ticket_id' => $this->ticket->id,
            'ticket_number' => $this->ticket->ticket_number,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'status_text' => $statusText,
            'url' => route('owner.tickets.show', $this->ticket),
            'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
        ];
    }
}
