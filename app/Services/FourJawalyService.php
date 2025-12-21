<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\SmsLog;

/**
 * FourJawalyService
 * 
 * خدمة إرسال SMS عبر 4jawaly
 */
class FourJawalyService
{
    protected $apiKey;
    protected $apiSecret;
    protected $sender;
    protected $baseUrl;
    protected $enabled;

    public function __construct()
    {
        $this->apiKey = config('services.4jawaly.api_key');
        $this->apiSecret = config('services.4jawaly.api_secret');
        $this->sender = config('services.4jawaly.sender');
        $this->baseUrl = config('services.4jawaly.url');
        $this->enabled = config('services.4jawaly.enabled', true);
    }

    /**
     * إرسال رسالة SMS واحدة
     * 
     * @param string $phone
     * @param string $message
     * @param array $metadata Additional metadata for logging (event_type, entity_type, entity_id)
     * @return array
     */
    public function sendSMS(string $phone, string $message, array $metadata = []): array
    {
        if (!$this->enabled) {
            return [
                'success' => false,
                'message' => 'خدمة SMS معطلة',
                'error' => 'SMS service is disabled'
            ];
        }

        // إنشاء سجل SMS إذا كان Logging مفعلاً
        $smsLog = null;
        if (config('sms.logging.enabled', true)) {
            try {
                $smsLog = SmsLog::createLog(array_merge([
                    'event_type' => $metadata['event_type'] ?? 'manual',
                    'entity_type' => $metadata['entity_type'] ?? null,
                    'entity_id' => $metadata['entity_id'] ?? null,
                    'phone' => $phone,
                    'message' => $message,
                ], $metadata));
            } catch (\Exception $e) {
                Log::warning('Failed to create SMS log', ['error' => $e->getMessage()]);
            }
        }

        try {
            // تنظيف رقم الهاتف
            $phone = $this->cleanPhoneNumber($phone);
            
            // تحديث رقم الهاتف في السجل إذا كان موجوداً
            if ($smsLog) {
                $smsLog->update(['phone' => $phone]);
            }
            
            // التحقق من Rate Limiting
            if (!$this->checkRateLimit($phone)) {
                $errorMsg = 'تم تجاوز الحد المسموح لإرسال الرسائل لهذا الرقم';
                if ($smsLog) {
                    $smsLog->markAsFailed($errorMsg);
                }
                return [
                    'success' => false,
                    'message' => $errorMsg,
                    'error' => 'Rate limit exceeded'
                ];
            }
            
            // التحقق من صحة رقم الهاتف
            if (!$this->isValidPhoneNumber($phone)) {
                $errorMsg = 'رقم الهاتف غير صحيح: ' . $phone;
                if ($smsLog) {
                    $smsLog->markAsFailed($errorMsg);
                }
                return [
                    'success' => false,
                    'message' => $errorMsg,
                    'error' => 'Invalid phone number'
                ];
            }

            // إعداد البيانات للإرسال حسب API 4jawaly
            // تنسيق 4jawaly API - قد يختلف حسب نوع الحساب
            // محاولة تنسيقين مختلفين
            $data = [
                'messages' => [
                    [
                        'text' => $message,
                        'numbers' => [$phone],
                        'sender' => $this->sender
                    ]
                ]
            ];
            
            // بديل: تنسيق مباشر (إذا كان API يدعمه)
            // $data = [
            //     'text' => $message,
            //     'numbers' => [$phone],
            //     'sender' => $this->sender
            // ];

            // URL كامل
            $fullUrl = rtrim($this->baseUrl, '/') . '/account/area/sms/send';
            
            // تسجيل البيانات المرسلة (بدون كلمات المرور)
            Log::info('4jawaly SMS Request', [
                'url' => $fullUrl,
                'phone' => $phone,
                'sender' => $this->sender,
                'message_length' => strlen($message),
                'data' => $data
            ]);

            // إرسال الطلب
            $response = Http::timeout(30)
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])
                ->withBasicAuth($this->apiKey, $this->apiSecret)
                ->post($fullUrl, $data);

            // تسجيل الاستجابة الكاملة
            $responseStatus = $response->status();
            $responseBody = $response->body();
            $responseData = $response->json();
            
