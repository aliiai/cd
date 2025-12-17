<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * إشعار إنشاء شكوى جديدة
 */
class TicketCreatedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Ticket $ticket
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'ticket_created',
            'title' => 'شكوى جديدة',
            'message' => "تم إنشاء شكوى جديدة من {$this->ticket->user->name}: {$this->ticket->subject}",
            'ticket_id' => $this->ticket->id,
            'ticket_number' => $this->ticket->ticket_number,
            'user_name' => $this->ticket->user->name,
            'url' => route('admin.tickets.show', $this->ticket),
            'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
        ];
    }
}

