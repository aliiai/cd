<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

/**
 * Paymob Service
 * 
 * خدمة للتعامل مع بوابة الدفع Paymob
 */
class PaymobService
{
    protected $apiKey;
    protected $integrationId;
    protected $iframeId;
    protected $merchantId;
    protected $hmacSecret;
    protected $currency;
    protected $baseUrl;
    protected $callbackUrl;
    protected $authToken;

    public function __construct()
    {
        $this->apiKey = config('services.paymob.api_key');
        $this->integrationId = config('services.paymob.integration_id');
        $this->iframeId = config('services.paymob.iframe_id');
        $this->merchantId = config('services.paymob.merchant_id');
        $this->hmacSecret = config('services.paymob.hmac_secret', '');
        $this->currency = config('services.paymob.currency', 'SAR');
        $this->baseUrl = config('services.paymob.base_url', 'https://ksa.paymob.com/api');
        $this->callbackUrl = config('services.paymob.callback_url', 'https://yourdomain.com/payment/callback');
        
        // Validate and log configuration
        if (empty($this->apiKey)) {
            Log::error('PayMob API key is not configured');
        } else {
            Log::info('PayMob API key loaded successfully', ['length' => strlen($this->apiKey)]);
        }

        if (empty($this->integrationId)) {
            Log::error('PayMob Integration ID is not configured');
        }

        if (empty($this->iframeId)) {
            Log::error('PayMob iFrame ID is not configured');
        }

        if (empty($this->merchantId)) {
            Log::error('PayMob Merchant ID is not configured');
        }
    }

    /**
     * تسجيل الدخول وجلب رمز المصادقة من Paymob
     * 
     * @return array|null
     */
    public function authenticate(): ?array
    {
        try {
            if (empty($this->apiKey)) {
                throw new \Exception('PayMob API key is not configured');
            }

            Log::info('Attempting PayMob authentication', [
                'base_url' => $this->baseUrl,
                'api_key_length' => strlen($this->apiKey)
            ]);

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'User-Agent' => 'Laravel/PayMob-Client'
            ])->post($this->baseUrl . '/auth/tokens', [
                'api_key' => $this->apiKey
            ]);

            Log::info('PayMob authentication response', [
                'status_code' => $response->status(),
                'body' => $response->json() ?? $response->body()
            ]);

            if (!$response->successful()) {
                Log::error('PayMob authentication failed', [
                    'status' => $response->status(),
                    'body' => $response->json() ?? $response->body(),
                    'response_headers' => $response->headers()
                ]);
                throw new \Exception('Authentication failed: ' . ($response->json('message') ?? $response->json('detail') ?? $response->status()));
            }

            $responseData = $response->json();
            
            if (!isset($responseData['token'])) {
                Log::error('PayMob response missing token', [
                    'response_data' => $responseData
                ]);
                throw new \Exception('Authentication response missing token');
            }

            $token = $responseData['token'];
            $this->authToken = $token;

            Log::info('PayMob authentication successful', [
                'token_length' => strlen($token),
                'profile_id' => $responseData['profile']['id'] ?? null
            ]);
            