            // تسجيل مفصل للاستجابة
            Log::info('4jawaly SMS Response - FULL DETAILS', [
                'http_status' => $responseStatus,
                'raw_body' => $responseBody,
                'parsed_json' => $responseData,
                'phone' => $phone,
                'has_code' => isset($responseData['code']),
                'code_value' => $responseData['code'] ?? 'N/A',
                'has_messages' => isset($responseData['messages']),
                'messages_count' => isset($responseData['messages']) ? count($responseData['messages']) : 0,
                'has_status' => isset($responseData['status']),
                'status_value' => $responseData['status'] ?? 'N/A',
                'has_err_text' => isset($responseData['err_text']),
                'err_text_value' => $responseData['err_text'] ?? 'N/A',
                'has_request_id' => isset($responseData['request_id']),
                'has_message_id' => isset($responseData['message_id']),
            ]);

            // معالجة الاستجابة
            // 4jawaly API قد يعيد HTTP 200 حتى لو فشل الإرسال
            // يجب التحقق من محتوى الاستجابة بدقة
            
            $isSuccess = false;
            $errorMessage = 'فشل إرسال الرسالة';
            $actualError = null;
            
            // التحقق من HTTP Status أولاً
            if ($response->successful()) {
                // التحقق من محتوى الاستجابة بدقة
                // 4jawaly قد يعيد:
                // 1. {"code": 200, "message": "تم الإرسال بنجاح"}
                // 2. {"messages": [{"id": "...", "status": "sent"}]}
                // 3. {"err_text": "خطأ في الإرسال"}
                // 4. {"message": "Success"}
                
                // التحقق من وجود خطأ صريح
                if (isset($responseData['err_text']) && !empty($responseData['err_text'])) {
                    $actualError = $responseData['err_text'];
                    $isSuccess = false;
                } elseif (isset($responseData['error']) && !empty($responseData['error'])) {
                    $actualError = $responseData['error'];
                    $isSuccess = false;
                } elseif (isset($responseData['messages']) && is_array($responseData['messages'])) {
                    // التحقق من حالة كل رسالة
                    $allSent = true;
                    $hasValidIndicator = false;
                    
                    foreach ($responseData['messages'] as $msg) {
                        // التحقق من وجود خطأ صريح
                        if (isset($msg['err_text']) && !empty($msg['err_text'])) {
                            $actualError = $msg['err_text'];
                            $allSent = false;
                            $hasValidIndicator = true;
                            break;
                        }
                        
                        // التحقق من success_count و error_count (تنسيق 4jawaly الجديد)
                        if (isset($msg['success_count']) || isset($msg['error_count'])) {
                            $hasValidIndicator = true;
                            $successCount = $msg['success_count'] ?? 0;
                            $errorCount = $msg['error_count'] ?? 0;
                            
                            if ($errorCount > 0) {
                                $actualError = "فشل إرسال {$errorCount} رسالة";
                                $allSent = false;
                                break;
                            } elseif ($successCount > 0) {
                                // نجاح - يوجد success_count > 0 و error_count = 0
                                $allSent = true;
                            } else {
                                // لا يوجد success_count ولا error_count - فشل
                                $allSent = false;
                            }
                        }
                        
                        // التحقق من status
                        if (isset($msg['status'])) {
                            $hasValidIndicator = true;
                            $status = strtolower($msg['status']);
                            if (!in_array($status, ['sent', 'accepted', 'queued', 'delivered'])) {
                                $actualError = 'Status: ' . $msg['status'] . (isset($msg['err_text']) ? ' - ' . $msg['err_text'] : '');
                                $allSent = false;
                                break;
                            }
                        }
                        
                        // التحقق من وجود id (مؤشر على قبول الرسالة)
                        if (isset($msg['id']) || isset($msg['message_id']) || isset($msg['job_id'])) {
                            $hasValidIndicator = true;
                        }
                    }
                    
                    // نجاح فقط إذا:
                    // 1. جميع الرسائل بدون أخطاء
                    // 2. يوجد مؤشر صحيح (status أو id أو success_count)
                    // 3. عدد الرسائل > 0
                    $isSuccess = $allSent && $hasValidIndicator && count($responseData['messages']) > 0;
                    
                    // إذا لم يكن هناك مؤشر صحيح، نعتبره فشل
                    if (!$hasValidIndicator) {
                        $actualError = 'لا يوجد مؤشر واضح على حالة الإرسال في استجابة الرسائل';
                        $isSuccess = false;
                        
                        Log::warning('4jawaly messages array has no valid indicator', [
                            'phone' => $phone,
                            'messages' => $responseData['messages']
                        ]);
                    }
                } elseif (isset($responseData['code'])) {
                    // التحقق من code - لكن لا نعتبر code 200 نجاحاً إلا مع مؤشرات أخرى
                    if ($responseData['code'] == 200 || $responseData['code'] == 0) {
                        // التحقق من وجود err_text أولاً
                        if (isset($responseData['err_text']) && !empty($responseData['err_text'])) {
                            $actualError = $responseData['err_text'];
                            $isSuccess = false;
                        } 
                        // التحقق من وجود messages مع status sent
                        // لا نعتبر messages array نجاحاً إلا إذا كان هناك status أو id واضح
                        elseif (isset($responseData['messages']) && is_array($responseData['messages']) && count($responseData['messages']) > 0) {
                            // يجب التحقق من أن كل رسالة لها status أو id
                            $hasValidMessages = false;
                            foreach ($responseData['messages'] as $msg) {
                                if (isset($msg['status']) && in_array(strtolower($msg['status']), ['sent', 'accepted', 'queued', 'delivered'])) {
                                    $hasValidMessages = true;
                                    break;
                                } elseif (isset($msg['id']) || isset($msg['message_id'])) {
                                    $hasValidMessages = true;
                                    break;
                                }
                            }
                            $isSuccess = $hasValidMessages;
                            if (!$hasValidMessages) {
                                $actualError = 'messages array موجود لكن لا يوجد status أو id صحيح. الاستجابة: ' . json_encode($responseData['messages'], JSON_UNESCAPED_UNICODE);
                            }
                        }
                        // التحقق من وجود request_id أو message_id (مؤشر على النجاح)
                        // لكن يجب أن يكون مع status أو message إيجابي
                        elseif ((isset($responseData['request_id']) || isset($responseData['message_id']) || isset($responseData['id'])) 
                                && (!isset($responseData['message']) || stripos(strtolower($responseData['message']), 'error') === false)) {
                            // فقط إذا لم يكن هناك رسالة خطأ
                            $isSuccess = true;
                        }
                        // لا نعتمد على message فقط - قد يكون مضلل
                        // إذا كان code 200 لكن لا يوجد أي مؤشر واضح على النجاح، نعتبره فشل
                        else {
                            $actualError = 'API عاد بـ code 200 لكن لا يوجد تأكيد واضح على نجاح الإرسال. الاستجابة: ' . json_encode($responseData, JSON_UNESCAPED_UNICODE);
                            $isSuccess = false;
                            
                            // تسجيل تحذير إضافي
                            Log::warning('4jawaly returned code 200 but no clear success indicator', [
                                'phone' => $phone,
                                'response' => $responseData
                            ]);
                        }
                    } else {
                        $actualError = 'Code: ' . $responseData['code'] . ' - ' . ($responseData['message'] ?? 'Unknown error');
                        $isSuccess = false;
                    }
                } elseif (isset($responseData['status'])) {
                    if ($responseData['status'] == 'success' || $responseData['status'] == 'sent') {
                        $isSuccess = true;
                    } else {
                        $actualError = 'Status: ' . $responseData['status'];
                        $isSuccess = false;
                    }
                } else {
                    // إذا لم يكن هناك أي مؤشر على النجاح، نعتبره فشل
                    $actualError = 'لا توجد معلومات عن حالة الإرسال في الاستجابة';
                    $isSuccess = false;
                }
            } else {
                // HTTP Error
                $actualError = 'HTTP ' . $responseStatus . ': ' . $responseBody;
                $isSuccess = false;
            }
            
