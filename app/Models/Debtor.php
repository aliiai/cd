<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'has_installments',
        'total_installments',
        'paid_amount',
        'remaining_amount',
    ];

    /**
     * تحويل أنواع البيانات
     */
    protected $casts = [
        'debt_amount' => 'decimal:2',
        'due_date' => 'date',
        'has_installments' => 'boolean',
        'paid_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
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
     * العلاقة مع الدفعات (Has Many)
     * 
     * @return HasMany
     */
    public function installments(): HasMany
    {
        return $this->hasMany(DebtInstallment::class)->orderBy('installment_number');
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
            'new' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 border border-blue-200 dark:border-blue-800',
            'contacted' => 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300 border border-yellow-200 dark:border-yellow-800',
            'promise_to_pay' => 'bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300 border border-purple-200 dark:border-purple-800',
            'paid' => 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 border border-green-200 dark:border-green-800',
            'overdue' => 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300 border border-red-200 dark:border-red-800',
            'failed' => 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300 border border-gray-200 dark:border-gray-600',
            default => 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300 border border-gray-200 dark:border-gray-600',
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
            'new' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 border border-blue-200 dark:border-blue-800',
            'contacted' => 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300 border border-yellow-200 dark:border-yellow-800',
            'promise_to_pay' => 'bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300 border border-purple-200 dark:border-purple-800',
            'paid' => 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 border border-green-200 dark:border-green-800',
            'overdue' => 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300 border border-red-200 dark:border-red-800',
            'failed' => 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300 border border-gray-200 dark:border-gray-600',
            default => 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300 border border-gray-200 dark:border-gray-600',
        };
    }

    /**
     * حساب المبلغ المتبقي
     * 
     * @return float
     */
    public function getRemainingAmountAttribute(): float
    {
        if ($this->has_installments) {
            $unpaidInstallments = $this->installments()
                ->where('status', '!=', 'paid')
                ->where('status', '!=', 'cancelled')
                ->get();
            
            $totalUnpaid = $unpaidInstallments->sum('amount');
            $totalPaid = $unpaidInstallments->sum('paid_amount');
            
            return max(0, $totalUnpaid - $totalPaid);
        }
        
        return max(0, $this->debt_amount - ($this->paid_amount ?? 0));
    }

    /**
     * حساب نسبة السداد
     * 
     * @return float
     */
    public function getPaymentProgressAttribute(): float
    {
        if ($this->debt_amount == 0) {
            return 100;
        }
        
        $paid = $this->paid_amount ?? 0;
        return min(100, ($paid / $this->debt_amount) * 100);
    }

    /**
     * التحقق من وجود دفعات متأخرة
     * 
     * @return bool
     */
    public function hasOverdueInstallments(): bool
    {
        if (!$this->has_installments) {
            return false;
        }
        
        return $this->installments()
            ->where('status', 'overdue')
            ->exists();
    }

    /**
     * الحصول على عدد الدفعات المدفوعة
     * 
     * @return int
     */
    public function getPaidInstallmentsCountAttribute(): int
    {
        if (!$this->has_installments) {
            return 0;
        }
        
        return $this->installments()
            ->where('status', 'paid')
            ->count();
    }

    /**
     * الحصول على عدد الدفعات المتأخرة
     * 
     * @return int
     */
    public function getOverdueInstallmentsCountAttribute(): int
    {
        if (!$this->has_installments) {
            return 0;
        }
        
        return $this->installments()
            ->where('status', 'overdue')
            ->count();
    }

    /**
     * تحديث حالة المديون بناءً على الدفعات
     * 
     * @return void
     */
    public function updateStatusFromInstallments(): void
    {
        if (!$this->has_installments) {
            return;
        }

        $allPaid = $this->installments()
            ->where('status', '!=', 'cancelled')
            ->where('status', '!=', 'paid')
            ->doesntExist();

        if ($allPaid && $this->status !== 'paid') {
            $this->status = 'paid';
            $this->save();
        }

        // تحديث المبلغ المدفوع والمتبقي
        $this->paid_amount = $this->installments()
            ->where('status', '!=', 'cancelled')
            ->sum('paid_amount');
        
        $this->remaining_amount = $this->remaining_amount;
        $this->save();
    }

    /**
     * الحصول على تاريخ الاستحقاق القادم
     * إذا كان الدين على دفعات، يرجع تاريخ الدفعة القادمة
     * وإلا يرجع تاريخ الاستحقاق الأصلي
     * 
     * @return \Carbon\Carbon|null
     */
    public function getNextDueDateAttribute()
    {
        if ($this->has_installments) {
            $nextInstallment = $this->installments()
                ->where('status', '!=', 'paid')
                ->where('status', '!=', 'cancelled')
                ->orderBy('due_date', 'asc')
                ->first();
            
            return $nextInstallment ? $nextInstallment->due_date : $this->due_date;
        }
        
        return $this->due_date;
    }
}

