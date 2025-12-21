<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * CollectionCampaign Model
 * 
 * نموذج حملات التحصيل
 */
class CollectionCampaign extends Model
{
    /**
     * الحقول القابلة للتعبئة (Mass Assignment)
     */
    protected $fillable = [
        'owner_id',
        'campaign_number',
        'channel',
        'template',
        'message',
        'send_type',
        'scheduled_at',
        'status',
        'total_recipients',
        'sent_count',
        'failed_count',
    ];

    /**
     * تحويل أنواع البيانات
     */
    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    /**
     * Boot method لتوليد رقم الحملة تلقائياً
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($campaign) {
            if (empty($campaign->campaign_number)) {
                $campaign->campaign_number = 'CAMP-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
            }
        });
    }

    /**
     * العلاقة مع المالك (User)
     * 
     * @return BelongsTo
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * العلاقة مع المديونين (Many to Many)
     * 
     * @return BelongsToMany
     */
    public function debtors(): BelongsToMany
    {
        return $this->belongsToMany(Debtor::class, 'collection_campaign_clients', 'campaign_id', 'client_id')
            ->withPivot('status', 'sent_at', 'error_message')
            ->withTimestamps();
    }

    /**
     * Alias للعلاقة مع المديونين (للتوافق مع الكود القديم)
     * 
     * @return BelongsToMany
     */
    public function clients(): BelongsToMany
    {
        return $this->debtors();
    }

    /**
     * الحصول على نص قناة الإرسال
     * 
     * @return string
     */
    public function getChannelTextAttribute(): string
    {
        return match($this->channel) {
            'sms' => 'SMS',
            'email' => 'Email',
            default => 'غير محدد',
        };
    }

    /**
     * الحصول على نص حالة الحملة
     * 
     * @return string
     */
    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            'pending' => 'قيد الانتظار',
            'sent' => 'تم الإرسال',
            'scheduled' => 'مجدول',
            'failed' => 'فشل',
            default => 'غير محدد',
        };
    }
    
    /**
     * الحصول على نص نوع الإرسال
     * 
     * @return string
     */
    public function getSendTypeTextAttribute(): string
    {
        return match($this->send_type) {
            'now' => 'فوري',
            'scheduled' => 'مجدول',
            'auto' => 'تلقائي',
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
            'pending' => 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300 border border-yellow-200 dark:border-yellow-800',
            'sent' => 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 border border-green-200 dark:border-green-800',
            'scheduled' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 border border-blue-200 dark:border-blue-800',
            'failed' => 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300 border border-red-200 dark:border-red-800',
            default => 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300 border border-gray-200 dark:border-gray-600',
        };
    }
    
    /**
     * الحصول على لون Badge حسب نوع الإرسال
     * 
     * @return string
     */
    public function getSendTypeColorAttribute(): string
    {
        return match($this->send_type) {
            'now' => 'bg-primary-100 dark:bg-primary-900/30 text-primary-800 dark:text-primary-300 border border-primary-200 dark:border-primary-800',
            'scheduled' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 border border-blue-200 dark:border-blue-800',
            'auto' => 'bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300 border border-purple-200 dark:border-purple-800',
            default => 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300 border border-gray-200 dark:border-gray-600',
        };
    }
}

