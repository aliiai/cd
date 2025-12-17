<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Debtor Model
 * 
 * نموذج المديونين (Debtors)
 */
class Debtor extends Model
{
    /**
     * اسم الجدول في قاعدة البيانات
     * 
     * @var string
     */
    protected $table = 'clients';

    /**
     * الحقول القابلة للتعبئة (Mass Assignment)
     */
    protected $fillable = [
        'owner_id',
        'name',
        'phone',
        'email',
        'debt_amount',
        'due_date',
        'payment_link',
        'notes',
        'status',
    ];

    /**
     * تحويل أنواع البيانات
     */
    protected $casts = [
        'debt_amount' => 'decimal:2',
        'due_date' => 'date',
    ];

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
     * العلاقة مع حملات التحصيل (Many to Many)
     * 
     * @return BelongsToMany
     */
    public function campaigns(): BelongsToMany
    {
        return $this->belongsToMany(CollectionCampaign::class, 'collection_campaign_clients', 'client_id', 'campaign_id')
            ->withPivot('status', 'sent_at', 'error_message')
            ->withTimestamps();
    }

    /**
     * الحصول على نص حالة الدين
     * 
     * @return string
     */
    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            'new' => 'جديد',
            'contacted' => 'تم التواصل',
            'promise_to_pay' => 'وعد بالدفع',
            'paid' => 'مدفوع',
            'overdue' => 'متأخر',
            'failed' => 'فشل',
            default => 'غير محدد',
        };
    }

    /**
     * الحصول على نص حالة الدين (static method للاستخدام في Controllers)
     * 
     * @param string $status
     * @return string
     */
    public static function getStatusText($status): string
    {
        return match($status) {
            'new' => 'جديد',
            'contacted' => 'تم التواصل',
            'promise_to_pay' => 'وعد بالدفع',
            'paid' => 'مدفوع',
            'overdue' => 'متأخر',
            'failed' => 'فشل',
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
            'new' => 'bg-blue-100 text-blue-800',
            'contacted' => 'bg-yellow-100 text-yellow-800',
            'promise_to_pay' => 'bg-purple-100 text-purple-800',
            'paid' => 'bg-green-100 text-green-800',
            'overdue' => 'bg-red-100 text-red-800',
            'failed' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * الحصول على لون Badge حسب الحالة (static method للاستخدام في Controllers)
     * 
     * @param string $status
     * @return string
     */
    public static function getStatusColor($status): string
    {
        return match($status) {
            'new' => 'bg-blue-100 text-blue-800',
            'contacted' => 'bg-yellow-100 text-yellow-800',
            'promise_to_pay' => 'bg-purple-100 text-purple-800',
            'paid' => 'bg-green-100 text-green-800',
            'overdue' => 'bg-red-100 text-red-800',
            'failed' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}

