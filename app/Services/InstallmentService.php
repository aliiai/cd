<?php

namespace App\Services;

use App\Models\Debtor;
use App\Models\DebtInstallment;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

/**
 * InstallmentService
 * 
 * خدمة إدارة دفعات الدين
 */
class InstallmentService
{
    /**
     * إنشاء دفعات تلقائياً
     * 
     * @param Debtor $debtor
     * @param float $totalAmount
     * @param int $numberOfInstallments
     * @param string $frequency
     * @param Carbon|null $startDate
     * @return Collection
     */
    public function createInstallments(
        Debtor $debtor,
        float $totalAmount,
        int $numberOfInstallments,
        string $frequency = 'monthly',
        ?Carbon $startDate = null
    ): Collection {
        $installments = new Collection();
        $installmentAmount = $totalAmount / $numberOfInstallments;
        $startDate = $startDate ?? now();
        
        // توزيع المبلغ (آخر دفعة تحمل الفرق)
        $baseAmount = floor($installmentAmount * 100) / 100;
        $remainder = $totalAmount - ($baseAmount * $numberOfInstallments);
        
        for ($i = 1; $i <= $numberOfInstallments; $i++) {
            $amount = $i === $numberOfInstallments 
                ? $baseAmount + $remainder 
                : $baseAmount;
            
            $dueDate = match($frequency) {
                'weekly' => $startDate->copy()->addWeeks($i - 1),
                'biweekly' => $startDate->copy()->addWeeks(($i - 1) * 2),
                'monthly' => $startDate->copy()->addMonths($i - 1),
                'every_3_months' => $startDate->copy()->addMonths(($i - 1) * 3),
                'every_6_months' => $startDate->copy()->addMonths(($i - 1) * 6),
                'yearly' => $startDate->copy()->addYears($i - 1),
                default => $startDate->copy()->addMonths($i - 1),
            };
            
            $installments->push(DebtInstallment::create([
                'debtor_id' => $debtor->id,
                'installment_number' => $i,
                'amount' => $amount,
                'due_date' => $dueDate,
                'status' => 'pending',
            ]));
        }
        
        // تحديث بيانات المديون
        $debtor->update([
            'has_installments' => true,
            'total_installments' => $numberOfInstallments,
            'debt_amount' => $totalAmount,
            'remaining_amount' => $totalAmount,
            'paid_amount' => 0,
        ]);
        
        return $installments;
    }
    
    /**
     * تسجيل دفعة
     * 
     * @param DebtInstallment $installment
     * @param float $amount
     * @param string|null $paymentProof
     * @param string|null $notes
     * @param Carbon|null $paidDate
     * @return void
     */
    public function recordPayment(
        DebtInstallment $installment,
        float $amount,
        ?string $paymentProof = null,
        ?string $notes = null,
        ?Carbon $paidDate = null
    ): void {
        DB::transaction(function () use ($installment, $amount, $paymentProof, $notes, $paidDate) {
            $installment->paid_amount += $amount;
            
            if ($paymentProof) {
                $installment->payment_proof = $paymentProof;
            }
            
            if ($notes) {
                $installment->notes = $notes;
            }
            
            if ($paidDate) {
                $installment->paid_date = $paidDate;
            }
            
            $installment->updateStatus();
            
            // تحديث المديون
            $debtor = $installment->debtor;
            $debtor->paid_amount = $debtor->installments()
                ->where('status', '!=', 'cancelled')
                ->sum('paid_amount');
            
            // حساب المبلغ المتبقي بشكل صحيح
            $totalUnpaid = $debtor->installments()
                ->where('status', '!=', 'paid')
                ->where('status', '!=', 'cancelled')
                ->get()
                ->sum(function($inst) {
                    return max(0, $inst->amount - $inst->paid_amount);
                });
            
            $debtor->remaining_amount = $totalUnpaid;
            
            // تحديث حالة المديون إذا تم سداد كل الدفعات
            if ($debtor->remaining_amount <= 0) {
                $debtor->status = 'paid';
            }
            
            $debtor->save();
        });
    }

    /**
     * تأجيل دفعة
     * 
     * @param DebtInstallment $installment
     * @param Carbon $newDueDate
     * @param bool $updateNext
     * @return void
     */
    public function postponeInstallment(
        DebtInstallment $installment,
        Carbon $newDueDate,
        bool $updateNext = true
    ): void {
        DB::transaction(function () use ($installment, $newDueDate, $updateNext) {
            $oldDueDate = $installment->due_date;
            $daysDifference = $oldDueDate->diffInDays($newDueDate);
            
            $installment->due_date = $newDueDate;
            $installment->updateStatus();
            $installment->save();
            
            // تحديث الدفعات التالية
            if ($updateNext) {
                $nextInstallments = $installment->debtor->installments()
                    ->where('installment_number', '>', $installment->installment_number)
                    ->where('status', '!=', 'paid')
                    ->where('status', '!=', 'cancelled')
                    ->orderBy('installment_number')
                    ->get();
                
                foreach ($nextInstallments as $next) {
                    $next->due_date = $next->due_date->addDays($daysDifference);
                    $next->updateStatus();
                    $next->save();
                }
            }
        });
    }

    /**
     * إلغاء دفعة
     * 
     * @param DebtInstallment $installment
     * @param bool $redistribute
     * @return void
     */
    public function cancelInstallment(
        DebtInstallment $installment,
        bool $redistribute = true
    ): void {
        DB::transaction(function () use ($installment, $redistribute) {
            $amount = $installment->amount - $installment->paid_amount;
            
            $installment->status = 'cancelled';
            $installment->save();
            
            // إعادة توزيع المبلغ على الدفعات المتبقية
            if ($redistribute && $amount > 0) {
                $remainingInstallments = $installment->debtor->installments()
                    ->where('installment_number', '>', $installment->installment_number)
                    ->where('status', '!=', 'paid')
                    ->where('status', '!=', 'cancelled')
                    ->orderBy('installment_number')
                    ->get();
                
                if ($remainingInstallments->count() > 0) {
                    $amountPerInstallment = $amount / $remainingInstallments->count();
                    $baseAmount = floor($amountPerInstallment * 100) / 100;
                    $remainder = $amount - ($baseAmount * $remainingInstallments->count());
                    
                    foreach ($remainingInstallments as $index => $remaining) {
                        $additionalAmount = ($index === $remainingInstallments->count() - 1) 
                            ? $baseAmount + $remainder 
                            : $baseAmount;
                        
                        $remaining->amount += $additionalAmount;
                        $remaining->save();
                    }
                }
            }
            
            // تحديث المديون
            $debtor = $installment->debtor;
            $debtor->updateStatusFromInstallments();
        });
    }

    /**
     * تحديث حالة جميع الدفعات المتأخرة
     * 
     * @return int
     */
    public function updateOverdueInstallments(): int
    {
        $overdueInstallments = DebtInstallment::where('due_date', '<', now())
            ->where('status', '!=', 'paid')
            ->where('status', '!=', 'cancelled')
            ->get();
        
        $count = 0;
        foreach ($overdueInstallments as $installment) {
            if ($installment->status !== 'overdue') {
                $installment->updateStatus();
                $count++;
            }
        }
        
        return $count;
    }
}

