# إصلاح خطأ 401 "incorrect credentials" في PayMob

## 🔍 المشكلة

من السجلات، كانت المشكلة:

```
[2025-12-22 12:15:52] local.INFO: PayMob API key loaded successfully {"length":264} 
[2025-12-22 12:15:52] local.INFO: Using API_KEY directly as token (length > 100)  
[2025-12-22 12:15:52] local.INFO: إنشاء طلب في PayMob {"amount_cents":5000,"merchant_order_id":"debtor_25"} 
[2025-12-22 12:15:53] local.ERROR: خطأ في إنشاء الطلب في PayMob {"status":401,"response":{"detail":"incorrect credentials"}} 
```

### السبب

الكود كان يحاول استخدام **API Key مباشرة كـ token** إذا كان طوله أكثر من 100 حرف. هذا غير صحيح لأن:

1. **API Key** هو مفتاح للوصول إلى API
2. **Auth Token** يجب الحصول عليه من `/auth/tokens` endpoint
3. لا يمكن استخدام API Key مباشرة في Authorization header

## ✅ الحل

تم إصلاح الكود ليقوم بـ:

1. **إزالة الكود الذي يستخدم API Key مباشرة كـ token**
2. **استدعاء `/auth/tokens` دائماً** للحصول على token صحيح
3. **تحسين logging** لتسهيل debugging

### التغييرات المنفذة

#### 1. إزالة استخدام API Key مباشرة

**قبل:**
```php
// إذا كان API_KEY طويلاً (أكثر من 100 حرف)، استخدمه مباشرة كـ token
if (strlen($this->apiKey) > 100) {
    Log::info('Using API_KEY directly as token (length > 100)');
    $this->authToken = $this->apiKey;
    return [
        'token' => $this->apiKey,
        'success' => true
    ];
}
```

**بعد:**
```php
// تم إزالة هذا الكود - يجب دائماً استدعاء /auth/tokens
```

#### 2. تحسين Authentication Method

**الآن الكود:**
- دائماً يستدعي `/auth/tokens` endpoint
- يسجل معلومات مفصلة عن الطلب والاستجابة
- يعالج الأخطاء بشكل أفضل

#### 3. تحسين Error Logging

**الآن يتم تسجيل:**
- Status code
- Response body
- Response headers
- Request URL

## 🔧 خطوات التحقق

### 1. مسح Cache

```bash
php artisan config:clear
php artisan cache:clear
```

### 2. التحقق من الإعدادات

تأكد من أن جميع الإعدادات موجودة في `.env`:

```env
PAYMOB_API_KEY=your_api_key_here
PAYMOB_INTEGRATION_ID=your_integration_id_here
PAYMOB_IFRAME_ID=your_iframe_id_here
PAYMOB_MERCHANT_ID=your_merchant_id_here
```

### 3. اختبار إنشاء رابط الدفع

بعد الإصلاح، يجب أن ترى في السجلات:

```
PayMob API key loaded successfully
Attempting PayMob authentication
PayMob authentication response
PayMob authentication successful
```

بدلاً من:

```
Using API_KEY directly as token (length > 100)
خطأ في إنشاء الطلب في PayMob {"status":401,"response":{"detail":"incorrect credentials"}}
```

## 📝 ملاحظات مهمة

### 1. API Key vs Auth Token

- **API Key**: مفتاح للوصول إلى API (يستخدم في `/auth/tokens`)
- **Auth Token**: رمز مصادقة يتم الحصول عليه من `/auth/tokens` (يستخدم في باقي الـ requests)

### 2. Flow الصحيح

```
1. استخدام API Key → POST /auth/tokens
2. الحصول على Auth Token
3. استخدام Auth Token → POST /ecommerce/orders
4. استخدام Auth Token → POST /acceptance/payment_keys
```

### 3. إذا استمر الخطأ 401

تحقق من:
- ✅ API Key صحيح من PayMob Dashboard
- ✅ Base URL صحيح: `https://ksa.paymob.com/api`
- ✅ لا توجد مسافات إضافية في API Key
- ✅ API Key نشط وغير منتهي الصلاحية

## 🐛 استكشاف الأخطاء

### المشكلة: لا يزال الخطأ 401 يظهر

**الحل:**
1. تحقق من السجلات - يجب أن ترى محاولة authentication
2. تحقق من API Key في PayMob Dashboard
3. جرب API Key في Postman أو curl للتأكد من صحته

### المشكلة: "Authentication response missing token"

**الحل:**
- تحقق من response من PayMob
- راجع السجلات لمعرفة response الكامل
- تأكد من أن PayMob يعيد token في response

---

**تاريخ الإصلاح:** 2025-12-22  
**الإصدار:** 2.1  
**الحالة:** ✅ تم الإصلاح

