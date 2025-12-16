<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * UserSubscription Model
 * 
 * نموذج الاشتراكات النشطة للمستخدمين
 */
class UserSubscription extends Model
{
    /**
     * الحقول القابلة للتعبئة (Mass Assignment)
     */
    protected $fillable = [
        'user_id',
        'subscription_id',
        'subscription_request_id',
        'status',
        'started_at',
        'expires_at',
    ];

    /**
     * تحويل أنواع البيانات
     */
    protected $casts = [
        'started_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * العلاقة مع المستخدم
     * 
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * العلاقة مع الباقة
     * 
     * @return BelongsTo
     */
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    /**
     * العلاقة مع طلب الاشتراك
     * 
     * @return BelongsTo
     */
    public function subscriptionRequest(): BelongsTo
    {
        return $this->belongsTo(SubscriptionRequest::class);
    }

    /**
     * Scope للاشتراكات النشطة فقط
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where(function($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            });
    }

    /**
     * التحقق من أن الاشتراك نشط
     * 
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->status === 'active' && 
               ($this->expires_at === null || $this->expires_at->isFuture());
    }
}
