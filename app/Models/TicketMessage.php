<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * TicketMessage Model
 * 
 * نموذج رسائل الشكاوى
 */
class TicketMessage extends Model
{
    /**
     * الحقول القابلة للتعبئة (Mass Assignment)
     */
    protected $fillable = [
        'ticket_id',
        'user_id',
        'message',
        'attachment',
        'is_admin',
    ];

    /**
     * العلاقة مع الشكوى
     * 
     * @return BelongsTo
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    /**
     * العلاقة مع المستخدم (المرسل)
     * 
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
