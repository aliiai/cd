<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Ticket Model
 * 
 * نموذج الشكاوى (Tickets)
 */
class Ticket extends Model
{
    /**
     * الحقول القابلة للتعبئة (Mass Assignment)
     */
    protected $fillable = [
        'ticket_number',
        'user_id',
        'subject',
        'type',
        'status',
        'description',
        'attachment',
        'closed_at',
    ];

    /**
     * تحويل أنواع البيانات
     */
    protected $casts = [
        'closed_at' => 'datetime',
    ];

    /**
     * Boot method لتوليد رقم الشكوى تلقائياً
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($ticket) {
            if (empty($ticket->ticket_number)) {
                $ticket->ticket_number = 'TKT-' . strtoupper(uniqid());
            }
        });
    }

    /**
     * العلاقة مع المستخدم (Owner)
     * 
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * العلاقة مع الرسائل
     * 
     * @return HasMany
     */
    public function messages(): HasMany
    {
        return $this->hasMany(TicketMessage::class);
    }

    /**
     * الحصول على آخر رسالة
     * 
     * @return TicketMessage|null
     */
    public function lastMessage()
    {
        return $this->messages()->latest()->first();
    }

    /**
     * الحصول على نص نوع الشكوى
     * 
     * @return string
     */
    public function getTypeTextAttribute(): string
    {
        return match($this->type) {
            'technical' => 'مشكلة تقنية',
            'subscription' => 'مشكلة اشتراك',
            'messages' => 'مشكلة رسائل',
            'general' => 'استفسار عام',
            'suggestion' => 'اقتراح',
            default => 'غير محدد',
        };
    }

    /**
     * الحصول على لون Badge حسب النوع
     * 
     * @return string
     */
    public function getTypeColorAttribute(): string
    {
        return match($this->type) {
            'technical' => 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300 border border-red-200 dark:border-red-800',
            'subscription' => 'bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300 border border-purple-200 dark:border-purple-800',
            'messages' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 border border-blue-200 dark:border-blue-800',
            'general' => 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300 border border-gray-200 dark:border-gray-600',
            'suggestion' => 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 border border-green-200 dark:border-green-800',
            default => 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300 border border-gray-200 dark:border-gray-600',
        };
    }

    /**
     * الحصول على نص حالة الشكوى
     * 
     * @return string
     */
    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            'open' => 'مفتوحة',
            'in_progress' => 'قيد المعالجة',
            'waiting_user' => 'في انتظار المستخدم',
            'closed' => 'مغلقة',
            default => 'غير محدد',
        };
    }

    /**
     * الحصول على لون Badge حسب الحالة
     * 
     * @return string
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'open' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 border border-blue-200 dark:border-blue-800',
            'in_progress' => 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300 border border-yellow-200 dark:border-yellow-800',
            'waiting_user' => 'bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-300 border border-orange-200 dark:border-orange-800',
            'closed' => 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300 border border-gray-200 dark:border-gray-600',
            default => 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300 border border-gray-200 dark:border-gray-600',
        };
    }

    /**
     * التحقق من إمكانية الإغلاق
     * 
     * @return bool
     */
    public function canBeClosed(): bool
    {
        return $this->status !== 'closed';
    }

    /**
     * إغلاق الشكوى
     */
    public function close()
    {
        $this->update([
            'status' => 'closed',
            'closed_at' => now(),
        ]);
    }

    /**
     * إعادة فتح الشكوى
     */
    public function reopen()
    {
        $this->update([
            'status' => 'open',
            'closed_at' => null,
        ]);
    }
}
