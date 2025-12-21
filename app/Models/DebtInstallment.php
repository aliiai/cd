<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * DebtInstallment Model
 * 
 * نموذج دفعات الدين
 */
class DebtInstallment extends Model
{
    /**
     * الحقول القابلة للتعبئة (Mass Assignment)
     */
    protected $fillable = [
        'debtor_id',
        'installment_number',
        'amount',
        'due_date',
        'paid_date',
        'paid_amount',
        'status',
        'payment_proof',
        'notes',
    ];

    /**
     * تحويل أنواع البيانات
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'due_date' => 'date',
        'paid_date' => 'date',
    ];

    /**
     * العلاقة مع المديون
     * 
     * @return BelongsTo
     */
    public function debtor(): BelongsTo
    {
        return $this->belongsTo(Debtor::class);
    }

    /**
     * حساب المبلغ المتبقي
     * 
     * @return float
     */
    public function getRemainingAmountAttribute(): float
    {
        return max(0, $this->amount - $this->paid_amount);
    }

    /**
     * التحقق من حالة الدفعة وتحديثها
     * 
     * @return void
     */
    public function updateStatus(): void
    {
        if ($this->paid_amount >= $this->amount) {
            $this->status = 'paid';
            if (!$this->paid_date) {
                $this->paid_date = now();
            }
        } elseif ($this->paid_amount > 0) {
            $this->status = 'partial';
        } elseif ($this->due_date < now() && $this->status !== 'cancelled') {
            $this->status = 'overdue';
        } else {
            $this->status = 'pending';
        }
        $this->save();
    }

    /**
     * الحصول على نص حالة الدفعة
     * 
     * @return string
     */
    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            'pending' => 'قيد الانتظار',
            'partial' => 'جزئية',
            'paid' => 'مدفوعة',
            'overdue' => 'متأخرة',
            'cancelled' => 'ملغاة',
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
            'pending' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 border border-blue-200 dark:border-blue-800',
            'partial' => 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300 border border-yellow-200 dark:border-yellow-800',
            'paid' => 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 border border-green-200 dark:border-green-800',
            'overdue' => 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300 border border-red-200 dark:border-red-800',
            'cancelled' => 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300 border border-gray-200 dark:border-gray-600',
            default => 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300 border border-gray-200 dark:border-gray-600',
        };
    }

    /**
     * Scope للدفعات المستحقة
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDue($query)
    {
        return $query->where('due_date', '<=', now())
                    ->where('status', '!=', 'paid')
                    ->where('status', '!=', 'cancelled');
    }

    /**
     * Scope للدفعات المتأخرة
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
                    ->where('status', '!=', 'paid')
                    ->where('status', '!=', 'cancelled');
    }

    /**
     * Scope للدفعات القادمة
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $days
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUpcoming($query, int $days = 7)
    {
        return $query->whereBetween('due_date', [now(), now()->addDays($days)])
                    ->where('status', '!=', 'paid')
                    ->where('status', '!=', 'cancelled');
    }
}
