<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Debtor;
use App\Models\DebtInstallment;
use App\Models\PaymentTransaction;
use App\Services\PaymobService;
use App\Services\InstallmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

/**
 * Payment Controller
 * 
 * Controller لمعالجة الدفعات عبر Paymob
 */
class PaymentController extends Controller
{
    protected $paymobService;

    public function __construct(PaymobService $paymobService)
    {
        $this->paymobService = $paymobService;
    }

    /**
     * إنشاء رابط دفع للمديون
     * 
     * @param Request $request
     * @param Debtor $debtor
     * @return \Illuminate\Http\JsonResponse
     */
    public function createPaymentLink(Request $request, Debtor $debtor)
    {
        // التحقق من أن المديون يخص المالك الحالي
        if ($debtor->owner_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'غير مصرح لك بإنشاء رابط دفع لهذا المديون.'
            ], 403);
        }

        // تحديد المبلغ المطلوب
        $amount = $debtor->has_installments 
            ? $debtor->remaining_amount 
            : $debtor->debt_amount;

        if ($amount <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'المبلغ المتبقي صفر أو أقل.'
            ], 400);
        }

        try {
            // التحقق من تكوين PayMob
            $apiKey = config('services.paymob.api_key');
            if (empty($apiKey)) {
                Log::error('PayMob not configured - API key missing', [
                    'debtor_id' => $debtor->id
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'إعدادات بوابة الدفع غير مكتملة. يرجى التحقق من إعدادات PayMob في ملف .env'
                ], 500);
            }

            // إنشاء رابط الدفع
            $result = $this->paymobService->generatePaymentLink(
                amount: $amount,
                debtorName: $debtor->name,
                debtorEmail: $debtor->email ?? $debtor->owner->email,
                debtorPhone: $debtor->phone,
                debtorId: $debtor->id
            );

            if (!$result || !isset($result['payment_link'])) {
                Log::error('Failed to generate payment link', [
                    'debtor_id' => $debtor->id,
                    'amount' => $amount
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'فشل إنشاء رابط الدفع. يرجى التحقق من إعدادات PayMob أو المحاولة مرة أخرى.'
                ], 500);
            }

            // حفظ رابط الدفع في قاعدة البيانات
            $debtor->update([
                'payment_link' => $result['payment_link'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم إنشاء رابط الدفع بنجاح.',
                'payment_link' => $result['payment_link'],
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to create payment link', [
                'debtor_id' => $debtor->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إنشاء رابط الدفع.'
            ], 500);
        }
    }

    /**
     * فتح iframe الدفع للمديون (للاختبار)
     * 
     * @param Request $request
     * @param Debtor $debtor
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function openPaymentIframe(Request $request, Debtor $debtor)
    {
        // التحقق من أن المديون يخص المالك الحالي
        if ($debtor->owner_id !== auth()->id()) {
            return redirect()->route('owner.collections.index')
                ->with('error', 'غير مصرح لك بفتح رابط دفع لهذا المديون.');
        }

        // تحديد المبلغ المطلوب
        $amount = $debtor->has_installments 
            ? $debtor->remaining_amount 
            : $debtor->debt_amount;

        if ($amount <= 0) {
            return redirect()->route('owner.collections.index')
                ->with('error', 'المبلغ المتبقي صفر أو أقل.');
        }

        try {
            // التحقق من تكوين PayMob
            $apiKey = config('services.paymob.api_key');
            if (empty($apiKey)) {
                Log::error('PayMob not configured - API key missing', [
                    'debtor_id' => $debtor->id
                ]);
                return redirect()->route('owner.collections.index')
                    ->with('error', 'إعدادات بوابة الدفع غير مكتملة. يرجى التحقق من إعدادات PayMob في ملف .env');
            }

            // إنشاء رابط الدفع
            $result = $this->paymobService->generatePaymentLink(
                amount: $amount,
                debtorName: $debtor->name,
                debtorEmail: $debtor->email ?? $debtor->owner->email,
                debtorPhone: $debtor->phone,
                debtorId: $debtor->id
            );

            if (!$result || !isset($result['payment_link'])) {
                Log::error('Failed to generate payment link', [
                    'debtor_id' => $debtor->id,
                    'amount' => $amount
                ]);
                return redirect()->route('owner.collections.index')
                    ->with('error', 'فشل إنشاء رابط الدفع. يرجى التحقق من إعدادات PayMob أو المحاولة مرة أخرى.');
            }

            // حفظ رابط الدفع في قاعدة البيانات
            $debtor->update([
                'payment_link' => $result['payment_link'],
            ]);

            // عرض صفحة iframe الدفع
            return view('owner.payments.iframe', [
                'paymentUrl' => $result['payment_link'],
                'debtor' => $debtor,
                'amount' => $amount,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to open payment iframe', [
                'debtor_id' => $debtor->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('owner.collections.index')
                ->with('error', 'حدث خطأ أثناء فتح صفحة الدفع.');
        }
    }

    /**
     * Short payment link redirect (for SMS)
     * 
     * @param Debtor $debtor
     * @return \Illuminate\Http\RedirectResponse
     */
    public function shortPaymentLink(Debtor $debtor)
    {
        if (!$debtor->payment_link) {
            return redirect()->route('owner.collections.index')
                ->with('error', 'رابط الدفع غير متوفر.');
        }
        
        return redirect()->away($debtor->payment_link);
    }

    /**
     * Handle payment callback from PayMob (redirect after payment)
     * This is called when user is redirected back from PayMob payment page
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleCallback(Request $request)
    {
        Log::info('Received callback from PayMob (redirect)', $request->all());
        
        try {
            // 1. Extract order ID using multiple methods
            $orderId = null;
            
            // Method 1: From merchant_order_id
            if ($request->has('merchant_order_id')) {
                $merchantOrderId = $request->input('merchant_order_id');
                
                // Check if it's a unique ID with timestamp format (id_timestamp)
                if (strpos($merchantOrderId, '_') !== false) {
                    $parts = explode('_', $merchantOrderId);
                    // Extract debtor ID from "debtor_{id}_timestamp" format
                    if (count($parts) >= 2 && $parts[0] === 'debtor') {
                        $orderId = $parts[1];
                        Log::info('Extracted debtor ID from unique merchant ID', [
                            'unique_merchant_id' => $merchantOrderId,
                            'extracted_debtor_id' => $orderId
                        ]);
                    } else {
                        $orderId = $parts[0];
                    }
                } else {
                    // Check if it starts with "debtor_"
                    if (strpos($merchantOrderId, 'debtor_') === 0) {
                        $orderId = str_replace('debtor_', '', $merchantOrderId);
                    } else {
                        $orderId = $merchantOrderId;
                    }
                    Log::info('Found order ID in merchant_order_id parameter', ['order_id' => $orderId]);
                }
            }
            
            // Method 2: From order (PayMob order ID)
            if (!$orderId && $request->has('order')) {
                $paymobOrderId = $request->input('order');
                $paymentTransaction = PaymentTransaction::where('paymob_order_id', $paymobOrderId)->first();
                
                if ($paymentTransaction) {
                    $orderId = $paymentTransaction->debtor_id;
                    Log::info('Found debtor ID via paymob_order_id', [
                        'paymob_order_id' => $paymobOrderId,
                        'debtor_id' => $orderId
                    ]);
                }
            }
            
            if (!$orderId) {
                Log::error('Could not find order ID in PayMob callback', $request->all());
                return redirect()->route('owner.collections.index')
                    ->with('error', 'تعذر تحديد الطلب. يرجى التحقق من طلباتك أو الاتصال بالدعم.');
            }
            
            // 2. Find debtor
            $debtor = \App\Models\Debtor::find($orderId);
            
            if (!$debtor) {
                Log::error('Debtor not found in database', ['debtor_id' => $orderId]);
                return redirect()->route('owner.collections.index')
                    ->with('error', 'لم يتم العثور على المديون. يرجى الاتصال بالدعم.');
            }
            
            // 3. Validate response (HMAC) - Optional but recommended
            $isValid = $this->paymobService->validateHmac($request->all());
            if (!$isValid) {
                Log::warning('HMAC validation failed', ['debtor_id' => $orderId]);
                // Continue processing but log the warning
            }
            
            // 4. Determine payment success or failure
            $success = false;
            
            // Check success parameter
            if ($request->has('success')) {
                $success = filter_var($request->input('success'), FILTER_VALIDATE_BOOLEAN);
            }
            
            // Check txn_response_code
            if (!$success && $request->has('txn_response_code')) {
                if ($request->input('txn_response_code') == 'APPROVED') {
                    $success = true;
                }
            }
            
            // Check data.message
            if (!$success && $request->has('data.message')) {
                if ($request->input('data.message') == 'Approved') {
                    $success = true;
                }
            }
            
            // Check obj.success (new format)
            if (!$success && $request->has('obj.success')) {
                $success = filter_var($request->input('obj.success'), FILTER_VALIDATE_BOOLEAN);
            }
            
            // 5. Update payment status
            if ($success) {
                // Payment was successful - processPayment will be called by webhook
                // Just redirect with success message
                return redirect()->route('owner.collections.show', $debtor->collection_id ?? $debtor->id)
                    ->with('success', 'تم الدفع بنجاح! سيتم تحديث حالة الدين قريباً.');
            } else {
                // Payment failed
                $errorMessage = $request->input('data.message', $request->input('obj.data.message', 'فشل الدفع بدون تفاصيل'));
                
                Log::info('Payment failed for debtor', [
                    'debtor_id' => $debtor->id,
                    'error_message' => $errorMessage
                ]);
                
                return redirect()->route('owner.collections.show', $debtor->collection_id ?? $debtor->id)
                    ->with('error', 'فشل الدفع: ' . $errorMessage);
            }
        } catch (\Exception $e) {
            Log::error('Error processing payment callback', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            
            return redirect()->route('owner.collections.index')
                ->with('error', 'حدث خطأ أثناء معالجة الدفع. يرجى الاتصال بالدعم.');
        }
    }

    /**
     * معالجة Callback من Paymob (Transaction Processed)
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function handleTransactionProcessedCallback(Request $request)
    {
        try {
            $data = $request->all();
            
            Log::info('Paymob Transaction Processed Callback', [
                'data' => $data,
            ]);

            // التحقق من صحة البيانات
            if (!$this->paymobService->verifyCallback($data)) {
                Log::warning('Invalid Paymob callback data', [
                    'data' => $data,
                ]);
                return response('Invalid callback data', 400);
            }

            // Validate HMAC if secret is configured
            $isValidHmac = $this->paymobService->validateHmac($data);
            if (!$isValidHmac) {
                Log::warning('HMAC validation failed for transaction processed callback', [
                    'data' => $data,
                ]);
                // Continue processing but log the warning
            }

            // معالجة البيانات
            $transactionData = $data['obj'] ?? [];
            $transactionId = $transactionData['id'] ?? null;
            $orderId = $transactionData['order']['id'] ?? null;
            $amount = ($transactionData['amount_cents'] ?? 0) / 100;
            $success = $transactionData['success'] ?? false;

            if ($success && $transactionId && $orderId) {
                $this->processPayment($orderId, $transactionId, $amount, $transactionData);
            }

            return response('OK', 200);
        } catch (\Exception $e) {
            Log::error('Paymob Transaction Processed Callback Exception', [
                'error' => $e->getMessage(),
                'data' => $request->all(),
            ]);

            return response('Error processing callback', 500);
        }
    }

    /**
     * معالجة Callback من Paymob (Transaction Response)
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function handleTransactionResponseCallback(Request $request)
    {
        try {
            $data = $request->all();
            
            Log::info('Paymob Transaction Response Callback', [
                'data' => $data,
            ]);

            // التحقق من صحة البيانات
            if (!$this->paymobService->verifyCallback($data)) {
                Log::warning('Invalid Paymob callback data', [
                    'data' => $data,
                ]);
                return response('Invalid callback data', 400);
            }

            // Validate HMAC if secret is configured
            $isValidHmac = $this->paymobService->validateHmac($data);
            if (!$isValidHmac) {
                Log::warning('HMAC validation failed for transaction response callback', [
                    'data' => $data,
                ]);
                // Continue processing but log the warning
            }

            // معالجة البيانات
            $transactionData = $data['obj'] ?? [];
            $transactionId = $transactionData['id'] ?? null;
            $orderId = $transactionData['order']['id'] ?? null;
            $amount = ($transactionData['amount_cents'] ?? 0) / 100;
            $success = $transactionData['success'] ?? false;

            if ($success && $transactionId && $orderId) {
                $this->processPayment($orderId, $transactionId, $amount, $transactionData);
            }

            return response('OK', 200);
        } catch (\Exception $e) {
            Log::error('Paymob Transaction Response Callback Exception', [
                'error' => $e->getMessage(),
                'data' => $request->all(),
            ]);

            return response('Error processing callback', 500);
        }
    }

    /**
     * معالجة الدفع وتحديث حالة الدين
     * 
     * @param string $orderId
     * @param string $transactionId
     * @param float $amount
     * @param array $transactionData
     * @return void
     */
    private function processPayment(string $orderId, string $transactionId, float $amount, array $transactionData): void
    {
        DB::transaction(function () use ($orderId, $transactionId, $amount, $transactionData) {
            // البحث عن معاملة الدفع
            $paymentTransaction = PaymentTransaction::where('paymob_order_id', $orderId)->first();

            if (!$paymentTransaction) {
                Log::warning('Payment transaction not found', [
                    'order_id' => $orderId,
                    'transaction_id' => $transactionId,
                ]);
                return;
            }

            // تحديث معاملة الدفع
            $paymentTransaction->update([
                'paymob_transaction_id' => $transactionId,
                'status' => 'success',
                'paymob_response' => $transactionData,
                'processed_at' => now(),
            ]);

            $debtor = $paymentTransaction->debtor;

            // إذا كان الدين على دفعات
            if ($debtor->has_installments && $paymentTransaction->debt_installment_id) {
                $installment = DebtInstallment::find($paymentTransaction->debt_installment_id);
                
                if ($installment) {
                    // تسجيل الدفعة في الدفعة المقسمة
                    $installmentService = new InstallmentService();
                    $installmentService->recordPayment(
                        installment: $installment,
                        amount: $amount,
                        paymentProof: null,
                        notes: "دفع عبر Paymob - Transaction ID: {$transactionId}",
                        paidDate: now()
                    );
                }
            } else {
                // دين عادي (غير مقسم)
                $debtor->paid_amount = ($debtor->paid_amount ?? 0) + $amount;
                $debtor->remaining_amount = max(0, $debtor->debt_amount - $debtor->paid_amount);

                // تحديث حالة الدين
                if ($debtor->remaining_amount <= 0) {
                    $debtor->status = 'paid';
                } elseif ($debtor->due_date < now() && $debtor->status !== 'paid') {
                    $debtor->status = 'overdue';
                }

                $debtor->save();
            }

            Log::info('Payment processed successfully', [
                'debtor_id' => $debtor->id,
                'transaction_id' => $transactionId,
                'amount' => $amount,
            ]);
        });
    }
}