            return [
                'token' => $token,
                'profile_id' => $responseData['profile']['id'] ?? null,
                'success' => true
            ];

        } catch (\Exception $e) {
            Log::error('PayMob authentication exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    /**
     * الحصول على Auth Token من Paymob (للتوافق مع الكود القديم)
     * 
     * @return string|null
     */
    public function getAuthToken(): ?string
    {
        // إذا كان لدينا token محفوظ مسبقاً (من constructor)
        if ($this->authToken) {
            return $this->authToken;
        }

        // استخدام Cache لتخزين الـ token لمدة 24 ساعة
        return Cache::remember('paymob_auth_token', now()->addHours(24), function () {
            $authData = $this->authenticate();
            return $authData['token'] ?? null;
        });
    }

    /**
     * إنشاء Order في Paymob
     * 
     * @param string $authToken
     * @param float $amount
     * @param string $currency
     * @param array $items
     * @param int|null $merchantOrderId
     * @return int|null
     */
    public function createOrder($authToken, float $amount, string $currency = 'SAR', array $items = [], $merchantOrderId = null): ?int
    {
        try {
            $orderId = $merchantOrderId ? "debtor_{$merchantOrderId}" : uniqid('order_');
            
            Log::info('إنشاء طلب في PayMob', [
                'amount_cents' => (int)($amount * 100),
                'merchant_order_id' => $orderId
            ]);
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $authToken
            ])->post("{$this->baseUrl}/ecommerce/orders", [
                'auth_token' => $authToken,
                'delivery_needed' => false,
                'amount_cents' => (int)($amount * 100),
                'currency' => $currency,
                'merchant_order_id' => $orderId,
                'items' => $items,
            ]);

            // معالجة حالة التكرار (duplicate order)
            if (!$response->successful() && $response->status() === 422) {
                $errorMessage = $response->json('message') ?? '';
                
                if (strpos($errorMessage, 'duplicate') !== false || strpos($errorMessage, 'مكرر') !== false) {
                    // إنشاء معرف فريد بإضافة timestamp
                    $uniqueMerchantOrderId = $orderId . '_' . time();
                    
                    $response = Http::withHeaders([
                        'Authorization' => 'Bearer ' . $authToken
                    ])->post("{$this->baseUrl}/ecommerce/orders", [
                        'auth_token' => $authToken,
                        'delivery_needed' => false,
                        'amount_cents' => (int)($amount * 100),
                        'currency' => $currency,
                        'merchant_order_id' => $uniqueMerchantOrderId,
                        'items' => $items,
                    ]);
                }
            }

            if (!$response->successful()) {
                Log::error('خطأ في إنشاء الطلب في PayMob', [
                    'status' => $response->status(),
                    'response' => $response->json() ?? $response->body(),
                    'response_headers' => $response->headers(),
                    'request_url' => "{$this->baseUrl}/ecommerce/orders"
                ]);
                return null;
            }

            $paymobOrderId = $response->json('id');
            Log::info('تم إنشاء الطلب في PayMob بنجاح', [
                'paymob_order_id' => $paymobOrderId
            ]);
            
            return $paymobOrderId;
        } catch (\Exception $e) {
            Log::error('استثناء أثناء إنشاء الطلب في PayMob', [
                'message' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * إنشاء Payment Key
     * 
     * @param string $authToken
     * @param int $paymobOrderId
     * @param float $amount
     * @param string $currency
     * @param array $billingData
     * @return string|null
     */
    public function createPaymentKey($authToken, int $paymobOrderId, float $amount, string $currency = 'SAR', array $billingData = []): ?string
    {
        try {
            Log::info('إنشاء مفتاح الدفع في PayMob', [
                'paymob_order_id' => $paymobOrderId,
            ]);

            // إعداد بيانات الفوترة
            // تقسيم الاسم إذا لم يتم توفير last_name
            $firstName = $billingData['first_name'] ?? 'Customer';
            $lastName = $billingData['last_name'] ?? '';
            
            // إذا لم يكن هناك last_name، قم بتقسيم first_name
            if (empty($lastName) && !empty($firstName)) {
                $nameParts = explode(' ', $firstName, 2);
                $firstName = $nameParts[0] ?? 'Customer';
                $lastName = $nameParts[1] ?? 'Name';
            }
            
            // إذا كان last_name لا يزال فارغاً، استخدم قيمة افتراضية
            if (empty($lastName)) {
                $lastName = 'Name';
            }
            
            $billingData = array_merge([
                'apartment' => 'NA',
                'email' => $billingData['email'] ?? 'customer@example.com',
                'floor' => 'NA',
                'first_name' => $firstName,
                'street' => 'NA',
                'building' => 'NA',
                'phone_number' => $billingData['phone'] ?? '+966500000000',
                'shipping_method' => 'NA',
                'postal_code' => 'NA',
                'city' => $billingData['city'] ?? 'Riyadh',
                'country' => 'SA',
                'last_name' => $lastName,
                'state' => 'NA'
            ], $billingData);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $authToken
            ])->post("{$this->baseUrl}/acceptance/payment_keys", [
                'auth_token' => $authToken,
                'amount_cents' => (int)($amount * 100),
                'expiration' => 3600, // صلاحية المفتاح: ساعة واحدة
                'order_id' => $paymobOrderId,
                'billing_data' => $billingData,
                'currency' => $currency,
                'integration_id' => $this->integrationId,
                'lock_order_when_paid' => true,
                'return_callback_url' => $this->callbackUrl,
                'return_merchant_callback_url' => $this->callbackUrl,
            ]);

            if (!$response->successful()) {
                Log::error('خطأ في إنشاء مفتاح الدفع في PayMob', [
                    'status' => $response->status(),
                    'response' => $response->json() ?? $response->body()
                ]);
                return null;
            }

            $paymentKey = $response->json('token');
            Log::info('تم إنشاء مفتاح الدفع في PayMob بنجاح');
            
            return $paymentKey;
        } catch (\Exception $e) {
            Log::error('استثناء أثناء إنشاء مفتاح الدفع في PayMob', [
                'message' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * الحصول على رابط الدفع
     * 
     * @param string $paymentKey
     * @return string
     */
    public function getPaymentUrl(string $paymentKey): string
    {
        if (empty($paymentKey) || empty($this->iframeId)) {
            throw new \Exception('مفتاح الدفع أو معرف iframe غير صالح');
        }

        $paymentUrl = "https://ksa.paymob.com/api/acceptance/iframes/{$this->iframeId}?payment_token={$paymentKey}";
        
        Log::info('تم إنشاء رابط الدفع في PayMob', [
            'iframe_id' => $this->iframeId
        ]);
        
        return $paymentUrl;
    }

    /**
     * إنشاء رابط دفع كامل
     * 
     * @param float $amount
     * @param string $debtorName
     * @param string $debtorEmail
     * @param string $debtorPhone
     * @param int|null $debtorId
     * @param int|null $installmentId
     * @param string $currency
     * @return array|null ['payment_link' => string, 'order_id' => int]
     */
    public function generatePaymentLink(
        float $amount,
        string $debtorName,
        string $debtorEmail,
        string $debtorPhone,
        ?int $debtorId = null,
        ?int $installmentId = null,
        string $currency = 'SAR'
    ): ?array {
        // 1. الحصول على توكن المصادقة
        $authData = $this->authenticate();
        
        if (!$authData || !isset($authData['token'])) {
            Log::error('Failed to authenticate with Paymob');
            return null;
        }
        
        $authToken = $authData['token'];

        // 2. إنشاء Order في PayMob
        $paymobOrderId = $this->createOrder($authToken, $amount, $currency, [
            [
                'name' => "دفع دين - {$debtorName}",
                'amount_cents' => (int)($amount * 100),
                'description' => "تسوية مبلغ الدين للمديون: {$debtorName}",
                'quantity' => 1,
            ],
        ], $debtorId);
        
        if (!$paymobOrderId) {
            Log::error('Failed to create order in Paymob');
            return null;
        }

        // حفظ معاملة الدفع في قاعدة البيانات
        $paymentTransaction = null;
        if ($debtorId) {
            $paymentTransaction = \App\Models\PaymentTransaction::create([
                'debtor_id' => $debtorId,
                'debt_installment_id' => $installmentId,
                'paymob_order_id' => (string)$paymobOrderId,
                'amount' => $amount,
                'currency' => $currency,
                'status' => 'pending',
            ]);
        }

        // تقسيم الاسم إلى first_name و last_name
        $nameParts = explode(' ', $debtorName, 2);
        $firstName = $nameParts[0] ?? $debtorName;
        $lastName = $nameParts[1] ?? null; // null بدلاً من '' حتى يعمل المنطق في createPaymentKey

        // 3. إنشاء Payment Key
        // لا نمرر last_name إذا كان null - سيتم معالجته في createPaymentKey
        $billingData = [
            'first_name' => $firstName,
            'email' => $debtorEmail,
            'phone' => $debtorPhone,
        ];
        
        // فقط أضف last_name إذا كان موجوداً
        if (!empty($lastName)) {
            $billingData['last_name'] = $lastName;
        }
        
        $paymentKey = $this->createPaymentKey($authToken, $paymobOrderId, $amount, $currency, $billingData);

        if (!$paymentKey) {
            Log::error('Failed to create payment key in Paymob');
            return null;
        }

        // 4. الحصول على رابط الدفع
        $paymentUrl = $this->getPaymentUrl($paymentKey);

        return [
            'payment_link' => $paymentUrl,
            'paymob_order_id' => $paymobOrderId,
            'payment_transaction_id' => $paymentTransaction?->id,
        ];
    }

    /**
     * التحقق من صحة Callback من Paymob
     * 
     * @param array $callbackData
     * @return bool
     */
    public function verifyCallback(array $callbackData): bool
    {
        // التحقق من وجود البيانات الأساسية
        if (!isset($callbackData['obj']) || !isset($callbackData['obj']['id'])) {
            return false;
        }

        // يمكن إضافة المزيد من التحقق هنا حسب متطلبات Paymob
        return true;
    }

    /**
     * Validate callback request using HMAC
     * 
     * @param array $data Callback data from PayMob
     * @return bool True if valid, false otherwise
     */
    public function validateHmac(array $data): bool
    {
        if (empty($this->hmacSecret)) {
            Log::warning('No HMAC secret configured, skipping validation');
            return true;
        }
        
        // For PayMob KSA, check if obj is in the data (new format callback)
        if (isset($data['obj'])) {
            $transactionData = $data['obj'];
            
            $concatenatedString = '';
            $concatenatedString .= isset($transactionData['id']) ? $transactionData['id'] : '';
            $concatenatedString .= isset($transactionData['created_at']) ? $transactionData['created_at'] : '';
            $concatenatedString .= isset($transactionData['amount_cents']) ? $transactionData['amount_cents'] : '';
            $concatenatedString .= isset($transactionData['currency']) ? $transactionData['currency'] : '';
            
            $calculatedHmac = hash_hmac('sha512', $concatenatedString, $this->hmacSecret);
            $providedHmac = isset($data['hmac']) ? $data['hmac'] : '';
            
            $isValid = $calculatedHmac === $providedHmac;
            
            Log::info('HMAC Validation', [
                'calculated' => substr($calculatedHmac, 0, 20) . '...',
                'provided' => substr($providedHmac, 0, 20) . '...',
                'match' => $isValid
            ]);
            
            return $isValid;
        } else {
            // Original HMAC validation for standard PayMob callback
            $concatenatedString = '';
            
            $amount_cents = isset($data['amount_cents']) ? $data['amount_cents'] : '';
            $created_at = isset($data['created_at']) ? $data['created_at'] : '';
            $currency = isset($data['currency']) ? $data['currency'] : '';
            $error_occured = isset($data['error_occured']) ? $data['error_occured'] : '';
            $has_parent_transaction = isset($data['has_parent_transaction']) ? $data['has_parent_transaction'] : '';
            $id = isset($data['id']) ? $data['id'] : '';
            $integration_id = isset($data['integration_id']) ? $data['integration_id'] : '';
            $is_3d_secure = isset($data['is_3d_secure']) ? $data['is_3d_secure'] : '';
            $is_auth = isset($data['is_auth']) ? $data['is_auth'] : '';
            $is_capture = isset($data['is_capture']) ? $data['is_capture'] : '';
            $is_refunded = isset($data['is_refunded']) ? $data['is_refunded'] : '';
            $is_standalone_payment = isset($data['is_standalone_payment']) ? $data['is_standalone_payment'] : '';
            $is_voided = isset($data['is_voided']) ? $data['is_voided'] : '';
            $order = isset($data['order']) ? $data['order'] : '';
            $owner = isset($data['owner']) ? $data['owner'] : '';
            $pending = isset($data['pending']) ? $data['pending'] : '';
            $source_data_pan = isset($data['source_data']['pan']) ? $data['source_data']['pan'] : '';
            $source_data_type = isset($data['source_data']['type']) ? $data['source_data']['type'] : '';
            $source_data_sub_type = isset($data['source_data']['sub_type']) ? $data['source_data']['sub_type'] : '';
            
            $concatenatedString = $amount_cents . $created_at . $currency . $error_occured . $has_parent_transaction . 
                                $id . $integration_id . $is_3d_secure . $is_auth . $is_capture . $is_refunded . 
                                $is_standalone_payment . $is_voided . $order . $owner . $pending . $source_data_pan . 
                                $source_data_type . $source_data_sub_type;
            
            $calculatedHmac = hash_hmac('sha512', $concatenatedString, $this->hmacSecret);
            $providedHmac = isset($data['hmac']) ? $data['hmac'] : '';
            
            $isValid = $calculatedHmac === $providedHmac;
            
            Log::info('HMAC Validation', [
                'calculated' => substr($calculatedHmac, 0, 20) . '...',
                'provided' => substr($providedHmac, 0, 20) . '...',
                'match' => $isValid
            ]);
            
            return $isValid;
        }
    }

    /**
     * الحصول على تفاصيل Transaction
     * 
     * @param string $transactionId
     * @return array|null
     */
    public function getTransactionDetails(string $transactionId): ?array
    {
        $token = $this->getAuthToken();
        
        if (!$token) {
            return null;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$token}",
            ])->get("{$this->baseUrl}/acceptance/transactions/{$transactionId}", [
                'token' => $token,
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Paymob Get Transaction Details Exception', [
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }
}

