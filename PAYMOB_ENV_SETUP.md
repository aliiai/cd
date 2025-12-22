# إعدادات PayMob في ملف .env

## ⚠️ المشكلة الحالية

من السجلات، يظهر أن إعدادات PayMob غير موجودة في ملف `.env`:

```
PayMob API key is not configured
PayMob Integration ID is not configured
PayMob iFrame ID is not configured
PayMob Merchant ID is not configured
```

## ✅ الحل: إضافة الإعدادات التالية في ملف `.env`

افتح ملف `.env` في جذر المشروع وأضف الإعدادات التالية:

```env
# ============================================
# PayMob Payment Gateway Configuration
# ============================================

# API Key من PayMob Dashboard
PAYMOB_API_KEY=your_api_key_here

# Integration ID من PayMob Dashboard
PAYMOB_INTEGRATION_ID=your_integration_id_here

# iFrame ID من PayMob Dashboard
PAYMOB_IFRAME_ID=your_iframe_id_here

# Merchant ID من PayMob Dashboard
PAYMOB_MERCHANT_ID=your_merchant_id_here

# HMAC Secret (اختياري لكن موصى به للأمان)
PAYMOB_HMAC_SECRET=your_hmac_secret_here

# العملة (افتراضي: SAR)
PAYMOB_CURRENCY=SAR

# Base URL (افتراضي: https://ksa.paymob.com/api)
PAYMOB_BASE_URL=https://ksa.paymob.com/api

# Callback URL (يجب أن يكون publicly accessible)
# للاختبار على local: استخدم ngrok
# للـ production: استخدم domain الخاص بك
PAYMOB_CALLBACK_URL=https://yourdomain.com/payment/callback

# تفعيل/تعطيل PayMob (افتراضي: true)
PAYMOB_ENABLED=true
```

## 📝 خطوات الحصول على الإعدادات من PayMob

### 1. تسجيل الدخول إلى PayMob Dashboard
- انتقل إلى: https://ksa.paymob.com
- سجل الدخول بحسابك

### 2. الحصول على API Key
- اذهب إلى **Settings** → **API Keys**
- انسخ **API Key**

### 3. الحصول على Integration ID
- اذهب إلى **Settings** → **Integrations**
- اختر Integration الخاص بك
- انسخ **Integration ID**

### 4. الحصول على iFrame ID
- اذهب إلى **Settings** → **iFrames**
- اختر iFrame الخاص بك
- انسخ **iFrame ID**

### 5. الحصول على Merchant ID
- اذهب إلى **Settings** → **Merchant Info**
- انسخ **Merchant ID**

### 6. الحصول على HMAC Secret (اختياري)
- اذهب إلى **Settings** → **Security**
- انسخ **HMAC Secret**

### 7. تكوين Callback URL
- اذهب إلى **Settings** → **Webhooks** أو **Callbacks**
- أضف Callback URL: `https://yourdomain.com/payment/callback`
- تأكد من أن URL **publicly accessible**

## 🔧 للاختبار على Local Development

### استخدام ngrok

1. **تثبيت ngrok:**
   ```bash
   # Windows: قم بتحميل ngrok من https://ngrok.com
   # أو استخدم Chocolatey:
   choco install ngrok
   ```

2. **تشغيل ngrok:**
   ```bash
   ngrok http 8000
   ```

3. **نسخ HTTPS URL:**
   - ستحصل على URL مثل: `https://abc123.ngrok.io`
   - استخدمه في `.env`:
     ```env
     PAYMOB_CALLBACK_URL=https://abc123.ngrok.io/payment/callback
     ```

4. **تكوين نفس URL في PayMob Dashboard**

## ✅ بعد إضافة الإعدادات

1. **احفظ ملف `.env`**

2. **امسح Cache:**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

3. **اختبر إنشاء رابط الدفع:**
   - انتقل إلى صفحة المديون
   - اضغط على "إنشاء رابط دفع"
   - تحقق من السجلات في `storage/logs/laravel.log`

## 🔍 التحقق من الإعدادات

بعد إضافة الإعدادات، تحقق من السجلات. يجب أن ترى:

```
PayMob API key loaded successfully
PayMob authentication successful
```

بدلاً من:

```
PayMob API key is not configured
PayMob authentication failed
```

## ⚠️ ملاحظات مهمة

1. **لا تشارك ملف `.env`** - يحتوي على معلومات حساسة
2. **استخدم HTTPS** في production
3. **تأكد من أن Callback URL publicly accessible**
4. **اختبر على local أولاً** باستخدام ngrok
5. **احفظ نسخة احتياطية** من الإعدادات في مكان آمن

## 🐛 استكشاف الأخطاء

### المشكلة: "PayMob API key is not configured"
**الحل:** تأكد من إضافة `PAYMOB_API_KEY` في `.env`

### المشكلة: "Authentication failed"
**الحل:** 
- تحقق من صحة API Key
- تحقق من اتصال الإنترنت
- تحقق من Base URL

### المشكلة: "Callback not received"
**الحل:**
- تحقق من أن Callback URL publicly accessible
- استخدم ngrok للاختبار على local
- تحقق من تكوين Callback URL في PayMob Dashboard

---

**تاريخ الإنشاء:** 2025-12-22  
**آخر تحديث:** 2025-12-22

