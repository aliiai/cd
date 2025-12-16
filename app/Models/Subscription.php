<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Subscription Model
 * 
 * نموذج الباقات (Subscriptions)
 */
class Subscription extends Model
{
    /**
     * الحقول القابلة للتعبئة (Mass Assignment)
     */
    protected $fillable = [
        'name',
        'description',
        'price',
        'max_debtors',
        'max_messages',
        'ai_enabled',
        'is_active',
    ];

    /**
     * تحويل أنواع البيانات
     */
    protected $casts = [
        'price' => 'decimal:2',
        'max_debtors' => 'integer',
        'max_messages' => 'integer',
        'ai_enabled' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * العلاقة مع طلبات الاشتراك
     * 
     * @return HasMany
     */
    public function requests(): HasMany
    {
        return $this->hasMany(SubscriptionRequest::class);
    }

    /**
     * Scope للباقات النشطة فقط
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
