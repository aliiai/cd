<?php

namespace App\Notifications;

use App\Models\Ticket;
use App\Models\TicketMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * إشعار رد على شكوى
 */
class TicketRepliedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Ticket $ticket,
        public TicketMessage $message
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $isAdmin = $this->message->is_admin;
        $senderName = $isAdmin ? 'الأدمن' : $this->ticket->user->name;
        $route = $isAdmin ? 'owner.tickets.show' : 'admin.tickets.show';
        
        return [
            'type' => 'ticket_replied',
            'title' => 'رد جديد على الشكوى',
            'message' => "تم إرسال رد جديد من {$senderName} على الشكوى: {$this->ticket->subject}",
            'ticket_id' => $this->ticket->id,
            'ticket_number' => $this->ticket->ticket_number,
            'message_id' => $this->message->id,
            'is_admin' => $isAdmin,
            'url' => route($route, $this->ticket),
            'icon' => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z',
        ];
    }
}
