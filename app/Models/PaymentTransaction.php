<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * PaymentTransaction Model
 * 
 * نموذج معاملات الدفع من Paymob
 */
class PaymentTransaction extends Model
{
    protected $fillable = [
        'debtor_id',
        'debt_installment_id',
        'paymob_order_id',
        'paymob_transaction_id',
        'amount',
        'currency',
        'status',
        'paymob_response',
        'processed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paymob_response' => 'array',
        'processed_at' => 'datetime',
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
     * العلاقة مع الدفعة (إن وجدت)
     * 
     * @return BelongsTo
     */
    public function installment(): BelongsTo
    {
        return $this->belongsTo(DebtInstallment::class, 'debt_installment_id');
    }
}