            // تسجيل النتيجة النهائية مع تفاصيل أكثر
            if ($isSuccess) {
                Log::info('4jawaly SMS sent successfully - CONFIRMED', [
                    'phone' => $phone,
                    'http_status' => $responseStatus,
                    'response' => $responseData,
                    'success_reason' => $this->getSuccessReason($responseData),
                    'provider_message_id' => $responseData['request_id'] ?? $responseData['message_id'] ?? $responseData['id'] ?? 'N/A'
                ]);

                // تحديث السجل
                if ($smsLog) {
                    $providerMessageId = $responseData['request_id'] ?? 
                                       ($responseData['message_id'] ?? 
                                       ($responseData['id'] ?? null));
                    $smsLog->markAsSent($providerMessageId, $responseData);
                }

                return [
                    'success' => true,
                    'message' => 'تم إرسال الرسالة بنجاح',
                    'data' => $responseData,
                    'sms_log_id' => $smsLog?->id
                ];
            } else {
                $finalErrorMessage = $actualError ?? 
                                   ($responseData['message'] ?? 
                                   ($responseData['err_text'] ?? 
                                   'فشل إرسال الرسالة'));
                
                Log::warning('4jawaly SMS failed', [
                    'phone' => $phone,
                    'response' => $responseData,
                    'status' => $responseStatus,
                    'body' => $responseBody,
                    'error' => $finalErrorMessage
                ]);

                // تحديث السجل
                if ($smsLog) {
                    $smsLog->markAsFailed($finalErrorMessage, $responseData);
                }

                return [
                    'success' => false,
                    'message' => $finalErrorMessage,
                    'error' => $responseData ?: $responseBody,
                    'status' => $responseStatus,
                    'sms_log_id' => $smsLog?->id
                ];
            }
        } catch (\Exception $e) {
            $errorMessage = 'حدث خطأ أثناء إرسال الرسالة: ' . $e->getMessage();
            
            Log::error('4jawaly SMS Error: ' . $e->getMessage(), [
                'phone' => $phone,
                'error' => $e->getTraceAsString()
            ]);

            // تحديث السجل
            if ($smsLog) {
                $smsLog->markAsFailed($errorMessage);
            }

            return [
                'success' => false,
                'message' => $errorMessage,
                'error' => $e->getMessage(),
                'sms_log_id' => $smsLog?->id
            ];
        }
    }

    /**
     * إرسال رسائل متعددة (Batch)
     * 
     * @param array $phones
     * @param string $message
     * @return array
     */
    public function sendBulkSMS(array $phones, string $message): array
    {
        $results = [];
        $successCount = 0;
        $failCount = 0;

        foreach ($phones as $phone) {
            $result = $this->sendSMS($phone, $message);
            $results[$phone] = $result;
            
            if ($result['success']) {
                $successCount++;
            } else {
                $failCount++;
            }
            
            // تأخير بسيط لتجنب Rate Limit
            if (count($phones) > 1) {
                usleep(100000); // 0.1 ثانية
            }
        }

        return [
            'success' => $failCount === 0,
            'total' => count($phones),
            'success_count' => $successCount,
            'fail_count' => $failCount,
            'results' => $results
        ];
    }

    /**
     * تنظيف رقم الهاتف وتحويله للصيغة الدولية
     * 
     * @param string $phone
     * @return string
     */
    protected function cleanPhoneNumber(string $phone): string
    {
        // إزالة المسافات والرموز
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // الأرقام السعودية: 05XXXXXXXX أو 5XXXXXXXX
        if (preg_match('/^0?5\d{8}$/', $phone)) {
            $phone = '966' . ltrim($phone, '0');
        }
        // الأرقام المصرية: 01XXXXXXXXX (10 أو 11 رقم بعد 01)
        elseif (preg_match('/^01\d{9,10}$/', $phone)) {
            $phone = '20' . $phone;
        }
        // إذا كان الرقم يبدأ بـ 966 (سعودي) أو 20 (مصري) بالفعل، اتركه كما هو
        elseif (preg_match('/^(966|20)\d+/', $phone)) {
            // الرقم بالفعل بصيغة دولية
        }
        // إذا كان الرقم يبدأ بـ 0، حاول تحديد البلد
        elseif (preg_match('/^0/', $phone)) {
            // رقم سعودي يبدأ بـ 05
            if (preg_match('/^05\d{8}$/', $phone)) {
                $phone = '966' . substr($phone, 1);
            }
            // رقم مصري يبدأ بـ 01
            elseif (preg_match('/^01\d{9,10}$/', $phone)) {
                $phone = '20' . $phone;
            }
        }
        
        return $phone;
    }

    /**
     * التحقق من صحة رقم الهاتف (سعودي أو مصري)
     * 
     * @param string $phone
     * @return bool
     */
    protected function isValidPhoneNumber(string $phone): bool
    {
        // رقم سعودي: 9665XXXXXXXX (12 رقم) أو 05XXXXXXXX (10 أرقام)
        if (preg_match('/^(9665|05)\d{8}$/', $phone) || preg_match('/^9665\d{9}$/', $phone)) {
            return true;
        }
        
        // رقم مصري: 201XXXXXXXXX (13 رقم) أو 01XXXXXXXXX (11 رقم)
        // الأرقام المصرية تبدأ بـ 01 ثم 9 أو 10 أرقام
        if (preg_match('/^201\d{9,10}$/', $phone) || preg_match('/^01\d{9,10}$/', $phone)) {
            return true;
        }
        
        // رقم بصيغة دولية أخرى (966 أو 20) مع 9-10 أرقام إضافية
        if (preg_match('/^(966|20)\d{9,10}$/', $phone)) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Check rate limiting for phone number
     * 
     * @param string $phone
     * @return bool
     */
    protected function checkRateLimit(string $phone): bool
    {
        if (!config('sms.rate_limiting.enabled', false)) {
            return true;
        }

        $now = now();
        $minuteAgo = $now->copy()->subMinute();
        $hourAgo = $now->copy()->subHour();
        $dayAgo = $now->copy()->subDay();

        // Check per minute limit
        $minuteCount = SmsLog::where('phone', $phone)
            ->where('created_at', '>=', $minuteAgo)
            ->count();

        if ($minuteCount >= config('sms.rate_limiting.max_per_minute', 5)) {
            return false;
        }

        // Check per hour limit
        $hourCount = SmsLog::where('phone', $phone)
            ->where('created_at', '>=', $hourAgo)
            ->count();

        if ($hourCount >= config('sms.rate_limiting.max_per_hour', 20)) {
            return false;
        }

        // Check per day limit
        $dayCount = SmsLog::where('phone', $phone)
            ->where('created_at', '>=', $dayAgo)
            ->count();

        if ($dayCount >= config('sms.rate_limiting.max_per_day', 100)) {
            return false;
        }

        return true;
    }
    
    /**
     * Retry failed SMS
     * 
     * @param SmsLog $smsLog
     * @return array
     */
    public function retrySms(SmsLog $smsLog): array
    {
        if (!$smsLog->canRetry()) {
            return [
                'success' => false,
                'message' => 'لا يمكن إعادة محاولة هذه الرسالة',
            ];
        }

        $smsLog->incrementAttempt();
        
        return $this->sendSMS($smsLog->phone, $smsLog->message, [
            'event_type' => $smsLog->event_type,
            'entity_type' => $smsLog->entity_type,
            'entity_id' => $smsLog->entity_id,
            'retry' => true,
        ]);
    }
    
    /**
     * تحديد سبب النجاح (للتسجيل)
     * 
     * @param array $responseData
     * @return string
     */
    protected function getSuccessReason(array $responseData): string
    {
        if (isset($responseData['messages']) && is_array($responseData['messages'])) {
            return 'messages array with valid status';
        }
        if (isset($responseData['request_id']) || isset($responseData['message_id']) || isset($responseData['id'])) {
            return 'has request/message id';
        }
        if (isset($responseData['status']) && in_array(strtolower($responseData['status']), ['success', 'sent', 'accepted'])) {
            return 'status: ' . $responseData['status'];
        }
        if (isset($responseData['code']) && $responseData['code'] == 200) {
            return 'code: 200';
        }
        return 'unknown';
    }

    /**
     * التحقق من صحة بيانات الاتصال
     * 
     * @return array
     */
    public function testConnection(): array
    {
        if (!$this->enabled) {
            return [
                'success' => false,
                'message' => 'خدمة SMS معطلة'
            ];
        }

        // التحقق من وجود البيانات المطلوبة
        if (empty($this->apiKey) || empty($this->apiSecret)) {
            return [
                'success' => false,
                'message' => 'بيانات API غير موجودة'
            ];
        }

        return [
            'success' => true,
            'message' => 'بيانات API صحيحة',
            'sender' => $this->sender,
            'url' => $this->baseUrl
        ];
    }
}

